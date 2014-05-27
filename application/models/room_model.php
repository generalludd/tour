<?php
defined('BASEPATH') or exit('No direct script access allowed');

// room_model.php Chris Dart Jan 9, 2014 9:46:17 PM chrisdart@cerebratorium.com
class Room_Model extends CI_Controller
{

    var $tour_id;

    var $room_id;

    var $size;

    var $stay;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "tour_id",
                "size",
                "stay",
                "room_id"
        );

        for ($i = 0; $i < count($variables); $i ++) {
            $my_variable = $variables[$i];
            if ($this->input->post($my_variable)) {
                $this->$my_variable = urldecode(
                        $this->input->post($my_variable));
            }
        }
    }

    function create ($tour_id, $stay, $size = "Double")
    {
        $room_list = $this->get_room_numbers($tour_id, $stay);
        $room_id = get_first_missing_number($room_list, "room_id");
        $data = array(
                "tour_id" => $tour_id,
                "stay" => $stay,
                "room_id" => $room_id,
                "size" => $size
        );

        $this->db->insert("room", $data);
        $id = $this->db->insert_id();
        return $this->get($id);
    }

    function update ($id, $values = array())
    {
        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("room", $this);
        } else {
            $this->db->update("room", $values);
            if ($values == 1) {
                $keys = array_keys($values);
                return $this->get_value($id, $keys[0]);
            }
        }
    }

    function get ($id)
    {
        $this->db->where("id", $id);
        $this->db->from("room");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_for_tour ($tour_id, $stay)
    {
        $this->db->from("room");
        // $this->db->join("variable", "room.size = variable.value AND
        // variable.class = 'room_type'");
        $this->db->order_by("room.room_id");
        $this->db->where("room.tour_id", $tour_id);
        $this->db->where("room.stay", $stay);
        $result = $this->db->get()->result();
        return $result;
    }

    function get_next_room ($tour_id, $stay)
    {}

    function get_room_numbers ($tour_id, $stay)
    {
        $this->db->from("room");
        $this->db->where("tour_id", $tour_id);
        $this->db->where("stay", $stay);
        $this->db->order_by("room_id", "ASC");
        $this->db->group_by("room_id");
        $this->db->select("room_id");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_last_room ($tour_id, $stay)
    {
        $this->db->where("tour_id", $tour_id);
        $this->db->where("stay", $stay);
        $this->db->select("room_id");
        $this->db->group_by("room_id");
        $this->db->order_by("room_id", "DESC");
        $this->db->limit(1);
        $this->db->from("room");
        $result = $this->db->get()->row();
        $output = 0;
        if ($result) {
            $output = $result->room;
        }
        return $output;
    }

    function get_room_count ($tour_id, $stay)
    {
        // select count(room.size), room.size from room where tour_id = 33 and
        // stay = 1 group by room.size
        $this->db->from("room");
        $this->db->where("tour_id", $tour_id);
        $this->db->where("stay", $stay);
        $this->db->select("COUNT(`room`.`size`) as `room_count`", FALSE);
        $this->db->select("room.size");
        $this->db->group_by("room.size");
        $result = $this->db->get()->result();
        return $result;
    }
}