<?php
defined('BASEPATH') or exit('No direct script access allowed');

// roommate.php Chris Dart Dec 28, 2013 10:08:58 PM chrisdart@cerebratorium.com
class Roommate extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("roommate_model", "roommate");
    }

    function view ()
    {
    }

    function view_for_tour ()
    {
        $tour_id = $this->input->get("tour_id");
    }

    function view_for_stay ()
    {
        $tour_id = $this->input->get("tour_id");
        $stay = $this->input->get("stay");
    }



    /**
     * generate a dropdown form menu of all those without rooms for the given
     * tour and stay
     */
    function get_roomless_menu ()
    {
        $tour_id = $this->input->get("tour_id");
        $stay = $this->input->get("stay");
        $class = FALSE;
        if ($this->input->get("class")) {
            $class = $this->input->get("class");
        }
        $roomless = $this->roommate->get_roomless($tour_id, $stay);
        $roomless_pairs = get_keyed_pairs($roomless, array(
                "id",
                "person_name"
        ));
        print form_dropdown("person_id", $roomless_pairs, FALSE, sprintf("id='person_id' %s", $class ? "class='$class'" : ""));
    }
}