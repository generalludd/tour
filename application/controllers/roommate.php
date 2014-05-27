<?php
defined('BASEPATH') or exit('No direct script access allowed');

// roommate.php Chris Dart Dec 28, 2013 10:08:58 PM chrisdart@cerebratorium.com
class Roommate extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("roommate_model", "roommate");
        $this->load->model("room_model","room");
        $this->load->model("hotel_model","hotel");

    }

    function view ()
    {
    }

    function view_for_tour ()
    {
        $tour_id = $this->input->get("tour_id");
        $stay = $this->input->get("stay");
        $this->load->model("payer_model", "payer");
        $data["room_types"] = $this->payer->get_room_types($tour_id);
        if ($tour_id && $stay) {
            $rooms = $this->room->get_for_tour($tour_id, $stay);
            $this->load->model("hotel_model");
            echo $this->hotel_model->get(1);
die();
            $hotel = $this->hotel->get_by_stay($tour_id, $stay);
            die();
            $data["last_stay"] = $this->hotel->get_last_stay($tour_id);
            $data["hotel"] = $hotel;
            $data["tour_id"] = $tour_id;
            $data["rooms"] = $rooms;
            $data["stay"] = $stay;
            $data["target"] = "roommate/list";
            $data["title"] = sprintf("Roommate List for Tour: %s, Stay: %s", $hotel->tour_name, $stay);
            $this->load->view("page/index", $data);
        }
    }

    function view_for_stay ()
    {
        $tour_id = $this->input->get("tour_id");
        $stay = $this->input->get("stay");
    }

    function create_room ()
    {
        $tour_id = $this->input->get("tour_id");
        $stay = $this->input->get("stay");
        if ($tour_id && $stay) {
            $last_room = $this->roommate->get_last_room($tour_id, $stay);
            $room_list = $this->roommate->get_room_numbers($tour_id, $stay);
            $data["room_number"] = get_first_missing_number($room_list, "room");
            $data["roommate_list"] = $this->get_roomless_menu($tour_id, $stay);
            $data["roommates"] = FALSE;
            $this->load->view("roommate/room", $data);
        }
    }

    function insert_row ()
    {
        $this->roommate->insert();
        $tour_id = $this->input->post("tour_id");
        $stay = $this->input->post("stay");
        $room = $this->input->post("room");
        $data["room_number"] = $room;
        $data["roommates"] = $this->roommate->get_for_room($tour_id, $stay, $room);
        $this->load->view("roommate/room", $data);
    }

    function update_value ()
    {
        $tour_id = $this->input->post("tour_id");
        $stay = $this->input->post("stay");
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
        );
        $this->phone->update($id, $values);
        print $this->input->post("value");
    }

    function delete ()
    {
        $deletion = array(
                "person_id" => $this->input->post("person_id"),
                "room" => $this->input->post("room"),
                "stay" => $this->input->post("stay"),
                "tour_id" => $this->input->post("tour_id")
        );
        $this->roommate->delete($deletion);
        $tour_id = $this->input->post("tour_id");
        $stay = $this->input->post("stay");
        $room = $this->input->post("room");
        $data["room_number"] = $room;
        $data["roommates"] = $this->roommate->get_for_room($tour_id, $stay, $room);
        $this->load->view("roommate/room", $data);
    }

    /**
     * generate a dropdown form menu of all those without rooms for the given
     * tour and stay
     */
    function get_roomless_menu ($tour_id = FALSE, $stay = FALSE)
    {
        $ajax = FALSE;
        if (! $tour_id && ! $stay) {
            $tour_id = $this->input->get("tour_id");
            $stay = $this->input->get("stay");
            $ajax = $this->input->get("ajax");
        }
        $class = FALSE;
        if ($this->input->get("class")) {
            $class = $this->input->get("class");
        }
        $roomless = $this->roommate->get_roomless($tour_id, $stay);
        $roomless_pairs = get_keyed_pairs($roomless, array(
                "id",
                "person_name"
        ), TRUE);
        if ($ajax) {
            print form_dropdown("person_id", $roomless_pairs, FALSE, sprintf("id='person_id' %s", $class ? "class='$class'" : ""));
        } else {
            return $roomless_pairs;
        }
    }
}