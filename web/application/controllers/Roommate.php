<?php

defined('BASEPATH') or exit ('No direct script access allowed');

// roommate.php Chris Dart Dec 28, 2013 10:08:58 PM chrisdart@cerebratorium.com
class Roommate extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("roommate_model", "roommate");
		$this->load->model("payer_model", "payer");
		$this->load->model("hotel_model", "hotel");
		$this->load->model("room_model", "room");
	}

	function view() {}

	function view_for_tour($tour_id, $stay) {
		$this->load->model("variable_model", "variable");
		$data ["room_count"] = $this->room->get_room_count($tour_id, $stay);
		$data ["sizes"] = get_keyed_pairs($this->variable->get_pairs("room_type", [
			"direction" => "ASC",
			"field" => "value",
		]), [
			"value",
			"name",
		]);

		if ($tour_id && $stay) {
			$rooms = $this->room->get_for_tour($tour_id, $stay);
			$sizes = [];
			foreach ($rooms as $room) {
				$room->roommates = $this->roommate->get_for_room($room->id);
				$sizes[$room->size][$room->room_id] = $room;
			}
			ksort($sizes);
			$data['sizes'] = $sizes;
			$data['rooms'] = $rooms;

			$hotel = $this->hotel->get_by_stay($tour_id, $stay);

			$data ["last_stay"] = $this->hotel->get_last_stay($tour_id);

			$data ["hotel"] = $hotel;
			$data ["tour_id"] = $tour_id;
			$data ["stay"] = $stay;

			$data ["target"] = "roommate/list";
			$data ["title"] = sprintf("Roommate List for Tour: %s, Stay: %s", $hotel->tour_name, $stay);
			$this->load->view("page/index", $data);
		}
	}

	/**
	 * duplicate duplicates all the rooms from the previous stay to the current
	 * stay.
	 */
	function duplicate() {
		$tour_id = $this->input->post("tour_id");
		$stay = $this->input->post("stay");
		$previous_stay = $stay - 1;
		$rooms = $this->room->get_for_tour($tour_id, $previous_stay);
		foreach ($rooms as $room) {
			$new_room = $this->room->create($room->tour_id, $stay, $room->size);
			$persons = $this->roommate->get_for_room($room->id);
			foreach ($persons as $person) {
				$data = [
					"person_id" => $person->person_id,
					"tour_id" => $tour_id,
					"stay" => $stay,
					"room_id" => $new_room->id,
					"placeholder" => $person->placeholder,
				];
				$this->roommate->insert($data);
			}
		}
		redirect('roommate/view_for_tour/' . $tour_id . '/' . $stay);
	}

	function view_for_stay() {
		$tour_id = $this->input->get("tour_id");
		$stay = $this->input->get("stay");
	}

	function create_room() {
		$tour_id = $this->input->get("tour_id");
		$stay = $this->input->get("stay");
		if ($tour_id && $stay) {
			$last_room = $this->roommate->get_last_room($tour_id, $stay);
			$room_list = $this->roommate->get_room_numbers($tour_id, $stay);
			$data['tour_id'] = $tour_id;
			$data['stay'] = $stay;
			$data ["room_number"] = get_first_missing_number($room_list, "room");
			$data ["roommate_list"] = $this->get_roomless_menu($tour_id, $stay, $data['room_number']);
			$data ["roommates"] = FALSE;
			$this->load->view("roommate/room", $data);
		}
	}

	/**
	 * for getting the next placeholder for the tour and stay
	 * This is for busdrivers and placeholder roommates.
	 *
	 * @param int $tour_id
	 * @param int $stay
	 */
	function add_placeholder(int $tour_id, int $stay) {
		$person_id = $this->roommate->get_next_placeholder($tour_id, $stay);
		echo sprintf('<input type="text" data-tour_id="%s" data-stay="%s" data-person_id="%s" value="" class="insert-placeholder" placeholder="Enter a Placeholder"/>', $tour_id, $stay, $person_id);
	}

	function insert_placeholder() {
		if ($placeholder = $this->input->post("placeholder")) {
			$values ['tour_id'] = $this->input->post("tour_id");
			$values ['stay'] = $this->input->post("stay");
			$values ['person_id'] = $this->input->post("person_id");
			$values ['room_id'] = $this->input->post("room_id");
			$values ['placeholder'] = $placeholder;
			$this->roommate->insert($values);
			echo $placeholder;
		}
	}

	function insert_row() {
		$this->roommate->insert();
		$tour_id = $this->input->post("tour_id");
		$stay = $this->input->post("stay");
		$room_id = $this->input->post("room_id");
		$this->load->model("room_model", "room");
		$room = $this->room->get($room_id);
		$room->roommates = $this->roommate->get_for_room($room->id);
		$data ["room"] = $room;
		$data['tour_id'] = $tour_id;
		$data['stay'] = $stay;
		$this->load->view("room/edit", $data);
	}

	function update_value() {
		$tour_id = $this->input->post("tour_id");
		$stay = $this->input->post("stay");
		$values = [
			$this->input->post("field") => $value = trim($this->input->post("value")),
		];
		$this->roommate->update($id, $values);
		print $this->input->post("value");
	}

	function delete() {
		$deletion = [
			"person_id" => $this->input->post("person_id"),
			"room_id" => $this->input->post("room_id"),
			"stay" => $this->input->post("stay"),
			"tour_id" => $this->input->post("tour_id"),
		];
		$this->roommate->delete($deletion);
		$tour_id = $this->input->post("tour_id");
		$stay = $this->input->post("stay");
		$room_id = $this->input->post("room_id");
		$data['tour_id'] = $tour_id;
		$data['stay'] = $stay;
		$data ["room"] = $this->room->get($room_id);
		$data ["room"]->roommates = $this->roommate->get_for_room($room_id);
		$this->load->view("room/edit", $data);
	}

	/**
	 * generate a dropdown form menu of all those without rooms for the given
	 * tour and stay
	 */
	function get_roomless_menu($tour_id, $stay, $room_number) {
		$class = FALSE;
		if ($this->input->get("class")) {
			$class = $this->input->get("class");
		}
		$roomless = $this->roommate->get_roomless($tour_id, $stay);
		$roomless_pairs = get_keyed_pairs($roomless, [
			"id",
			"person_name",
		], TRUE);
		$output = json_encode([
			'roomless' => $roomless_pairs,
			'room_number' => $room_number,
			'tour_id' => $tour_id,
			'stay' => $stay,
			'class' => $class,
			'url' => site_url("roommate/add_placeholder/$tour_id/$stay"),
		]);
		$this->output->set_content_type('application/json')->set_output($output);
	}

}
