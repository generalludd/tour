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

    function get ($id, $fields = FALSE)
    {
        $this->db->from("tour");
        $this->db->where("id", $id);
        if ($fields) {
            $this->db->select($fields);
        }
        return $this->db->get()->row();
    }

    function get_all ($current_only = FALSE, $fields = "*")
    {
        $this->db->from("tour");
        $this->db->select($fields);
        $this->db->order_by("tour.start_date", "DESC");
        if ($current_only) {
            $this->db->where("tour.start_date > CURDATE()", NULL, FALSE);
        }
        return $this->db->get()->result();
    }

    function get_value ($id, $field)
    {
        // return parent::get_value("tour", $id, $field);
        $this->db->from("tour");
        $this->db->where("id", $id);
        $this->db->select($field);
        $result = $this->db->get()->row();
        return $result->$field;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("tour", $this);
        return $this->db->insert_id();
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
}