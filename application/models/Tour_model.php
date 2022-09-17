<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tour_model.php Chris Dart Dec 13, 2013 7:57:28 PM chrisdart@cerebratorium.com
class Tour_model extends MY_Model {

	var $tour_name;

	var $start_date;

	var $end_date;

	var $due_date;

	var $full_price;

	var $banquet_price;

	var $early_price;

	var $regular_price;

	var $single_room;

	var $triple_room;

	var $quad_room;


	function prepare_variables() {
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

				$this->{$my_variable} = $my_value;
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

		$tour->tourists = $this->payer->get_payers($id);
		$this->load->model('hotel_model', 'hotel');
		$tour->hotels = $this->hotel->get_all($id);
		return $tour;
	}

	function get_all($archived = TRUE, $fields = "*"): array {
		$this->db->from("tour");
		$this->db->select($fields);
		$this->db->order_by("tour.start_date", "DESC");
		if ($archived) {
			$this->db->where("tour.start_date < CURDATE()", NULL, FALSE);
		}
		else {
			$this->db->where('tour.status', 1);
			$this->db->where("tour.start_date > CURDATE()", NULL, FALSE);
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
		$this->db->where("id", $id);

		if (empty($values)) {
			$this->prepare_variables();
			$this->db->update("tour", $this);
		}
		else {
			$this->db->update("tour", $values);
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
