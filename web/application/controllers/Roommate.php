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

	function view_for_tour($tour_id, $stay): void {
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
			$data['scripts'] = [site_url('js/roommate.js'), site_url('js/hotel.js')];
			$data ["target"] = "roommate/list";
			$data ["title"] = sprintf("Roommate List for Tour: %s, Stay: %s", $hotel->tour_name, $stay);
			$this->load->view("page/index", $data);
		}
	}

	/**
	 * duplicate duplicates all the rooms from the previous stay to the current
	 * stay.
	 */
	function duplicate(): void {
		$json = file_get_contents('php://input');
		$input =  json_decode($json, TRUE);
		$tour_id = $input['tour_id'];
		$stay = $input['stay'];
		$previous_stay = $input['previous_stay'];
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
		if($this->input->get('ajax')){
			$output = ['tour_id' => $tour_id, 'stay' => $stay];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else{
			redirect("roommate/view_for_tour/$tour_id/$stay");
		}
	}



	/**
	 * for getting the next placeholder for the tour and stay
	 * This is for bus drivers and placeholder roommates.
	 *
	 */
	function add_placeholder(): void {
		$tour_id = $this->input->get("tour_id");
		$stay = $this->input->get("stay");
		$room_id = $this->input->get("room_id");
		$person_id = $this->roommate->get_next_placeholder($tour_id, $stay);
		$output['person_id'] = $person_id;
		$output['tour_id'] = $tour_id;
		$output['stay'] = $stay;
		$output['room_id'] = $room_id;
		$output['placeholder'] = 'Enter a placeholder';
		$output['url'] = site_url("/roommate/insert_placeholder");

		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	function insert_placeholder(): void {
		$json = file_get_contents('php://input');
		$input =  json_decode($json, TRUE);

		$output = [];
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($output));
		if (!empty($input['placeholder'])) {
			$this->roommate->insert($input);
		}
		$room = $this->room->get($input['room_id']);
		$room->roommates = $this->roommate->get_for_room($room->id);
		$input ["room"] = $room;
		if($this->input->get('ajax')){
			$output['html'] = $this->load->view("room/edit", $input, TRUE);
			$output['room_id'] = $input['room_id'];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else{
			$this->load->view("room/edit", $input);
		}

	}

	function insert_row(): void {
		$json = file_get_contents('php://input');
		$input =  json_decode($json, TRUE);
		$this->roommate->insert($input);
		$room = $this->room->get($input['room_id']);
		$room->roommates = $this->roommate->get_for_room($room->id);
		$input ["room"] = $room;
		if($this->input->get('ajax')){
			$output['html'] = $this->load->view("room/edit", $input, TRUE);
			$output['room_id'] = $input['room_id'];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else{
			$this->load->view("room/edit", $input);
		}
	}

	function update_value(): void {
		$tour_id = $this->input->post("tour_id");
		$stay = $this->input->post("stay");
		$values = [
			$this->input->post("field") => $value = trim($this->input->post("value")),
		];
		$this->roommate->update($id, $values);
		print $this->input->post("value");
	}

	function delete(): void {
		$json = file_get_contents('php://input');
		$data =  json_decode($json, TRUE);
		$this->roommate->delete($data);
		$data ["room"] = $this->room->get($data['room_id']);
		$data ["room"]->roommates = $this->roommate->get_for_room($data['room_id']);
		if($this->input->get('ajax')){
			$output = ['html' => $this->load->view("room/edit", $data, TRUE)];
			$output['room_id'] = $data['room_id'];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else{
			$data['target'] = 'room/edit';
			$data['title'] = 'Edit a room';
			$this->load->view('page/index', $data);
		}
	}

	/**
	 * generate a dropdown form menu of all those without rooms for the given
	 * tour and stay
	 */
	function get_roomless_menu(): void {
			$tour_id = $this->input->get("tour_id");
			$stay = $this->input->get("stay");
			$room_id = $this->input->get("room_id");

		$roomless = $this->roommate->get_roomless($tour_id, $stay);
		$roomless_pairs = get_keyed_pairs($roomless, [
			"id",
			"person_name",
		]);
		$data['placeholder_row'] = (object)['roomless' => $roomless_pairs];
		$data['tour_id'] = $tour_id;
		$data ["room"] = $this->room->get($room_id);
		$data ["room"]->roommates = $this->roommate->get_for_room($room_id);
		if($this->input->get('ajax')){
			$output['html'] = $this->load->view("room/edit", $data, TRUE);
			$output['room_id'] = $room_id;
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else{
			$this->load->view("room/edit", $data);
		}
	}

}
