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

    /**
     * expects a person id.
     * @TODO got to create a messaging system if an error occurs
     * that works well regardless of the way a script completes
     */
    function create ()
    {
        if ($this->input->get("id")) {
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
    }

    /**
     * expects a person_id in the get.
     */
    function insert ()
    {
        if ($this->input->post("person_id")) {
            $person_id = $this->input->post("person_id");
            $address_id = $this->address->insert();
            $this->load->model("person_model", "person");
            $values = array(
                    "address_id" => $address_id
            );
            $this->person->update($person_id, $values);
            redirect("person/view/$person_id");
        }
    }

    /**
     * requires address_id and person_id to function;
     */
    function edit ()
    {
        if ($this->input->get("address_id") && $this->input->get("person_id")) {
            $address_id = $this->input->get("address_id");
            $person_id = $this->input->get("person_id");
            $data["person_id"] = $person_id;
            $data["address"] = $this->address->get($address_id);
            $data["action"] = "update";
            if ($this->input->get("ajax") == 1) {
                $this->load->view("address/edit", $data);
            } else {
                $data["target"] = "address/edit";
                $data["title"] = "Editing an Address";
                $this->load->view("page/index", $data);
            }
        }
    }

    /**
     * expects an input id (address id).
     * redirects to the person
     * whose address is being edited.
     */
    function update ()
    {
        if ($this->input->post("id")) {
            $id = $this->input->post("id");
            $person_id = $this->input->post("person_id");
            $this->address->update($id);
            redirect("person/view/$person_id");
        }
    }

    function find_housemate ()
    {
        $data["person_id"] = $this->input->get("person_id");

        if ($this->input->get("ajax") == 1) {
            $this->load->view("address/find_housemate", $data);
        }
    }

    /**
     * requres an id from the post variable (address_id)
     * used mostly by ajax scripts, this allows updating individual values.
     * not currently in use.
     */
    function update_value ()
    {
        if ($this->input->post("id")) {
            $id = $this->input->post("id");
            $values = array(
                    $this->input->post("field") => $value = trim($this->input->post("value"))
            );
            $this->address->update($id, $values);
            echo $this->input->post("value");
        }
    }

    function update_salutations ()
    {
        $this->load->model("person_model", "person");
        $addresses = $this->address->get_all("id");
        foreach ($addresses as $address) {
            $values["formal_salutation"] = $people = $this->person->get_residents($address->id);
            $values["formal_salutation"] = format_salutation($people, "formal");
            $values["informal_salutation"] = format_salutation($people, "informal");
            $this->address->update($address->id, $values);
        }
    }

    function export ()
    {
        $options = $this->input->cookie("person_filters");
        $options = unserialize($options);
        $data["addresses"] = $this->address->get_all($options);
        $data['target'] = 'Address Export';
        $data['title'] = "Export of Addresses";
        $this->load->helper('download');
        $this->load->view('address/export', $data);
    }
}