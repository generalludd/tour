<?php  defined('BASEPATH') OR exit('No direct script access allowed');
class Backup extends MY_Controller{
	
	
	function __construct(){
		parent::__construct();
		$this->load->model("log_model","log");
	}
	
	function index(){
		// Load the DB utility class
		$this->load->dbutil();
		$dbs = $this->dbutil->list_databases();
		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();
		$filename = sprintf("backup-%s.sql.gz",date("Y-m-d-H-i-s"));
		$path = sprintf("/tmp/");
		$temp_file = $path . $filename;
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		write_file($temp_file, $backup);
		$this->log->log("backup","success");
		$this->session->set_flashdata("notice","Move the file backup file from your downloads folder to your backups folder.");
		
		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
	    force_download($filename, $backup);
		//*/
		redirect("person");
		
	}
	
	
}