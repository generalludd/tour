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
            $data["room"] = $this->room->create($tour_id, $stay);
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

    function edit_value ()
    {
        $data["name"] = $this->input->get("field");

        $value = $this->input->get("value");
        $data["value"] = $value;
        if (is_array($value)) {
            $data["value"] = implode(",", $value);
        }
        $data["id"] = $this->input->get("id");
        $data["size"] = strlen($data["value"]) + 5;
        $data["type"] = $this->input->get("type");
        $data["category"] = $this->input->get("category");

        switch ($data["type"]) {
            case "dropdown":
                $output = $this->_get_dropdown($data["category"],
                        $data["value"], $data["name"]);
                break;
            case "multiselect":
                $output = $this->_get_multiselect($data["category"],
                        $data["value"], $data["name"]);
                break;
            case "textarea":
                $output = form_textarea($data, $data["value"]);
                break;
            default:
                $output = form_input($data);
        }

        echo $output;
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $value = $this->input->post("value");
        if (is_array($value)) {
            $value = implode(",", $value);
        }
        $values = array(
                $this->input->post("field") => $value
        );
        $this->room->update($id, $values);
        echo $value;
    }

    function _get_dropdown ($category, $value, $field)
    {
        $this->load->model("variable_model", "variable");
        $categories = $this->variable->get_pairs($category,
                array(
                        "field" => "value",
                        "direction" => "ASC"
                ));
        $pairs = get_keyed_pairs($categories,
                array(
                        "value",
                        "name"
                ));
        return form_dropdown($field, $pairs, $value, "class='live-field'");
    }
}