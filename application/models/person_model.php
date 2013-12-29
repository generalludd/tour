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
    var $address_id;

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
                "salutation",
                "address_id"
        );
        prepare_variables($this, $variables);
    }

    function get ($id, $fields = false)
    {
        $this->db->where("person.id", $id);
        $this->db->from("person");
        if ($fields) {
            $this->db->select($fields);
        }
        $result = $this->db->get()->row();
        return $result;
    }

    function get_all ()
    {
        $this->db->from("address");
        $this->db->from("person");
        $this->db->order_by("person.last_name", "ASC");
        $this->db->order_by("person.first_name", "ASC");
        $this->db->order_by("person.address_id", "ASC");
        $this->db->where("person.address_id = address.id", NULL, FALSE);
        $result = $this->db->get()->result();
        return $result;
    }

    function insert ($include_address = TRUE)
    {
        $this->prepare_variables();
        $this->db->insert("person", $this);
        $id = $this->db->insert_id();
        if ($include_address) {
            $this->load->model("address_model");
            $this->address_model->insert_for_user($id);

            $this->load->model("phone_model");
            $this->phone_model->insert_for_user($id);
        }
        return $id;
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

    function find_people ($name, $payer_id = FALSE, $tour_id = FALSE)
    {
        $this->db->where("CONCAT(`first_name`,' ', `last_name`) LIKE '%$name%'", NULL, FALSE);

        $this->db->order_by("first_name", "ASC");
        $this->db->order_by("last_name", "ASC");
        $this->db->from("person");
        //The following are deprectated steps a vain attempt at selecting tourists not already added to a tour.
        /*if ($payer_id) {
            $this->db->where("person.id != '$payer_id'", NULL, FALSE);
        }
        if ($tour_id) {
            $this->db->join("tourist", "tourist.person_id = person.id");
            $this->db->where_not_in("tourist.tour_id", $tour_id);
        }*/
        $result = $this->db->get()->result();
        return $result;
    }

    function get_housemates($address_id, $person_id){
        $this->db->where("person.address_id", $address_id);
        $this->db->where("person.id !=", $person_id);
        $this->db->order_by("person.last_name, person.first_name");
        $this->db->from("person");
        $result = $this->db->get()->result();
        return $result;
    }
}