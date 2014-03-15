<?php
defined('BASEPATH') or exit('No direct script access allowed');

// letter_model.php Chris Dart Mar 14, 2014 9:21:40 PM
// chrisdart@cerebratorium.com
class Letter_model extends CI_Model
{
    var $title;
    var $body;
    var $cancellation;
    var $tour_id;
    var $creation_date;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "title",
                "body",
                "cancellation",
                "tour_id",
                "creation_date"
        );
        prepare_variables($this, $variables);
    }

    function get ($id)
    {
        $this->db->where("id", $id);
        $this->db->from("letter");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_for_tour ($tour_id)
    {
        $this->db->where("tour_id", $tour_id);
        $this->db->from("letter");
        $result = $this->db->get()->result();
        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        print $this->creation_date;
        $this->db->insert("letter", $this);
        $id = $this->db->insert_id();
        return $id;
    }

    function update ($id)
    {
        $this->prepare_variables();
        $this->db->where("id", $id);
        $this->db->update("letter", $this);
    }

    function delete($id){
        $this->db->where("id", $id);
        $this->db->delete("letter");
    }
}