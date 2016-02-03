<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Logging_Model extends MY_Model{
	
	var $event_type;
	var $event;
	var $timestamp;
	
	
	function get_latest($event_type){
		$this->db->where("event_type",$event_type);
		$this->db->order_by("timestamp","DESC");
		$this->db->limit(1);
		$this->db->select("UNIX_TIMESTAMP(`timestamp`) as `unix_time`",FALSE);
		$this->db->select("log.*");
		$this->db->from("log");
		$result = $this->db->get()->row();
		
		return $result;
	}
	
	
	function log($event_type,$event){
		$this->event_type = $event_type;
		$this->event = $event;
		$this->timestamp = date("Y-m-d H:i:s");
		$this->db->insert("log",$this);
		
	}
	
}