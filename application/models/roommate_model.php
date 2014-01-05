<?php
defined('BASEPATH') or exit('No direct script access allowed');

// roommate_model.php Chris Dart Dec 28, 2013 10:09:30 PM
// chrisdart@cerebratorium.com
class Roommate_Model extends CI_Model
{
    var $tour_id;
    var $person_id;
    var $stay;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "tour_id",
                "person_id",
                "stay",
                "room"
        );
        prepare_variables($this, $variables);
    }

    function get_for_tour ($tour_id, $stay)
    {
        $this->db->from("roommate");
        $this->db->join("person", "roommate.person_id = person.id");
        $this->db->where("roommate.tour_id", $tour_id);
        $this->db->where("roommate.stay", $stay);
        $this->db->select("room");
        $this->db->order_by("roommate.room");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_room_count ($tour_id)
    {
        $this->db->from("roommate");
        $this->db->where("tour_id", $tour_id);
        $this->db->select("DISTINCT(`room`) AS room_count", FALSE);
        $this->db->group_by("room");
        $this->db->get()->result();
        $result = $this->db->count_all_results();
        return $result;
    }

    function get_for_room ($tour_id, $stay, $room)
    {
        $this->db->from("roommate");
        $this->db->where("tour_id", $tour_id);
        $this->db->where("room", $room);
        $this->db->where("stay", $stay);
        $this->db->join("person", "roommate.person_id=person.id");
        $this->db->select("roommate.room, roommate.tour_id, roommate.person_id");
        $this->db->select("CONCAT(person.first_name,' ',person.last_name) as person_name", false);

        $result = $this->db->get()->result();
        return $result;
    }

    function get_roomless ($tour_id, $stay)
    {
        $this->db->select("person.id");
        $this->db->select("CONCAT(`person`.`first_name`,' ', `person`.`last_name`) as `person_name`", FALSE);
        $this->db->from("person");
        $this->db->join("tourist", "person.id = tourist.person_id");
        $this->db->join("roommate", "roommate.person_id = tourist.person_id", "LEFT OUTER");
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->where("roommate.person_id IS NULL OR roommate.stay != $stay", NULL, FALSE);
        $this->db->order_by("person.last_name", "DESC");
        $result = $this->db->get()->result();
        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("roommate", $this);
    }

    function delete ($deletion)
    {
        $this->db->delete("roommate", $deletion);
    }

    function get_last_room ($tour_id, $stay)
    {
        $this->db->where("tour_id", $tour_id);
        $this->db->where("stay", $stay);
        $this->db->select("room");
        $this->db->group_by("room");
        $this->db->order_by("room", "DESC");
        $this->db->limit(1);
        $this->db->from("roommate");
        $result = $this->db->get()->row();
        $output = 0;
        if ($result) {
            $output = $result->room;
        }
        return $output;
    }
}