<?php

defined('BASEPATH') or exit('No direct script access allowed');

// tour_model.php Chris Dart Dec 13, 2013 7:57:28 PM chrisdart@cerebratorium.com
class Tour_model extends MY_Model {

	public string $tour_name;

	public string $start_date;

	public string $end_date;

	public string $due_date;

	public $full_price;

	public $banquet_price;

	public $early_price;

	public $regular_price;

	public $single_room;

	public $triple_room;

	public $quad_room;

	function prepare_variables(): void {
		$variables = [
			"tour_name",
			"start_date",
			"end_date",
			"due_date",
			"full_price",
			"banquet_price",
			"early_price",
			"regular_price",
			"single_room",
			"triple_room",
			"quad_room",
		];

		$dates = [
			"start_date",
			"end_date",
			"due_date",
		];
		$money = [
			"full_price",
			"banquet_price",
			"early_price",
			"regular_price",
			"single_room",
			"triple_room",
			"quad_room",
		];

		for ($i = 0; $i < count($variables); $i++) {
			$my_variable = $variables[$i];

			if ($this->input->post($my_variable)) {
				$my_value = $this->input->post($my_variable);

				if (in_array($my_variable, $money)) {
					$this->{$my_variable} = $my_value;
				}
				if (in_array($my_variable, $dates)) {
					$this->{$my_variable} = date('Y-m-d', strtotime($my_value));
				}
				else {
					$this->{$my_variable} = $my_value;
				}
			}
		}
	}

	function get($id, $fields = FALSE) {
		$this->db->from("tour")
			->where("id", $id);
		if ($fields) {
			$this->db->select($fields);
		}
		$tour = $this->db->get()->row();
		$this->load->model('payer_model', 'payer');

		$tour->tourists = $this->payer->getPayers($id);
		// Loop through the tourists to get totals for amt_paid, discount, surcharge, and amount_due
		$tour->total_paid = 0;
		$tour->total_discount = 0;
		$tour->total_surcharge = 0;
		$tour->total_due = 0;
		$tour->total_payers = 0;
		$tour->total_tourists = 0;
		$tour->total_cancels = 0;
		foreach ($tour->tourists as $payer) {
			$tour->total_paid +=floatval($payer->amount_paid??0);
			$tour->total_discount += floatval($payer->discount??0);
			$tour->total_surcharge += floatval($payer->surcharge??0);
			$tour->total_due += floatval($payer->amount_due??0);
			$tour->total_payers+= $payer->is_cancelled == 0? 1:0;
			$tour->total_tourists += $payer->is_cancelled != 1? count($payer->tourists):0;
			$tour->total_cancels += $payer->is_cancelled == 1? count($payer->tourists):0;
		}
		$this->load->model('hotel_model', 'hotel');
		$tour->hotels = $this->hotel->get_all($id);
		return $tour;
	}

	function get_all($archived = TRUE, $fields = "*"): array {
		$now = date('Y-m-d');
		$this->db->from("tour");
		$this->db->select($fields);
		$this->db->order_by("tour.start_date", "DESC");
		if ($archived) {
			$this->db->where("tour.start_date <", $now);
		}
		else {
			$this->db->where('tour.status', 1);
			$this->db->where("tour.start_date >", $now);
		}
		$results = $this->db->get()->result();
		$this->load->model('hotel_model', 'hotel');
		foreach ($results as $tour) {
			$tour->hotels = $this->hotel->get_all($tour->id);
		}
		return $this->keyed($results, 'id');
	}

	function get_value($id, $field) {
		$this->db->from("tour");
		$this->db->where("id", $id);
		$this->db->select($field);
		$result = $this->db->get()->row();
		return $result->$field;
	}

	function insert() {
		$this->prepare_variables();
		$this->db->insert("tour", $this);
		return $this->db->insert_id();
	}

	function update($id, $values = []) {
		$this->id = $id;

		if (empty($values)) {
			$this->prepare_variables();
			$this->db->replace("tour", $this);

		}
		else {
			$this->db->replace("tour", $values);
		}
	}

	function get_payment_types($tour_id) {
		$payment_types = get_keyed_pairs($this->variable->get_pairs('payment_type'), [
			'value',
			'name',
		]);
		$this->db->from('tour')
			->where('id', $tour_id)
			->select(array_keys($payment_types));
		$result = $this->db->get()->row();
		$this->load->model('variable_model', 'variable');

		foreach ($payment_types as $value => $name) {
			if (empty($result->{$value})) {
				unset($payment_types[$value]);
			}
		}
		return $payment_types;
	}

	function delete($id) {
		$this->db->where('id', $id)
			->delete('tour');
	}

	function disable($id) {
		$this->db->where('id', $id)
			->update('tour', ['status' => 0]);
	}

}
