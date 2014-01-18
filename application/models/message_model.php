<?php
defined('BASEPATH') or exit('No direct script access allowed');

// message_model.php Chris Dart Jan 13, 2014 9:06:58 PM
// chrisdart@cerebratorium.com
class Message_model extends CI_Model
{
    var $tour_id;
    var $subject;
    var $body;
    var $message_date;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "tour_id",
                "subject",
                "body",
                "message_date"
        );
        prepare_variables($this, $variables);
    }

    function get ($id)
    {
        $this->db->from("message");
        $this->db->where("id", $id);
        $result = $this->db->get()->row();
        return $result;
    }



    function insert ()
    {
        $this->prepare_variables();
        $this->db->insert("message", $this);
        $id = $this->db->insert_id();
        return $id;
    }
}