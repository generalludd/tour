<?php  defined('BASEPATH') OR exit('No direct script access allowed');
class Backup extends MY_Controller{
	
	
	function __construct(){
		parent::__construct();
	}
	
	function index(){
		$this->backup();
	}
	
	function backup(){
		// Load the DB utility class
		$this->load->dbutil();
		
		$dbs = $this->dbutil->list_databases();
		// Backup your entire database and assign it to a variable
		$this->dbutil->backup();
		/*
		// Load the file helper and write the file to your server
		$this->load->helper('file');
		write_file('/tmp/mybackup.gz', $backup);
		
		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download('mybackup.gz', $backup);
		*/
	}
	
}