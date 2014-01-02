<?php defined('BASEPATH') OR exit('No direct script access allowed');

class user extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model("user_model", "user");
		$this->load->model("variable_model","variable");
	}

	function view()
	{
		$id = $this->uri->segment(3);
		$user = $this->user->get($id);
		$data["id"] = $id;
		$data["user"] = $user;
		$data["target"] = "user/view";
		$data["title"] = "Viewing Information for $user->first_name $user->last_name";

		$this->load->view("page/index", $data);

	}


	function edit_password()
	{
		$id = $this->input->post("id");
		$user_id = $this->session->userdata("user_id");
		if($id == $user_id || $user_id == 1){
			$data["id"] = $id;
			$this->load->view("auth/changepass", $data);
		}
	}

	function change_password()
	{
		$output = "You are not authorized to do this!";
		$id = $this->input->post("id");

		$user_id = $this->session->userdata("user_id");
		$this->load->model("auth_model");
		if($id == $user_id || $user_id == 1){
			$output = "The passwords did not match";
			$current_password = $this->input->post("current_password");

			$new_password = $this->input->post("new_password");

			$check_password = $this->input->post("check_password");

			if($new_password === $check_password){
				$result = $this->auth_model->change_password($id, $current_password, $new_password);
				if($result){
					$output = "Your password has been successfully changed";
				}else{
					$output = "Your original password did not match the one in the database";
				}
			}
		}
		echo $output;
	}

	function create()
	{
		if($this->session->userdata("role") == "admin"){
			$data["role"] = "user";
			$data["action"] = "insert";
			$data["target"] = "user/edit";
			$data["title"] = "Add a new user";
			$roles = $this->variable->get_pairs("user_role");
			$data["roles"] = get_keyed_pairs($roles, array("value","name"));
			$user_status = $this->variable->get_pairs("user_status");
			$data["user_status"] = get_keyed_pairs($user_status, array("value", "name"));
			$data["user"] = NULL;
			if($this->input->get_post("ajax")){
				$this->load->view($data["target"], $data);
			}else{
				$this->load->view("page/index",$data);
			}
		}else {
			$this->index();
		}
	}

	function insert()
	{
		if($this->session->userdata("role") == "admin"){
			$id = $this->user->insert();
			redirect("user/view/$id");
		}
	}

	function update_value()
	{
		$id = $this->input->post("id");
		$values = array($this->input->post("field") => $value = $this->input->post("value"));
		$output = $this->user->update($id, $values);
		echo $output;
	}

}