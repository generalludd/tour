<?php defined('BASEPATH') OR exit('No direct script access allowed');

// address.php Chris Dart Dec 11, 2013 9:15:31 PM chrisdart@cerebratorium.com

class Address extends My_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("address_model", "address");
    }


    function update()
    {
        $id = $this->input->post("id");
        $this->address->update("id");
        redirect("person/view/$id");
    }

    function update_value()
    {

        $id = $this->input->post("id");
        $values =	array($this->input->post("field") => $value = trim($this->input->post("value")));
        $this->address->update($id, $values);
        echo $this->input->post("value");
    }
}