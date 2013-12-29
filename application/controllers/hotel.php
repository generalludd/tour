<?php defined('BASEPATH') OR exit('No direct script access allowed');

// hotel.php Chris Dart Dec 28, 2013 10:08:31 PM chrisdart@cerebratorium.com

class Hotel extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("hotel_model","hotel");
    }
}