<?php
defined('BASEPATH') or exit('No direct script access allowed');

// phone_model.php Chris Dart Dec 10, 2013 10:04:19 PM
// chrisdart@cerebratorium.com
class Phone_model extends CI_Model
{
    var $phone;
    var $phone_type;

    function __construct ()
    {

        parent::__construct();
    }

    function prepare_variables ()
    {

        $variables = array(
                "phone",
                "phone_type"
        );

        if($this->input->post("phone")){
            $this->phone = $this->input->post("phone");
        }

        if($this->input->post("phone_type")){
            $this->phone_type = $this->input->post("phone_type");
        }

        //prepare_variables($this, $variables);
    }

    function insert_for_person ($person_id)
    {

        $this->prepare_variables();
        $this->db->insert("phone", $this);
        $id = $this->db->insert_id();
        $relation_array = array(
                "person_id" => $person_id,
                "phone_id" => $id
        );
        if ($this->input->post("phone_is_primary")) {
            $relation_array["is_primary"] = $this->input->post(
                    "phone_is_primary");
        }

        $this->db->insert("phone_person", $relation_array);
    }

    function get_for_person ($person_id)
    {

        $this->db->from("phone_person");
        $this->db->join("phone", "phone_person.phone_id = phone.id");
        $this->db->select("phone.id,phone.phone,phone.phone_type");
        $this->db->where("phone_person.person_id", $person_id);
        $result = $this->db->get()->result();
        return $result;
    }

    function update ($id, $values = array())
    {

        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("phone", $this);
        } else {
            $this->db->update("phone", $values);
            if ($values == 1) {
                $keys = array_keys($values);
                return $this->get_value($id, $keys[0]);
            }
        }
    }
}