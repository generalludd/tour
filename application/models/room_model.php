<?php
defined('BASEPATH') or exit('No direct script access allowed');

// room_model.php Chris Dart Jan 9, 2014 9:46:17 PM chrisdart@cerebratorium.com
class Room_model extends CI_Controller
{

    var $tour_id;

    var $room_id;

    var $size;

    var $stay;

    function __construct ()
    {
        parent::__construct();
    }

    function create ($tour_id, $stay)
    {
        $room_list = $this->get_room_numbers($tour_id, $stay);
        $room_id = get_first_missing_number($room_list, "room_id");
        $this->db->insert("room",
                array(
                        "tour_id" => $tour_id,
                        "stay" => $stay,
                        "room_id" => $room_id
                ));
        return $room_id;
    }

    function insert ($data)
    {
        $this->db->insert("room", $data);
        return $this->db->insert_id();
    }

    function get_for_tour ($tour_id, $stay)
    {
        $this->db->from("room");
        $this->db->join("roommate",
                "room.room_id=room.room_id AND room.tour_id = roommate.tour_id AND room.stay = roommate.stay");
        $this->db->join("person", "roommate.person_id = person.id");
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
}