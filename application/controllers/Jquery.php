<?php
defined('BASEPATH') or exit('No direct script access allowed');

// jquery.php Chris Dart Jan 7, 2014 8:23:55 PM chrisdart@cerebratorium.com
class Jquery extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
    }

    function datefield ()
    {
        $data["target"] = "jquery/datefield";
        $data["title"] = "Datefield";
        $this->load->view("page/index", $data);
    }

    function get_date ()
    {
        return date("m-d-Y");
    }
}