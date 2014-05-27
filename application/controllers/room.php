<?php
defined('BASEPATH') or exit('No direct script access allowed');

// room.php Chris Dart May 23, 2014 10:11:59 PM chrisdart@cerebratorium.com
class Room extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("room_model", "room");
        $this->load->model("roommates_model", "roommates");
        $this->load->model("variable_model", "variable");
    }

    function create ()
    {
        $tour_id = $this->input->get("tour_id");
        $stay = $this->input->get("stay");
        if ($tour_id && $stay) {
            $data["room_id"] = $this->room->create($tour_id, $stay);
            $data["tour_id"] = $tour_id;
            $data["stay"] = $stay;
            $data["sizes"] = get_keyed_pairs(
                    $this->variable->get_pairs("room_type",
                            array(
                                    "direction" => "ASC",
                                    "field" => "value"
                            )),
                    array(
                            "value",
                            "name"
                    ));
            $data["action"] = "update";
            $data["room"] = NULL;
            $this->load->view("room/edit", $data);
        }
    }

    function insert ()
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
}