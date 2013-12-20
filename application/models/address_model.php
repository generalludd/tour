<?php
defined('BASEPATH') or exit('No direct script access allowed');

// address_model.php Chris Dart Dec 10, 2013 9:48:02 PM
// chrisdart@cerebratorium.com
class Address_model extends CI_Model
{
    var $num;
    var $street;
    var $unit_type;
    var $unit;
    var $city;
    var $state;
    var $zip;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "num",
                "street",
                "unit_type",
                "unit",
                "city",
                "state",
                "zip"
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