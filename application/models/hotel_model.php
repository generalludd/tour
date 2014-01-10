<?php
defined('BASEPATH') or exit('No direct script access allowed');

// hotel_model.php Chris Dart Dec 28, 2013 10:09:58 PM
// chrisdart@cerebratorium.com
class Hotel_Model extends CI_Model
{
    var $hotel_name;
    var $tour_id;
    var $stay;
    var $arrival_date;
    var $arrival_time;
    var $departure_date;
    var $departure_time;
    var $phone;
    var $fax;
    var $url;
    var $email;
    var $contact_name;
    var $address;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "hotel_name",
                "tour_id",
                "stay",
                "arrival_date",
                "arrival_time",
                "departure_date",
                "departure_time",
                "phone",
                "fax",
                "url",
                "email",
                "contact_name",
                "address"
        );
        prepare_variables($this, $variables);
    }

    function get ($hotel_id, $fields = "hotel.*")
    {
        $this->db->from("hotel");
        $this->db->where("hotel.id", $hotel_id);
        $this->db->select($fields);
        $this->db->join("tour", "hotel.tour_id=tour.id");
        $this->db->select("tour.tour_name");
        return $this->db->get()->row();
    }

    function get_by_stay ($tour_id, $stay, $fields = "hotel.*")
    {
        $this->db->from("hotel");
        $this->db->where("tour_id", $tour_id);
        $this->db->where("stay", $stay);
        $this->db->join("tour", "tour.id=hotel.tour_id");
        $this->db->select("tour.tour_name");
        $this->db->select($fields);
        $result = $this->db->get()->row();
        return $result;
    }

    function get_for_tour ($tour_id)
    {
        $this->db->from("hotel");
        $this->db->where("tour_id", $tour_id);
        $this->db->order_by("arrival_date", "ASC");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_last_stay ($tour_id)
    {
        $this->db->from("hotel");
        $this->db->where("tour_id", $tour_id);
        $this->db->order_by("stay","DESC");
        $this->db->limit(1);
        $this->db->select("stay");
        $result = $this->db->get()->row();
        return $result->stay;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("hotel", $this);
        $id = $this->db->insert_id();
        return $id;
    }

    function update ($id, $values = array())
    {
        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("hotel", $this);
        } else {
            $this->db->update("hotel", $values);
        }
    }
}