<?php

defined('BASEPATH') or exit('No direct script access allowed');

// payer_model.php Chris Dart Dec 14, 2013 5:47:05 PM
// chrisdart@cerebratorium.com
class Payer_model extends My_Model {

	var $payment_type;

	var $room_size;

	var $discount;

	var $amt_paid;

	var $is_comp = 0;

	var $is_cancelled = 0;

	var $note;

	function prepare_variables() {
		$variables = [
			"payment_type",
			"room_size",
			"discount",
			"surcharge",
			"amt_paid",
			"is_comp",
			"is_cancelled",
			"note",
		];
		for ($i = 0; $i < count($variables); $i++) {
			$my_variable = $variables[$i];
			$this->{$my_variable} = $this->input->post($my_variable);
		}
	}

	function get_value($payer_id, $tour_id, $field) {
		$this->db->from("payer");
		$this->db->where("payer_id", $payer_id);
		$this->db->where("tour_id", $tour_id);

		// @TODO convert this into an array option for multiple fields
		$this->db->select($field);
		$result = $this->db->get()->row();
		return $result;
	}

	function get_for_tour($payer_id, $tour_id) {
		$this->db->from('payer');
		$this->db->join('tour', 'payer.tour_id=tour.id');
		$this->db->join('payment',
			'payer.tour_id=payment.tour_id AND payer.payer_id = payment.payer_id', 'LEFT');
		$this->db->join('person', 'payer.payer_id=person.id');
		$this->db->where('payer.payer_id', $payer_id);
		$this->db->where('payer.tour_id', $tour_id);
		$this->db->select(
			'tour.tour_name,tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price,tour.single_room, tour.triple_room, tour.quad_room');
		$this->db->select('person.first_name, person.last_name');
		$this->db->select('payer.*');
		$this->db->select_sum('payment.amount');
		return $this->db->get()->row();
	}

	function get_amount_due(int $payer_id, int $tour_id): int {
		// Get the sum of the payments:
		$payments = $this->getPayments($tour_id, $payer_id);
		$ticket_cost = $this->get_ticket_cost($payer_id, $tour_id);

		return $ticket_cost - $payments;
	}

	function get_ticket_cost(int $payer_id, int $tour_id): float {
		$price_levels = $this->getPriceLevels($tour_id, $payer_id);
		if ($price_levels->is_comp == 1 || $price_levels->is_cancelled == 1) {
			return 0;
		}
		$rate_values = $this->getRateValues($tour_id, $price_levels);

		$ticket_count = $this->get_tourist_count($payer_id, $tour_id);

		$price = $rate_values->{$price_levels->payment_type} + $rate_values->{$price_levels->room_size};
		return $price * $ticket_count;
	}

	function get_tourist_count($payer_id, $tour_id) {
		$this->db->where("tourist.payer_id", $payer_id);
		$this->db->where("tourist.tour_id", $tour_id);
		$this->db->from("tourist");
		return $this->db->count_all_results();
	}

	function get_payers($tour_id, $options = []) {
		$this->db->where("payer.tour_id", $tour_id);
		$this->db->where("`tour`.`id` = `payer`.`tour_id`", NULL, FALSE);
		$this->db->join("person", "person.id = payer.payer_id");
		$this->db->from("payer,tour");
		$this->db->select(
			"tour.id, tour.tour_name,tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price, tour.single_room, tour.triple_room, tour.quad_room");
		$this->db->select(
			"payer.*, person.first_name, person.last_name, person.email");
		if (array_key_exists("include_address", $options) &&
			$options["include_address"]) {
			$this->db->join("address", "person.address_id = address.id");
			$this->db->select(
				"address, address.city, address.state, address.zip, address.informal_salutation, address.formal_salutation");
		}
		$this->db->join("letter", "letter.tour_id = payer.tour_id", "LEFT");
		$this->db->join("merge", "merge.payer_id = payer.payer_id AND letter.id = merge.letter_id", "LEFT");
		$this->db->select("merge.id as merge_id");
		$this->db->order_by('payer.is_cancelled', 'ASC');
		$this->db->order_by("person.last_name", "ASC");
		$this->db->order_by("person.first_name", "ASC");
		$result = $this->db->get()->result();
		return $result;
	}

