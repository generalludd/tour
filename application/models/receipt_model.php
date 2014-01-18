<?php
defined('BASEPATH') or exit('No direct script access allowed');

// receipt_model.php Chris Dart Jan 14, 2014 8:53:42 PM
// chrisdart@cerebratorium.com
class receipt_model extends CI_Model
{
    var $message_id;
    var $person_id;
    var $status;
    var $body;
    var $receipt_date;

    function __construct ()
    {
        parent::__construct();
    }

    function prepare_variables ()
    {
        $variables = array(
                "message_id",
                "person_id",
                "status",
                "body"
        );
        prepare_variables($this, $variables);
        $this->receipt_date = mysql_timestamp();
    }

    function get ($id)
    {
        $this->db->where("receipt.id", $id);
        $this->db->from("receipt");
        $this->db->join("person", "person.id=receipt.person_id");
        $this->db->join("message", "message.id=receipt.message_id");
        $this->db->select("receipt.*");
        $this->db->select("person.first_name, person.last_name, person.email, person.shirt_size");
        $this->db->select("message.subject,message.tour_id, message.message_date");
        $result = $this->db->get()->row();
        return $result;
    }

    function get_all ($id)
    {
        $this->db->from("receipt");
        $this->db->join("person", "person.id=receipt.person_id");
        $this->db->join("message", "message.id=receipt.message_id");
        $this->db->where("message.id", $id);
        $this->db->select("receipt.*");
        $this->db->select("person.first_name, person.last_name, person.email, person.shirt_size");
        $this->db->select("message.subject,message.tour_id");
        $result = $this->db->get()->result();
        return $result;
    }

    function insert ($message_id, $person_id, $status, $body)
    {
        /*
         * $this->message_id = $message_id; $this->person_id = $person_id;
         * $this->status = $status; $this->body = $body;
         */
        $query = "replace into `receipt` (`message_id`, `person_id`, `status`, `body`) VALUES('$message_id','$person_id','$status','$body')";
        $this->db->query($query);
        // $this->db->insert("receipt", $this);
        $id = $this->db->insert_id();
        return $id;
    }

    function update ($id)
    {
        $this->db->where("id", $id);
        $this->prepare_variables();
        $this->db->update("receipt",$this);
    }

    function update_value ($id, $fields = array())
    {
        if (! empty($fields)) {
            $this->db->where("id", $id);
            $this->db->update("receipt", $fields);
        }
    }
}