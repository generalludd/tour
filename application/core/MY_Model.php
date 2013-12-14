<?php
defined('BASEPATH') or exit('No direct script access allowed');

// My_Model.php Chris Dart Dec 13, 2013 8:04:54 PM chrisdart@cerebratorium.com
class MY_Model extends CI_Model
{

    function __construct ()
    {

        parent::__construct();
    }

    function get ($db, $id)
    {

        $this->db->from($db);
        $this->db->where("id", $id);
        return $this->db->get()->row();
    }

    function get_value ($db, $id, $field)
    {

        $this->db->from($db);
        $this->db->select($field);
        $this->db->where("id", $id);
        $value = $this->db->get()->row();
        return $value->$field;
    }

    function update ($db, $id, $values = array())
    {

        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update($db, $this);
        } else {
            $this->db->update($db, $values);
            if ($values == 1) {
                $keys = array_keys($values);
                return $this->get_value($id, $keys[0]);
            }
        }
    }
}