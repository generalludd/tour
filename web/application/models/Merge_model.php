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
    var $note;
    var $salutation;



    function prepare_variables ()
    {
        $variables = array(
                "payer_id",
                "tour_id",
                "letter_id",
                "sent_date"
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

    function get_for_payer ($payer_id, $letter_id)
    {
        $this->db->where("payer_id", $payer_id);
        $this->db->where("letter_id", $letter_id);
        $this->db->from("merge");
        $this->db->order_by("sent_date", "DESC");
        $result = $this->db->get()->row();
        return $result;
    }

    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("merge", $this);
        return $this->db->insert_id();
    }

    function quick_insert ($payer_id, $letter_id)
    {
        $merge = $this->get_for_payer($payer_id, $letter_id);
        if ($merge) {
            $result = $merge;
        } else {
            $this->payer_id = $payer_id;
            $this->letter_id = $letter_id;
            $this->sent_date = date("Y-m-d");
            $query = "REPLACE INTO merge SET sent_date = '$this->sent_date', payer_id = '$this->payer_id', letter_id= '$this->letter_id'";
            $this->db->query($query);
            $id = $this->db->insert_id();
            $result = $this->get($id);
        }
        return $result->id;
    }

    function update ($id, $values = array())
    {
        $this->db->where("id", $id);

        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("merge", $this);
        } else {
            $this->db->update("merge", $values);
        }
    }
}
