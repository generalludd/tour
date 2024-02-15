<?php
defined('BASEPATH') or exit('No direct script access allowed');

// roommate_model.php Chris Dart Dec 28, 2013 10:09:30 PM
// chrisdart@cerebratorium.com
class Roommate_Model extends MY_Model {

	protected int $tour_id;

	protected int $person_id;

	protected int $stay;

	protected int $room_id;

	protected int $placeholder;


	function prepare_variables(): void {
		$variables = [
			'tour_id',
			'person_id',
			'stay',
			'room_id',
		];
		prepare_variables($this, $variables);
	}


	function get_for_tour($tour_id, $stay, $room_id) {
		$this->db->from('roommate');
		$this->db->join('person', 'roommate.person_id = person.id');
		$this->db->where('roommate.tour_id', $tour_id);
		$this->db->where('roommate.stay', $stay);
		$this->db->where('roommate.room_id', $room_id);
		$this->db->select(
			'CONCAT(person.first_name, " ", person.last_name) as person_name, roommate.person_id',
			FALSE);
		$this->db->order_by('roommate.room_id');
		$result = $this->db->get()->result();
		return $result;
	}

	function get_room_count($tour_id) {
		$this->db->from('roommate');
		$this->db->where('tour_id', $tour_id);
		$this->db->select('DISTINCT(`room_id`) AS room_count', FALSE);
		$this->db->group_by('room_id');
		$this->db->get()->result();
		$result = $this->db->count_all_results();
		return $result;
	}

	function get_for_room($room_id): array {
		$this->db->from('roommate');
		$this->db->where('room_id', $room_id);
		$this->db->join('person', 'roommate.person_id=person.id', 'left');
		$this->db->join('tourist', 'tourist.person_id=person.id', 'left');
		$this->db->select(
			'roommate.room_id, roommate.tour_id, roommate.person_id, roommate.placeholder');
		$this->db->select(
			'CONCAT(person.first_name," ",person.last_name) as person_name',
			FALSE);
		$this->db->select('tourist.payer_id');
		$roommates = $this->db->get()->result();
		$output = [];
		foreach ($roommates as $roommate) {
			$output[$roommate->person_id] = $roommate;
		}
		return $output;
	}

	function get_roomless($tour_id, $stay) {
		$this->db->select(
			"person.id, concat(person.first_name,' ', person.last_name) as person_name",
			FALSE)
			->from('tourist')
			->join('hotel', 'tourist.tour_id= hotel.tour_id', 'left')
			->join('roommate',
			'tourist.person_id = roommate.person_id AND tourist.tour_id = roommate.tour_id AND hotel.stay = roommate.stay',
			'left')
			->join('person', 'tourist.person_id=person.id')
			->join('payer',
			'tourist.payer_id = payer.payer_id AND tourist.tour_id = payer.tour_id')
			->where('tourist.tour_id', $tour_id)
			->where('hotel.stay', $stay)
			->where('payer.is_cancelled !=', 1)
			->where('roommate.person_id =', NULL)
			->order_by('person.last_name', 'ASC')
			->order_by('person.first_name', 'ASC');
		return $this->db->get()->result();
	}

	function get_next_placeholder($tour_id, $stay) {
		//select (person_id -1) as person_id from roommate where person_id < 1 order by person_id asc limit 1
		$this->db->from('roommate');
		$this->db->select('(person_id -1) as person_id');
		$this->db->where('tour_id', $tour_id);
		$this->db->where('stay', $stay);
		$this->db->where('person_id < ', 1);
		$this->db->order_by('person_id', 'asc');
		$this->db->limit(1);
		$result = $this->db->get()->row();
		if (!$result) {
			$output = -1;
		}
		else {
			$output = $result->person_id;
		}
		return $output;

	}

	function insert($data = []): void {
		if (empty($data)) {
			$this->prepare_variables();
			$this->db->insert('roommate', $this);
		}
		else {
			$this->db->insert('roommate', $data);
		}
	}

	function delete($deletion): void {
		$this->db->delete('roommate', $deletion);
	}

	function get_room_numbers($tour_id, $stay) {
		$this->db->from('roommate');
		$this->db->where('tour_id', $tour_id);
		$this->db->where('stay', $stay);
		$this->db->order_by('room_id', 'ASC');
		$this->db->group_by('room_id');
		$this->db->select('room_id');
		return $this->db->get()->result();
	}

	function get_last_room($tour_id, $stay) {
		$this->db->where('tour_id', $tour_id);
		$this->db->where('stay', $stay);
		$this->db->select('room_id');
		$this->db->group_by('room_id');
		$this->db->order_by('room_id', 'DESC');
		$this->db->limit(1);
		$this->db->from('roommate');
		$result = $this->db->get()->row();
		$output = 0;
		if ($result) {
			$output = $result->room_id;
		}
		return $output;
	}

	function delete_payer($payer_id, $tour_id): void {
		$this->load->model('tourist_model', 'tourist');
		$people = $this->tourist->get_for_payer($payer_id, $tour_id);
		// Get tourists for a payer.
		foreach ($people as $person) {
			$this->delete_tourist($person->person_id, $tour_id);
		}


	}

	function delete_tourist($person_id, $tour_id): void {
		$this->db->where('person_id', $person_id);
		$this->db->where('tour_id', $tour_id);
		$this->db->delete('roommate');
	}

	function delete_for_stay($tour_id, $stay): void {
		$this->db->delete("room", ["tour_id" => $tour_id, "stay" => $stay]);
		$this->db->delete("roommate", ["tour_id" => $tour_id, "stay" => $stay]);
	}

}
