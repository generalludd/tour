<?php
defined('BASEPATH') or exit('No direct script access allowed');

// contact.php Chris Dart Jan 19, 2014 6:09:08 PM
// chrisdart@cerebratorium.com
class Contact_model extends CI_Model
{
    var $hotel_id;
    var $contact;
    var $position;
    var $phone;
    var $fax;
    var $email;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "hotel_id",
                "contact",
                "position",
                "phone",
                "fax",
                "email"
        );
        prepare_variables($this, $variables);
    }

    function get_all ($hotel_id)
    {
        $this->db->where("hotel_id", $hotel_id);
        $this->db->from("contact");
        $this->db->order_by("id");
        $result = $this->db->get()->result();
        return $result;
    }

    function get ($id)
    {
        $this->db->where("contact.id", $id);
        $this->db->from("contact");
        $this->db->join("hotel","contact.hotel_id=hotel.id");
        $this->db->select("contact.*,hotel.hotel_name");
        $result = $this->db->get()->row();
        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("contact", $this);
        $id = $this->db->insert_id();
        return $id;
    }

    function update ($id)
    {
        $this->db->where("id", $id);
        $this->prepare_variables();
        $this->db->update("contact", $this);
    }

    function delete($id)
    {
        $this->db->where("id",$id);
        $this->db->delete("contact");
    }
}