<?php
defined('BASEPATH') or exit('No direct script access allowed');

// My_Model.php Chris Dart Dec 13, 2013 8:04:54 PM chrisdart@cerebratorium.com
class MY_Model extends CI_Model
{

    function __construct ()
    {
        parent::__construct();
    }

    function get ($db, $id, $fields = FALSE)
    {
        $this->db->from($db);
        $this->db->where("id", $id);
        if ($fields) {
            $this->db->select($fields);
        }
        return $this->db->get()->row();
    }

    function get_value ($db, $id, $field)
    {
        $row = $this->get($db, $id, $field);
        return $row->$field;
    }

    function get_all ($db)
    {
        $this->db->from($db);
        return $this->db->get()->result();
    }
}