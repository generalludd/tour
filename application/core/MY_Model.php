<?php
defined('BASEPATH') or exit('No direct script access allowed');

// My_Model.php Chris Dart Dec 13, 2013 8:04:54 PM chrisdart@cerebratorium.com
class MY_Model extends CI_Model
{

    function __construct ()
    {
        parent::__construct();
    }

    function _get ($db, $id, $fields = FALSE)
    {
        $this->db->from($db);
        $this->db->where("id", $id);
        if ($fields) {
            $this->db->select($fields);
        }
        return $this->db->get()->row();
    }

    function _get_value ($db, $id, $field)
    {
        $row = $this->get($db, $id, $field);
        return $row->$field;
    }

    function _get_all ($db)
    {
        $this->db->from($db);
        return $this->db->get()->result();
    }
    
    function _log ($element = "alert")
    {
    	$last_query = $this->db->last_query();
    	//$this->load->model("user_preferences_model","user_prefs");
    
    	//if ($this->user_prefs->get($this->ion_auth->user()->row()->id,"dev") == 1) {
    		$this->session->set_flashdata($element, $last_query);
    	//}
    }
}