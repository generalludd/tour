<?php
defined('BASEPATH') or exit('No direct script access allowed');

// room_model.php Chris Dart Jan 9, 2014 9:46:17 PM chrisdart@cerebratorium.com
class Room_model extends CI_Controller
{
    var $tour_id;
    var $room;
    var $stay;

    function __construct ()
    {
        parent::__construct();
    }

    function get_next_room ($tour_id, $stay)
    {

    }

    function get_last_room($tour_id, $stay){

    }
}