<?php

class MY_Controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		if (!is_logged_in($this->session->all_userdata())) {
			// determine the query to redirect after login.
			$this->load->model("auth_model");
			$this->auth_model->log(1, "logout");
			$uri = $_SERVER["REQUEST_URI"];
			if ($uri != "/auth") {
				bake_cookie("uri", $uri);
			}
			redirect("auth");
			die();
		}
		$this->load->model("variable_model", "variable");

		//find out when the last database backup happened
		$this->load->model("logging_model", "logging");
		$latest_backup = $this->logging->get_latest("backup");
		//more than 4 weeks since last backup
		if (!empty($latest_backup)) {
			$backup_time = date("U") - $latest_backup->unix_time;
		}
		else {
			$backup_time = BACKUP_THRESHOLD;//2 weeks
		}
		define("BACKUP_STATUS", $backup_time);
	}

}
