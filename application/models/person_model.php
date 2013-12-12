<?php
defined('BASEPATH') or exit('No direct script access allowed');

// person.php Chris Dart Dec 10, 2013 8:15:47 PM chrisdart@cerebratorium.com
class Person_model extends CI_Model
{
    var $first_name;
    var $last_name;
    var $email;
    var $shirt_size;
    var $salutation;

    function __construct ()
    {

        parent::__construct();
    }

    function prepare_variables ()
    {

        $variables = array(
                "first_name",
                "last_name",
                "email",
                "shirt_size",
                "salutation"
        );
        prepare_variables($this, $variables);
    }

    function get ($id)
    {

        $this->db->where("person.id", $id);
        $this->db->from("person");
        $this->db->join("phone_person", "phone_person.person_id=person.id");
        $this->db->join("phone", "phone_person.phone_id=phone.id");
        $this->db->join("address", "person.address_id=address.id");
        $result = $this->db->get()->row();
        return $result;
    }

    function insert ()
    {

        $this->prepare_variables();
        $this->db->insert("person", $this);
        $id = $this->db->insert_id();
        $this->load->model("address_model");
        $this->address_model->insert_for_user($id);
        $this->load->model("phone_model");
        $this->phone_model->insert_for_user($id);
    }

    function update ($id, $values = array())
    {

        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("person", $this);
        } else {
            $this->db->update("person", $values);
            if ($values == 1) {
                $keys = array_keys($values);
                return $this->get_value($id, $keys[0]);
            }
        }
    }
}