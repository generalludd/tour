<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer_model.php Chris Dart Dec 14, 2013 5:47:05 PM
// chrisdart@cerebratorium.com
class Payer_model extends CI_Model
{
    var $payer_id;
    var $tour_id;
    var $payment_type;
    var $room_size;
    var $discount;
    var $amt_paid;
    var $notes;

    function __construct ()
    {
        parent::__construct();
    }

    function get_for_tour ($payer_id, $tour_id)
    {
        $this->db->from("payer");
        $this->db->join("tour", "payer.tour_id=tour.id");
        $this->db->join("person", "payer.payer_id=person.id");
        $this->db->where("payer.payer_id", $payer_id);
        $this->db->where("payer.tour_id", $tour_id);
        $this->db->select("tour.tour_name,tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price,tour.single_room, tour.triple_room, tour.quad_room");
        $this->db->select("person.first_name, person.last_name");
        $this->db->select("payer.*");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_tourist_count ($payer_id, $tour_id)
    {
        $this->db->where("payer.payer_id", $payer_id);
        $this->db->where("payer.tour_id", $tour_id);
        $this->db->from("payer");
        $this->db->join("tourist", "payer.payer_id = tourist.payer_id");
        $result = $this->db->count_all_results();
        return $result;
    }


}