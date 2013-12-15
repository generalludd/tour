<?php
defined('BASEPATH') or exit('No direct script access allowed');

// data_model.php Chris Dart Dec 13, 2013 9:36:47 PM chrisdart@cerebratorium.com
class Data_model extends CI_Model
{

    function __construct ()
    {

        parent::__construct();
    }

    function get_fields ($table)
    {

        return $this->db->field_data($table);
    }
}