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

    function edit ()
    {
        $id = $this->input->get("id");
        $person_id = $this->input->get("person_id");
        $phone = $this->phone->get($id);
        $is_primary = $this->phone->get_phone_person($id, array("is_primary"));
        $phone->is_primary = $is_primary->is_primary;
        $data["phone"] = $phone;
        $data["person_id"] = $person_id;
        $data["action"] = "update";
        if ($this->input->get("ajax") == 1) {
            $this->load->view("phone/edit", $data);
        }
    }

    function insert ()
    {
        $person_id = $this->input->post("person_id");
       $id =  $this->phone->insert_for_person($person_id);
        $is_primary = $this->input->post("is_primary");
        $this->phone->set_primary($id, $is_primary);
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

    function update ()
    {
        $id = $this->input->post("phone_id");
        $this->phone->update($id);
        $result = $this->phone->get_phone_person($id, array(
                "person_id"
        ));
        $is_primary = $this->input->post("is_primary");
        $this->phone->set_primary($id, $is_primary);
        redirect("person/view/$result->person_id");
    }

    function delete ()
    {
        $id = $this->input->post("id");
        $this->phone->delete($id);
        print TRUE;
    }

    function fix ()
    {
        // $phones = $this->phone->fix();
        // foreach($phones as $phone){
        // $data = array("person_id"=>$phone->person_id,
        // "phone_id"=>$phone->phone_id);
        // $this->db->insert("phone_person",$data);
        // print $this->db->last_query();
        // }
    }
}