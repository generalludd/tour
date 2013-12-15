<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tourist_model.php Chris Dart Dec 14, 2013 4:00:34 PM
// chrisdart@cerebratorium.com
class Tourist_model extends CI_Model
{
    var $tour_id;
    var $payer_id;
    var $person_id;

    function __construct ()
    {
        parent::__construct();
    }

    function get_by_tour ($tour_id)
    {
        $this->db->select("tourist.tour_id, tourist.person_id");
        $this->db->select("person.first_name, person.last_name, person.shirt_size,person.email");
        $this->db->select(
                "tour.tour_name, tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price, tour.single_room, tour.triple_room, tour.quad_room");
        $this->db->select("payer.payer_id,payer.payment_type, payer.room_size, payer.discount, payer.amt_paid, payer.notes");
        $this->db->from("tourist");
        $this->db->join("person", "person.id = tourist.person_id");
        $this->db->join("tour", "tour.id = tourist.tour_id");
        $this->db->join("payer", "payer.tour_id = tourist.tour_id");
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->where("`payer`.`payer_id` = `tourist`.`payer_id`", NULL, FALSE);
        $this->db->order_by("tourist.person_id,person.last_name, person.first_name");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_by_payer ($payer_id, $tour_id)
    {
        $this->db->from("tourist");
        $this->db->join("person", "tourist.person_id = person.id");
        $this->db->where("tourist.payer_id", $payer_id);
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->select("person.first_name, person.last_name");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_by_tourist ($person_id)
    {
    }
}