	function get_payer_object($payer) {
		$this->load->model('person_model', 'person');
		$this->load->model('payment_mode', 'payment');
		$payer->person = $this->person->get($payer->payer_id);
		$payer->tourists = $this->tourist->get_for_payer($payer->payer_id, $payer->tour_id);
		$payer->payments = $this->payment->get_all($payer->tour_id, $payer->payer_id);
		$payer->amt_paid = 0;
		foreach ($payer->payments as $payment) {
			$payer->amt_paid += $payment->amount;
		}
		switch ($payer->payment_type) {
			case 'full_price' :
				$payer->price = $payer->full_price;
				break;
			case 'banquet_price' :
				$payer->price = $payer->banquet_price;
				break;
			case 'early_price' :
				$payer->price = $payer->early_price;
				break;
			case 'regular_price' :
				$payer->price = $payer->regular_price;
				break;
			default :
				$payer->price = 0;
				break;
		}
		if ($payer->price == 0) {
			$payer->room_rate = 0;
		}
		else {
			switch ($payer->room_size) {
				case 'single_room' :
					$payer->room_rate = $payer->single_room;
					break;
				case 'triple_room' :
					$payer->room_rate = $payer->triple_room;
					break;
				case 'quad_room' :
					$payer->room_rate = $payer->quad_room;
					break;
				default :
					$payer->room_rate = 0;
					break;
			}
		}
		if ($payer->is_comp == 1 || $payer->is_cancelled) {
			$payer->price = 0;
			$payer->room_rate = 0;
		}
		$payer->amt_due = get_payment_due($payer);
		return $payer;
	}

	/**
	 * Get the number room types for a given tour (single, double, triple, quad)
	 *
	 * @param int $tour_id
	 *
	 * @return object
	 */
	function get_room_types(int $tour_id): object {
		$this->db->select("count(room_size) as count, room_size");
		$this->db->from("payer");
		$this->db->where("tour_id", $tour_id);
		$this->db->group_by("room_size");
		return $this->db->get()->result();
	}

	function update($payer_id, $tour_id): void {
		$this->db->where("tour_id", $tour_id);
		$this->db->where("payer_id", $payer_id);
		$this->prepare_variables();
		$this->db->update("payer", $this);
	}

	function updateValue($payer_id, $tour_id, $field, $value){
		$this->db->where("tour_id", $tour_id);
		$this->db->where("payer_id", $payer_id);
		$this->db->update("payer", [$field => $value]);

	}
	function insert($payer_id, $tour_id): void {
		$insert_array = [
			"payer_id" => $payer_id,
			"tour_id" => $tour_id,
		];
		$this->db->insert('payer', $insert_array);
	}

	function delete($payer_id, $tour_id): void {
		$this->db->where("payer_id", $payer_id)
			->where("tour_id", $tour_id)
			->delete("payer");
	}

	/**
	 * @param int $tour_id
	 * @param int $payer_id
	 *
	 * @return mixed
	 */
	public function getPayments(int $tour_id, int $payer_id): mixed {
		return $this->db->from('payment')
			->where('tour_id', $tour_id)
			->where('payer_id', $payer_id)
			->select_sum('amount')->get()->row()->amount;
	}

	/**
	 * @param int $tour_id
	 * @param int $payer_id
	 *
	 * @return object
	 */
	public function getPriceLevels(int $tour_id, int $payer_id): object {
		return $this->db->from('payer')
			->where('tour_id', $tour_id)
			->where('payer_id', $payer_id)
			->select(['payment_type', 'room_size', 'is_comp', 'is_cancelled'])
			->get()->row();
	}

	/**
	 * @param int $tour_id
	 * @param mixed $price_levels
	 *
	 * @return \stdClass
	 */
	public function getRateValues(int $tour_id, mixed $price_levels): stdClass {
		if (!in_array($price_levels->room_size, [
			'single_room',
			'triple_room',
			'quad_room',
			'double_room',
		])) {
			$price_levels->room_size = 'double_room';
		}
		if (empty($price_levels->payment_type)) {
			$price_levels->payment_type = 'full_price';
		}
		return $this->db->from('tour')
			->where('id', $tour_id)
			->select([$price_levels->payment_type, $price_levels->room_size])
			->get()->row();
	}

	/**
	 * Generates a salutation based on the tourists in the payer's party.
	 *
	 * @param object $payer
	 *
	 * @return string
	 */
	function getSalutation(object $payer): string {
		$salutation = "Dear Bleacher Bum";

		// create a salutation based on each tourist first_name and last_name
		if (!empty($payer->tourists)) {
			$salutation = NULL;
			$names = [];
			foreach ($payer->tourists as $tourist) {
				$names[] = $tourist->first_name;
			}
			$salutation .= implode(", ", $names);;
		}
		return $salutation;
	}

	function appendNote($payer_id, $tour_id, $note): void {
		$existing_note = $this->get_value($payer_id, $tour_id, 'note');
		$new_note = '';
		if (!empty($existing_note)) {
			$new_note = $existing_note->note . '\\n\\r' . $note;
		}
		else {
			$new_note = $note;
		}
		$this->db
			->where('tour_id', $tour_id)
			->where('payer_id', $payer_id)
			->update('payer', ['note' => $new_note]);
	}

}
