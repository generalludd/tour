<?php
defined('BASEPATH') or exit('No direct script access allowed');

// index.php Chris Dart Dec 10, 2013 8:14:38 PM chrisdart@cerebratorium.com
class Person extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("person_model", "person");
        $this->load->model("phone_model", "phone");
        $this->load->model("address_model", "address");
    }

    function index ()
    {
        $this->view_all();
    }

    function view ()
    {
        $id = $this->uri->segment(3);
        $data = array();
        // $data["person"] = array();

        $person = $this->person->get($id);

        $phones = $this->phone->get_for_person($id);
        $person->phones = $phones;
        $person->address = $this->address->get($person->address_id);
        $data["id"] = $id;
        $data["person"] = $person;
        $data["title"] = sprintf("Person Record: %s %s", $data["person"]->first_name, $data["person"]->last_name);
        $data["target"] = "person/view";
        $this->load->view("page/index", $data);
    }

    function view_all ()
    {
        $data["people"] = $this->person->get_all();
        $data["title"] = "Address Book";
        $data["target"] = "person/list";
        $this->load->view("page/index", $data);
    }

    function find_by_name ()
    {
        $name = $this->input->get("name");
        $tour_id = FALSE;
        if ($this->input->get("tour_id")) {
            $tour_id = $this->input->get("tour_id");
        }
        $payer_id = FALSE;
        if ($this->input->get("payer_id")) {
            $payer_id = $this->input->get("payer_id");
        }
        $data["payer_id"] = $payer_id;
        $data["tour_id"] = $tour_id;
        $target = "person/mini_list";
        $data["people"] = $this->person->find_people($name, $payer_id, $tour_id);
        $this->load->view($target, $data);
    }

    function edit ()
    {
        $id = $this->uri->segment(3);
        $data = array();
        $data["person"] = $this->person->get($id);
        $data["title"] = sprintf("Person Record: %s %s", $data["person"]->first_name, $data["person"]->last_name);
        $data["phones"] = $this->phone->get_for_person(1);
        $data["target"] = "person/edit";
        $this->load->view("page/index", $data);
    }

    function create ()
    {
        // create a record in the db and get the insertion id. Then go to the
        // edit user page with
        $data["person"] = FALSE;
        $data["action"] = "insert";
        $this->load->view("person/edit", $data);
    }

    function insert ()
    {
        $person_id = $this->person->insert(FALSE);

        redirect("person/view/$person_id");
    }

    function update ()
    {
        $id = $this->input->post("id");
        $this->person->update("id");
        redirect("person/view/$id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
        );
        $this->person->update($id, $values);
        echo $this->input->post("value");
    }
}