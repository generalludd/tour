<?php

defined('BASEPATH') or exit('No direct script access allowed');

// payer_model.php Chris Dart Dec 14, 2013 5:47:05 PM
// chrisdart@cerebratorium.com
class Payer_model extends My_Model {

	var $payment_type;

	var $room_size;

	var $discount;

	var $surcharge;

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

	function getForTour($payer_id, $tour_id): ?object {
		$this->db->from('payer');
		$this->db->where('payer.payer_id', $payer_id);
		$this->db->where('payer.tour_id', $tour_id);
		$this->db->select('payer.*');
		$this->db->join('tour', 'tour.id = payer.tour_id');
		$this->db->select('tour.tour_name, tour.due_date, tour.start_date, tour.end_date, tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price, tour.single_room, tour.triple_room, tour.quad_room');
		$payer =  $this->db->get()->row();
		$this->load->model('person_model', 'person');
		$payer->person = $this->person->get($payer_id);
		if(empty($payer->person)){
			return null;
		}
		$payer->ticket_cost = $this->getTicketCost($payer_id, $tour_id)??0;
		$payer->amount_paid = $this->getPayments($tour_id, $payer_id);
		$payer->discount = floatval($payer->discount??0);
		$payer->surcharge = floatval($payer->surcharge??0);
		$payer->amount_due = floatval($payer->ticket_cost - $payer->amount_paid + $payer->surcharge - $payer->discount);
		$this->load->model('payment_model','payment');
		$payer->payments = $this->payment->get_all($payer->tour_id, $payer->payer_id);
		$this->load->model('tourist_model','tourist');
		$payer->tourists = $this->tourist->get_for_payer($payer->payer_id, $payer->tour_id);
		$payer->price = match ($payer->payment_type) {
			'full_price' => $payer->full_price,
			'banquet_price' => $payer->banquet_price,
			'early_price' => $payer->early_price,
			'regular_price' => $payer->regular_price,
			default => 0,
		};
		if ($payer->price == 0) {
			$payer->room_rate = 0;
		}
		else {
			$payer->room_rate = match ($payer->room_size) {
				'single_room' => $payer->single_room,
				'triple_room' => $payer->triple_room,
				'quad_room' => $payer->quad_room,
				default => 0,
			};
		}
		if ($payer->is_comp == 1 || $payer->is_cancelled == 1) {
			$payer->price = 0;
			$payer->room_rate = 0;
		}
		$this->load->model('letter_model', 'letter');
		$letters = $this->letter->get_for_tour($payer->tour_id);
		if(!empty($letters)) {

			$this->load->model('merge_model', 'merge');
			foreach($letters as $letter) {
				$payer->merge[] = $this->merge->get_for_payer($payer->payer_id, $letter->id);
			}
		}
		return $payer;
	}

	function getAmountDue(int $payer_id, int $tour_id): int {
		// Get the sum of the payments:
		$payments = $this->getPayments($tour_id, $payer_id);
		$ticket_cost = $this->getTicketCost($payer_id, $tour_id);

		return $ticket_cost - $payments;
	}

	function getTicketCost(int $payer_id, int $tour_id): float {
		$price_levels = $this->getPriceLevels($tour_id, $payer_id);
		if ($price_levels->is_comp == 1 || $price_levels->is_cancelled == 1) {
			return 0;
		}
		$rate_values = $this->getRateValues($tour_id, $price_levels);

		$ticket_count = $this->getTouristCount($payer_id, $tour_id);

		$price = $rate_values->{$price_levels->payment_type} + $rate_values->{$price_levels->room_size};
		return $price * $ticket_count;
	}

	function getTouristCount($payer_id, $tour_id) {
		$this->db->where("tourist.payer_id", $payer_id);
		$this->db->where("tourist.tour_id", $tour_id);
		$this->db->from("tourist");
		return $this->db->count_all_results();
	}

	function getPayers($tour_id, $options = []): array {
		$this->db->where("payer.tour_id", $tour_id);
		$this->db->select(
			'payer_id');
		$this->db->join('person','person.id = payer.payer_id');
		$this->db->order_by('payer.is_cancelled', 'ASC');
		$this->db->order_by('person.last_name', 'ASC');
		$this->db->order_by('person.first_name', 'ASC');
		$payers = $this->db->get("payer")->result();
		$output = [];
		foreach($payers as $payer){
			$entity = $this->getForTour($payer->payer_id, $tour_id);
			if(!empty($entity)){
				$output[] = $entity;
			}
		}
		return $output;
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

	function updateValue($payer_id, $tour_id, $field, $value): void {
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
		$output =  $this->db->from('payment')
			->where('tour_id', $tour_id)
			->where('payer_id', $payer_id)
			->select_sum('amount')->get()->row()->amount;
		return floatval($output);
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

	function update_payments(): void {
		$this->db->select('payer.*')
			->join('payment', 'payer.tour_id=payment.tour_id AND payer.payer_id = payment.payer_id', 'LEFT')
		->where('payment.tour_id', NULL)
		->from('payer');
		$payers = $this->db->get()->result();
		// Get an array of the tour due dates.
		$this->db->select('tour.id, tour.due_date')
			->from('tour');
		$tours = $this->db->get()->result();
		$tour_ids = [];
		foreach($tours as $tour){
			$tour_ids[$tour->id] = $tour->due_date;
		}
		foreach($payers as $payer){
			if($payer->amt_paid) {
				$due_date = $tour_ids[$payer->tour_id];
				$this->db->insert('payment', ['amount' => $payer->amt_paid, 'receipt_date' => $due_date, 'payer_id' => $payer->payer_id, 'tour_id' => $payer->tour_id]);
			}
		}
	}

}
