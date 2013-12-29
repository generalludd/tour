<?php
defined('BASEPATH') or exit('No direct script access allowed');

// phone.php Chris Dart Dec 13, 2013 6:40:43 PM chrisdart@cerebratorium.com
class Phone extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("phone_model", "phone");
    }

    function index ()
    {
        $this->view();
    }

    function create ()
    {
        $data["person_id"] = $this->input->get("person_id");
        $data["phone"] = FALSE;
        $data["action"] = "insert";
        if ($this->input->get("ajax") == 1) {
            $this->load->view("phone/edit", $data);
        }
    }

    function insert ()
    {
        $person_id = $this->input->post("person_id");
        $this->phone->insert_for_person($person_id);
        redirect("person/view/$person_id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
        );
        $this->phone->update($id, $values);
        print $this->input->post("value");
    }

    function delete ()
    {
        $id = $this->input->post("id");
        $this->phone->delete($id);
        print TRUE;
    }
}