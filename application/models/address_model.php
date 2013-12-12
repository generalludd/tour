<?php
defined('BASEPATH') or exit('No direct script access allowed');

// address_model.php Chris Dart Dec 10, 2013 9:48:02 PM
// chrisdart@cerebratorium.com
class Address_model extends CI_Model
{
    var $number;
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
                "number",
                "street",
                "unit_type",
                "unit",
                "city",
                "state",
                "zip"
        );
        prepare_variables($this, $variables);
    }

    function insert_for_person ($person_id)
    {

        $this->prepare_variables();
        $this->db->insert("address", $this);
        $id = $this->db->insert_id();
        return $id;
    }
}