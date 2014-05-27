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
                "room_id"
        );
        prepare_variables($this, $variables);
    }

    function get_for_tour ($tour_id, $stay)
    {
        $this->db->from("roommate");
        $this->db->join("person", "roommate.person_id = person.id");
        $this->db->where("roommate.tour_id", $tour_id);
        $this->db->where("roommate.stay", $stay);
        $this->db->select("room_id");
        $this->db->order_by("roommate.room_id");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_room_count ($tour_id)
    {
        $this->db->from("roommate");
        $this->db->where("tour_id", $tour_id);
        $this->db->select("DISTINCT(`room_id`) AS room_count", FALSE);
        $this->db->group_by("room_id");
        $this->db->get()->result();
        $result = $this->db->count_all_results();
        return $result;
    }

    function get_for_room ($tour_id, $stay, $room_id)
    {
        $this->db->from("roommate");
        $this->db->where("tour_id", $tour_id);
        $this->db->where("room_id", $room_id);
        $this->db->where("stay", $stay);
        $this->db->join("person", "roommate.person_id=person.id");
        $this->db->select("roommate.room_id, roommate.tour_id, roommate.person_id");
        $this->db->select("CONCAT(person.first_name,' ',person.last_name) as person_name", false);

        $result = $this->db->get()->result();
        return $result;
    }

    function get_roomless ($tour_id, $stay)
    {
        $this->db->select("person.id, concat(person.first_name,' ', person.last_name) as person_name", FALSE);
        $this->db->from("tourist");
        $this->db->join("hotel", "tourist.tour_id= hotel.tour_id", "left");
        $this->db->join("roommate", "tourist.person_id = roommate.person_id AND tourist.tour_id = roommate.tour_id AND hotel.stay = roommate.stay", "left");
        $this->db->join("person", "tourist.person_id=person.id");
        $this->db->join("payer","tourist.payer_id = payer.payer_id AND tourist.tour_id = payer.tour_id");
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->where("hotel.stay", $stay);
        $this->db->where("payer.is_cancelled != 1",NULL,FALSE);
        $this->db->where("`roommate`.`person_id` IS NULL", NULL, FALSE);
        $this->db->order_by("person.first_name", "DESC");
        $this->db->order_by("person.address_id", "ASC");
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

    function get_room_numbers ($tour_id, $stay)
    {
        $this->db->from("roommate");
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
        $this->db->from("roommate");
        $result = $this->db->get()->row();
        $output = 0;
        if ($result) {
            $output = $result->room_id;
        }
        return $output;
    }

    function delete_payer ($payer_id, $tour_id)
    {
        $query = sprintf(
                "delete r.* from roommate r join tourist t on r.person_id = t.person_id and t.tour_id = r.tour_id WHERE t.payer_id = %s and r.tour_id = %s",
                $payer_id, $tour_id);
        $this->db->query($query);
    }

    function delete_tourist ($person_id, $tour_id)
    {
        $this->db->where("person_id", $person_id);
        $this->db->where("tour_id", $tour_id);
        $this->db->delete("roommate");
    }
}