<?php
defined('BASEPATH') or exit('No direct script access allowed');

// address.php Chris Dart Dec 11, 2013 9:15:31 PM chrisdart@cerebratorium.com
class Address extends My_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("address_model", "address");
    }

    function create ()
    {
        $data["person_id"] = $this->input->get("id");
        $data["address"] = FALSE;
        $data["target"] = "address/edit";
        $data["title"] = "Adding an Address";
        $data["action"] = "insert";
        if ($this->input->get("ajax") == 1) {
            $this->load->view("address/edit", $data);
        } else {
            $this->load->view("page/index", $data);
        }
    }

    function insert ()
    {
        $person_id = $this->input->post("person_id");
        $address_id = $this->address->insert();
        $this->load->model("person_model", "person");
        $values = array(
                "address_id" => $address_id
        );
        $this->person->update($person_id, $values);
        redirect("person/view/$person_id");
    }

    function update ()
    {
        $id = $this->input->post("id");
        $this->address->update("id");
        redirect("person/view/$id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
        );
        $this->address->update($id, $values);
        echo $this->input->post("value");
    }
}