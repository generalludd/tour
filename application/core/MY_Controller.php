<?php

class MY_Controller extends CI_Controller
{

    function __construct ()
    {
        parent::__construct();
        if (!is_logged_in($this->session->all_userdata())) {
            // determine the query to redirect after login.
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