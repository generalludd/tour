<?php defined('BASEPATH') OR exit('No direct script access allowed');


class admin extends MY_Controller {

	function __construct() {
		parent::__construct();
		if ($this->session->userdata("user_id") == 1) {
			$this->load->model("auth_model");
			$this->load->model('updates');
		}
		else {
			redirect("/");
		}
	}

	function index() {
		$data["target"] = "admin/panel";
		$data["title"] = "Site Administration";
		$this->load->view("page/index", $data);
		delete_cookie('admin');

	}

	function masquerade() {
		if ($this->session->userdata("username") == "administrator") {
			$user_id = $this->uri->segment(3);
			$this->load->model("user_model");
			$user = $this->user_model->get($user_id);
			if ($user) {
				$data['username'] = $user->username;
				$data['role'] = $user->role;
				$data['user_id'] = $user->id;
				$this->session->set_userdata($data);
				redirect("/");
			}
		}
	}

	function show_log() {
		$options = [];
		if ($this->input->get_post("id")) {
			$options["id"] = $this->input->get_post("id");
		}

		if ($this->input->get_post("username")) {
			$options["username"] = $this->input->get_post("username");
		}

		if ($this->input->get_post("action")) {
			$options["action"] = $this->input->get_post("action");
		}

		if ($this->input->get_post("time_start") && $this->input->get_post("time_end")) {
			$time_start = format_date($this->input->get_post("time_start"), "mysql");
			$time_end = format_date($this->input->get_post("time_end"), "mysql");
			$time_end .= " 23:59:59";// make the end time the end of the same day
			$options["date_range"]["time_start"] = $time_start;
			$options["date_range"]["time_end"] = $time_end;
		}

		$data["header"] = ["username", "timestamp", "action"];
		$data["logs"] = $this->auth_model->get_log($options);
		$data["options"] = $options;
		$data["target"] = "admin/log";
		$data["title"] = "User Log";
		$this->load->view("page/index", $data);
	}

	function search_log() {
		$users = $this->auth_model->get_usernames();
		$data["users"] = get_keyed_pairs($users, ["username", "user"], TRUE);
		$data["actions"] = ["login" => "login", "logout" => "logout"];
		$this->load->view("admin/search_log", $data);
	}



}
