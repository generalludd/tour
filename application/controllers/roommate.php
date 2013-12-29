<?php defined('BASEPATH') OR exit('No direct script access allowed');

// roommate.php Chris Dart Dec 28, 2013 10:08:58 PM chrisdart@cerebratorium.com

class Roommate extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("roommate_model","roommate");
    }
}