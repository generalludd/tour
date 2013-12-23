<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer_model.php Chris Dart Dec 14, 2013 5:47:05 PM
// chrisdart@cerebratorium.com
class Payer_model extends CI_Model
{
    var $payment_type;
    var $room_size;
    var $discount;
    var $amt_paid;
    var $notes;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "payment_type",
                "room_size",
                "discount",
                "amt_paid",
                "notes"
        );
        for ($i = 0; $i < count($variables); $i ++) {
            $my_variable = $variables[$i];
            if ($this->input->post($my_variable)) {
                if ($my_variable == "discount" || $my_variable == "amt_paid") {
                    $this->$my_variable = format_money($this->input->post($my_variable), "int");
                }
                $this->$my_variable = $this->input->post($my_variable);
            }
        }
    }

    function get_for_tour ($payer_id, $tour_id)
    {
        $this->db->from("payer");
        $this->db->join("tour", "payer.tour_id=tour.id");
        $this->db->join("person", "payer.payer_id=person.id");
        $this->db->where("payer.payer_id", $payer_id);
        $this->db->where("payer.tour_id", $tour_id);
        $this->db->select(
                "tour.tour_name,tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price,tour.single_room, tour.triple_room, tour.quad_room");
        $this->db->select("person.first_name, person.last_name");
        $this->db->select("payer.*");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_tourist_count ($payer_id, $tour_id)
    {
        $this->db->where("tourist.payer_id", $payer_id);
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->from("tourist");
        $result = $this->db->count_all_results();
        return $result;
    }

    function get_payers ($tour_id)
    {
        $this->db->where("payer.tour_id", $tour_id);
        $this->db->where("`tour`.`id` = `payer`.`tour_id`", NULL, FALSE);
        $this->db->join("person","person.id = payer.payer_id");
        $this->db->from("payer,tour");
        $this->db->select("tour.id, tour.tour_name, payer.payer_id, person.first_name, person.last_name");
        $result = $this->db->get()->result();
        return $result;
    }

    function update ($payer_id, $tour_id)
    {
        $this->db->where("tour_id", $tour_id);
        $this->db->where("payer_id", $payer_id);
        $this->prepare_variables();
        $this->db->update("payer", $this);
    }

    function insert ($payer_id, $tour_id)
    {
        $insert_array = array(
                "payer_id" => $payer_id,
                "tour_id" => $tour_id
        );
        $query = "INSERT IGNORE INTO `payer` (`payer_id`, `tour_id`) VALUES('$payer_id', '$tour_id');";
        $this->db->query($query);
    }
}