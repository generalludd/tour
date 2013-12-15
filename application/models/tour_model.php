<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tour_model.php Chris Dart Dec 13, 2013 7:57:28 PM chrisdart@cerebratorium.com
class Tour_model extends MY_Model
{
    var $tour_name;
    var $start_date;
    var $end_date;
    var $due_date;
    var $full_price;
    var $banquet_price;
    var $early_price;
    var $regular_price;
    var $single_room;
    var $triple_room;
    var $quad_room;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "tour_name",
                "start_date",
                "end_date",
                "due_date",
                "full_price",
                "banquet_price",
                "early_price",
                "regular_price",
                "single_room",
                "triple_room",
                "quad_room"
        );

        $dates = array(
                "start_date",
                "end_date",
                "due_date"
        );
        $money = array(
                "full_price",
                "banquet_price",
                "early_price",
                "regular_price",
                "single_room",
                "triple_room",
                "quad_room"
        );

        for ($i = 0; $i < count($variables); $i ++) {
            $my_variable = $variables[$i];

            if ($this->input->post($my_variable)) {
                $my_value = $this->input->post($my_variable);

                if (in_array($my_variable, $dates)) {
                    $my_value = format_date($my_value, "mysql");
                } else if (in_array($my_variable, $money)) {
                    $my_value = format_money($my_value, "int");
                }

                $this->$my_variable = $my_value;
            }
        }
    }

    function get ($id)
    {
        return parent::get("tour", $id, $values = array());
    }

    function update ($id, $values = array())
    {
        $this->db->where("id", $id);

        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("tour", $this);
        } else {
            $this->db->update("tour", $values);
        }
    }

    function get_all ()
    {
        return parent::get_all("tour");
    }

    function get_value ($id, $field)
    {
        $this->db->from("tour");
        $this->db->select($field);
        $result = $this->db->get()->row();
        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("tour", $this);
        return $this->db->insert_id();
    }
}