<?php

class MY_Controller extends CI_Controller
{

    function __construct ()
    {
        parent::__construct();
        if (!is_logged_in($this->session->all_userdata())) {
            // determine the query to redirect after login.
            $this->load->model("auth_model");
            $this->auth_model->log(1,"logout");
            $uri = $_SERVER["REQUEST_URI"];
            if ($uri != "/auth") {
                bake_cookie("uri", $uri);
            }
            redirect("auth");
            die();
        }
        $this->load->model("variable_model", "variable");
    }
}