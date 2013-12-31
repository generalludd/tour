<?php defined('BASEPATH') OR exit('No direct script access allowed');

// roommate_model.php Chris Dart Dec 28, 2013 10:09:30 PM chrisdart@cerebratorium.com

class Roommate_Model extends CI_Model
{

    var $tour_id;
    var $person_id;
    var $hotel_id;
    var $stay;

    function __construct()
    {
        parent::__construct();
    }


    function get_roomless($tour_id,$stay){
        $this->db->select("person.id");
        $this->db->select("CONCAT(`person`.`first_name`,' ', `person`.`last_name`) as `person_name`", FALSE);
        $this->db->from("person");
        $this->db->join("tourist","person.id = tourist.person_id");
        $this->db->join("roommate","roommate.person_id = tourist.person_id","LEFT OUTER");
        $this->db->where("tourist.tour_id",$tour_id);
        $this->db->where("roommate.person_id IS NULL OR roommate.stay != $stay",NULL, FALSE);
       // $this->db->where("(roommate.stay !=$stay)",NULL,FALSE);
        $this->db->order_by("person.last_name","DESC");
      //  $this->db->order_by("person.id");
        $result = $this->db->get()->result();
        return $result;
    }
}