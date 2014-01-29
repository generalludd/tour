<?php
defined('BASEPATH') or exit('No direct script access allowed');

// address_model.php Chris Dart Dec 10, 2013 9:48:02 PM
// chrisdart@cerebratorium.com
class Address_model extends CI_Model
{
    var $address;
    var $city;
    var $state;
    var $zip;
    var $formal_salutation;
    var $informal_salutation;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "address",
                "city",
                "state",
                "zip",
                "formal_salutation",
                "informal_salutation"
        );
        prepare_variables($this, $variables);
    }

    function get ($address_id)
    {
        $this->db->where("id", $address_id);
        $this->db->from("address");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_all ($options = array())
    {
        $veterans_only = FALSE;
        $tour_id = FALSE;
        if (array_key_exists("veterans_only", $options) && $options["veterans_only"]) {
            $veterans_only = TRUE;
        }
        if (array_key_exists("tour_id", $options) && $options["tour_id"]) {
            $tour_id = $options["tour_id"];
        }
        $this->db->from("address");
        $this->db->order_by("person.address_id", "ASC");
        $this->db->where("`person`.`address_id` = `address`.`id`", NULL, FALSE);
        $this->db->select("address.address, address.city, address.state,address.zip,address.informal_salutation,address.formal_salutation, person.address_id");
        $this->db->join("person", "person.address_id=address.id");
        $this->db->order_by("address.id");
        if (array_key_exists("export", $options)) {
            $this->db->group_by("address.id");
        }
        if ($veterans_only) {
            $this->db->where("person.is_veteran", 1);
        }
        if ($tour_id) {
            $this->db->join("tourist", "tourist.person_id = person.id");
            $this->db->where("tourist.tour_id", $tour_id);
        }
        $result = $this->db->get()->result();

        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("address", $this);
        $id = $this->db->insert_id();
        return $id;
    }

    function update ($id, $values = array())
    {
        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("address", $this);
        } else {
            $this->db->update("address", $values);
            if ($values == 1) {
                $keys = array_keys($values);
                return $this->get_value($id, $keys[0]);
            }
        }
    }
}