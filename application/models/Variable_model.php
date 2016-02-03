<?php

defined('BASEPATH') or exit('No direct script access allowed');

// variable.php Chris Dart Dec 14, 2013 6:51:09 PM chrisdart@cerebratorium.com
class Variable_Model extends CI_Model
{

    var $class;

    var $name;

    var $value;

    var $type;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "class",
                "name",
                "value",
                "type"
        );

        for ($i = 0; $i < count($variables); $i ++) {
            $my_variable = $variables[$i];
            if ($this->input->post($my_variable)) {
                $this->$my_variable = $this->input->post($my_variable);
            }
        }

        $this->rec_modified = mysql_timestamp();
        $this->rec_modifier = $this->session->userdata('user_id');
    }

    function insert ()
    {
        $this->prepare_variables();
        $id = $this->get_id($this->class, $this->name, $this->value);
        if (! $id) {
            $this->db->insert("variable", $this);
            $id = $this->db->last_insert_id();
        }
        return $id;
    }

    function update ($id)
    {
        $this->db->where("id", $id);
        $this->prepare_variables();
        $this->db->update("variable", $this);
    }

    function get_id ($class, $name, $value)
    {
        $this->db->where("class", $class);
        $this->db->where("name", $name);
        $this->db->where("value", $value);
        $this->db->from("variable");
        $output = $this->db->get()->row();
        return $output;
    }

    function get_classes ()
    {
        $this->db->from("variable");
        $this->db->select("`class` as 'name',`class` as 'value'");
        $this->db->distinct("class");
        $this->db->order_by("class", "ASC");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_pairs ($class, $order = array())
    {
        $this->db->where('class', $class);
        $this->db->select('name');
        $this->db->select('value');
        $direction = "ASC";
        $order_field = "value";

        if (! empty($order)) {
            if (in_array("direction", $order)) {
                $direction = $order['direction'];
            }
            if (in_array("field", $order)) {
                $order_field = $order['field'];
            }
        }
        $this->db->order_by("id");
        $this->db->order_by($order_field, $direction);
        $this->db->from('variable');
        $result = $this->db->get()->result();
        return $result;
    }

    function get_name ($class, $value)
    {
        $this->db->from("variable");
        $this->db->where("class", $class);
        $this->db->where("value", $value);
        $this->db->select("name");
        $result = $this->db->get()->row()->name;
        return $result;
    }
}