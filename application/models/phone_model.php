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

        prepare_variables($this, $variables);
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
}