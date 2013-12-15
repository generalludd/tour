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

    function get_all($db)
    {
        $this->db->from($db);
        $this->db->order_by("start_date");
        return $this->db->get()->result();
    }

}