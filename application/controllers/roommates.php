<?php
defined('BASEPATH') or exit('No direct script access allowed');

// roommates.php Chris Dart May 23, 2014 10:03:39 PM chrisdart@cerebratorium.com
class Roommates extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("roommates_model", "roommates");
        $this->load->model("tourist_model", "tourist");
        $this->load->model("room_model", "room");
    }

    function create ($person_id, $tour_id)
    {
       $data["tourists"] = $this->tourist->get_for_payer($person_id, $tour_id);

       $this->load->view("rooming/create",$data);

    }

    function insert ()
    {
        $person_id = $this->input->post("person_id");
        $room_id = $this->input->post("room_id");
        $this->roommates->insert(
                array(
                        "person_id" => $person_id,
                        "room_id" => $room_id
                ));
    }

    function insert_room ()
    {
        $person_id = $this->input->post("person_id");
        $tour_id = $this->input->post("tour_id");
        // does the person have a room? Don't allow this if they do.
        $tourists = $this->tourist->get_for_payer($person_id, $tour_id);
        $room_size = get_room_size(
                $this->payer->get_value($person_id, $tour_id, "room_size")->room_size);
        $room_id = $this->room->insert(
                array(
                        "tour_id" => $tour_id,
                        "size" => $room_size
                ));
        foreach ($tourists as $tourist) {
            $data = array(
                    "room_id" => $room_id,
                    "person_id" => $tourist->person_id,
                    "tour_id" => $tour_id
            );
            $this->roommates->insert($data);
        }
    }

    function get_roomless_menu ($tour_id)
    {
        $ajax = FALSE;
        if (! $tour_id) {
            $tour_id = $this->input->get("tour_id");
            $ajax = $this->input->get("ajax");
        }
        $class = FALSE;
        if ($this->input->get("class")) {
            $class = $this->input->get("class");
        }
        $roomless = $this->roommates->get_roomless($tour_id);
        print $this->db->last_query();
        $roomless_pairs = get_keyed_pairs($roomless,
                array(
                        "id",
                        "person_name"
                ), TRUE);
        if ($ajax) {
            print
                    form_dropdown("person_id", $roomless_pairs, FALSE,
                            sprintf("id='person_id' %s",
                                    $class ? "class='$class'" : ""));
        }
        return $roomless_pairs;
    }
}