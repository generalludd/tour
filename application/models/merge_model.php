<?php
defined('BASEPATH') or exit('No direct script access allowed');

// letter_model.php Chris Dart Mar 14, 2014 9:26:54 PM
// chrisdart@cerebratorium.com
class Merge_model extends CI_Model
{
    var $payer_id;
    var $tour_id;
    var $letter_id;
    var $sent_date;
    var $body;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "payer_id",
                "tour_id",
                "letter_id",
                "sent_date",
        );
        prepare_variables($this, $variables);
    }

    function get ($id)
    {
        $this->db->where("id", $id);
        $this->db->from("merge");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_for_payer ($payer_id, $tour_id)
    {
        $this->db->where("payer_id", $payer_id);
        $this->db->where("tour_id", $tour_id);
        $this->db->from("merge");
        $this->db->order_by("date_sent", "DESC");
        $result = $this->db->get()->result();
        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("merge", $this);
        return $this->db->insert_id();
    }

    function update ($id)
    {
        $this->prepare_variables();
        $this->db->where("id", $id);
        $this->db->update("merge", $this);
    }
}