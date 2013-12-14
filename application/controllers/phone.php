<?php defined('BASEPATH') OR exit('No direct script access allowed');

// phone.php Chris Dart Dec 13, 2013 6:40:43 PM chrisdart@cerebratorium.com

class Phone extends MY_Controller
{

    function __construct ()
    {

        parent::__construct();
        $this->load->model("phone_model","phone");
    }

    function index ()
    {

        $this->view();
    }

    function update_value ()
    {

        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim(
                        $this->input->post("value"))
        );
        $this->phone->update($id, $values);
        print $this->input->post("value");
    }
}