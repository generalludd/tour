<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{

	var $first_name;
	var $last_name;
	var $username;
	var $email;
	var $is_active;
	var $role;


	function prepare_variables()
	{

		$variables = array("first_name","last_name","username","email","role","is_active");
		for($i = 0; $i < count($variables); $i++){
			$myVariable = $variables[$i];
			if($this->input->post($myVariable)){
				$this->$myVariable = $this->input->post($myVariable);
			}
		}


	}


	function get_all($options = array()){
		$userRole = $this->session->userdata("role");
		$user_id = $this->session->userdata("user_id");
		$this->db->order_by("status", "DESC");
		$this->db->order_by("role", "DESC");
		$this->db->order_by("last", "ASC");

		if($userRole == 1){
			if($user_id != 1000){  //only the administrator should have any reason to see the other administrators.
				$this->db->where("role != " , 1);
			}
			if(array_key_exists("show_inactive", $options)){
				$this->db->where_in("status", array(0,1,2));
			}else{
				$this->db->where_in("status", array(1,2));
			}

			if(array_key_exists("role",$options)){
				$this->db->where_in("role",$options["role"]);
			}else{
				$this->db->where_in("role", array(2,3));
			}
		}else{
			$this->db->where("role != " , 1);
			$this->db->where("status", 1);

		}

		$this->db->select("user.id, first, last, role, status");
		$this->db->from("user");
		$result = $this->db->get()->result();

		return $result;

	}


	/**
	 * @param int $id
	 * @param string $select
	 * @return string|boolean
	 */
	function get($id, $select = "user.*")
	{

		$this->db->where('user.id', $id);
		$this->db->from('user');

		//create the selection based on both the query submission
		if(is_array($select)){
			foreach($select as $item){
				$this->db->select($item);
			}
		}else{
			$this->db->select($select);
		}
		$result = $this->db->get()->row();

		if($result){
			return $result;
		}else{
			return false;
		}

	}


	function get_user_pairs($role = 2, $status = 1)
	{
		if($role){
			$this->db->where('role', 2);
		}
		if($status){
			$this->db->where('status', 1);
		}
		$this->db->select("CONCAT(first_name,' ',last_name) as user", false);
		$this->db->select('id');
		$direction = "ASC";
		$order_field = "first";

		$this->db->order_by($order_field, $direction);
		$this->db->from('user');
		$query = $this->db->get()->result();
		return $query;
	}



	function get_name($id)
	{
		$this->db->select("CONCAT(first_name,' ',last_name) as user", false);
		$this->db->from('user');
		$this->db->where('id', $id);
		$result = $this->db->get()->row();
		return $result->user;
	}



	/**
	 * Setter
	 */
	function insert()
	{
		$this->prepare_variables();
		$this->db->insert('user', $this);
		$id = $this->db->insert_id();
		$this->set_role($id);
		return $id;
	}

	function set_role($id){
		$data["role"] = $this->input->post("role");
		if($this->session->userdata("role") == "admin" && $this->session->userdata("user_id") == 1){
			$this->db->where("id", $id);
			$this->db->update("user",$data);
		}
	}


	function update($id, $values = array())
{
		$this->db->where("id", $id);
		if(empty($values)){
			$this->prepare_variables();
			$this->db->update("user", $this);
		}else{
			$this->db->update("user", $values);
			$keys = array_keys($values);
			return $this->get_value($id, $keys[0] );
		}
	}

	function get_value($id, $field)
	{
		$this->db->where("id", $id);
		$this->db->select($field);
		$this->db->from("user");
		$output = $this->db->get()->row();
		return $output->$field;
	}


	function get_columns()
	{
		$result = $this->db->query("SHOW COLUMNS FROM `user`")->result();
		return $result;

	}

}