<?php
defined('BASEPATH') or exit('No direct script access allowed');

// roommates_model.php Chris Dart May 23, 2014 8:58:04 PM
// chrisdart@cerebratorium.com
class Roommates_Model extends CI_Model
{

    var $room_id;

    var $tour_id;

    var $person_id;

    var $size;

    function __construct ()
    {
        parent::__construct();
    }

    function insert ($data)
    {
        $this->db->insert("roommates", $data);
    }

    function get_roommates ($person_id, $tour_id)
    {
        // this could be done with a single query, but for now...
        $room_id = $this->get_room($person_id, $tour_id)->room_id;
        $this->db->from("roommates");
        $this->db->where("roommates.room_id", $room_id);
        $this->db->where("roommates.tour_id", $tour_id);
        $this->db->join("person", "roommates.person_id=person.id");
        $this->db->select("roommates.*");
        $this->db->select("person.first_name, person.last_name");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_room ($person_id, $tour_id)
    {
        $this->db->from("roommates");
        $this->db->join("room", "roommates.room_id = room.id");
        $this->db->where("roommates.person_id", $person_id);
        $this->db->where("roommates.tour_id", $tour_id);
        $result = $this->db->get()->row();
        return $result;
    }

    function get_roomless ($tour_id)
    {
        $this->db->select(
                "person.id, concat(person.first_name,' ', person.last_name) as person_name",
                FALSE);
        $this->db->from("tourist");
        $this->db->join("roommates",
                "tourist.person_id = roommates.person_id AND tourist.tour_id = roommates.tour_id",
                "left");
        $this->db->join("person", "tourist.person_id=person.id");
        $this->db->join("payer",
                "tourist.payer_id = payer.payer_id AND tourist.tour_id = payer.tour_id");
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->where("payer.is_cancelled != 1", NULL, FALSE);
        $this->db->where("`roommates`.`person_id` IS NULL", NULL, FALSE);
        $this->db->order_by("person.first_name", "DESC");
        $this->db->order_by("person.address_id", "ASC");
        $result = $this->db->get()->result();
        return $result;
    }
}