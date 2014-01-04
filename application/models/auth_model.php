<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Auth_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function is_user($username)
	{

		$this->db->where("username", $username);
		$this->db->from("user");
		$count = $this->db->get()->num_rows();
		$result = false;

		if($count == 1){
			$result = TRUE;
		}

		return $result;

	}

	function validate($username, $password)
	{
		$this->db->where("username", $username);
		$this->db->where("password", $this->encrypt($password));
		$this->db->select("user.id as id, role");
		$this->db->from("user");
		$query = $this->db->get();
		$count = $query->num_rows();
		$output = FALSE;
		if($count == 1){
			$output = $query->row();
		}
		return $output;

	}


	function get_role($id)
	{
		$this->db->where("id", $id);
		$this->db->select("role");
		$this->db->from("user");
		$result = $this->db->get()->row();
		return $result->role;
	}


	function set_role($id,$role)
	{
		$this->db->where("id", $id);
		$data["role"] = $role;
		$this->db->update("user", $data);
	}

	function get_username($id)
	{
		$this->load->model("user_model");
		$user = $this->user_model->get($id,"username");
		return $user->username;
	}


	function change_password($id, $old, $new)
	{
		$result = FALSE;
		$username = $this->get_username($id);
		$is_valid = $this->validate($username, $old);

		if($is_valid){
			$this->db->where("username", $username);
			$this->db->where("password",$this->encrypt($old));
			$data["password"] = $this->encrypt($new);
			$this->db->update("user", $data);
			if($this->validate($username, $new)){
				$result = TRUE;
			}
		}
		return $result;
	}


	function encrypt($text)
	{
		return md5(md5($text));
	}

	function email_exists($email){
		$output = FALSE;
		$this->db->where("email",$email);
		$this->db->select("id");
		$this->db->from("user");
		$row = $this->db->get()->row();
		if(!empty($row)){
			$output = $row->id;
		}
		return $output;
	}

	function set_reset_hash($id)
	{
		$hash = $this->encrypt(date("YmdHns"));
		$data["reset_hash"] = $hash;
		$this->db->where("id", $id);
		$this->db->update("user",$data);
		return $hash;
	}


	function reset_password($id, $reset_hash, $password)
	{
		$this->db->where("id", $id);
		$this->db->where("reset_hash", $reset_hash);
		$this->db->where("`reset_hash` IS NOT NULL");
		$data["password"] = $this->encrypt($password);
		$data["reset_hash"] = "";
		$this->db->update("user", $data);
		$username = $this->get_username($id);
		return $this->validate($username, $password);
	}

	function log($user_id, $action)
	{
		$data["user_id"] = $user_id;
		$data["action"] = $action;
		$data["time"] = mysql_timestamp();
		$data["username"] = $this->get_username($id);
		$this->db->insert("user_log",$data);
		return $this->db->insert_id();
	}

	function get_usernames()
	{
		$this->db->select("username");
		$this->db->select("CONCAT(first,' ', last) as user",FALSE);
		$this->db->where("status",1);
		$this->db->from("user");
		$this->db->order_by("username");
		$result = $this->db->get()->result();
		return $result;
	}

	function get_log($options = array())
	{
		if(!empty($options)){
			$keys = array_keys($options);
			$values = array_values($options);
			for($i = 0; $i < count($options); $i++ ){
				$myKey = $keys[$i];
				$myValue = $values[$i];
				if($myKey != "date_range"){
					$this->db->where($myKey, $myValue);
				}else{
					$this->db->where("(time >= '" . $myValue["time_start"] . "' AND time <= '" . $myValue["time_end"] . "')");
				}
			}
		}
		$this->db->select("username,time,action");
		$this->db->from("user_log");
		$this->db->order_by("username","ASC");
		$this->db->order_by("time","DESC");

		$result = $this->db->get()->result();
		return $result;
	}

}