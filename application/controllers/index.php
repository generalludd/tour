<?php

defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

// index.php Chris Dart Dec 10, 2013 8:14:38 PM chrisdart@cerebratorium.com
class Index extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}



	function index()
	{
	   redirect("person");
	}


}