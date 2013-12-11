<?php
defined('BASEPATH') or exit('No direct script access allowed');

// person.php Chris Dart Dec 10, 2013 8:15:47 PM chrisdart@cerebratorium.com
class Person_model extends CI_Model
{
    var $first_name;
    var $last_name;
    var $email;
    var $shirt_size;
    var $salutation;

    function __construct ()
    {

        parent::__construct();
    }

    function prepare_variables ()
    {

        $variables = array(
                "first_name",
                "last_name",
                "email",
                "shirt_size",
                "salutation"
        );
        prepare_variables($this, $variables);
    }

    function get($id)
    {

    }


}