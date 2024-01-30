<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payment_model.php Chris Dart Mar 9, 2014 6:14:15 PM
// chrisdart@cerebratorium.com
class Payment_Model extends CI_Model {

	var $tour_id;

	var $payer_id;

	var $amount;

	var $receipt_date;

	var $method;


	function prepare_variables() {
		$variables = [
			"tour_id",
			"payer_id",
			"amount",
			"method",
			'receipt_date',
		];
		prepare_variables($this, $variables);
	}

	function get($id) {
		$this->db->where("id", $id);
		$this->db->from("payment");
		$result = $this->db->get()->row();
		return $result;
	}

	function get_all($tour_id, $payer_id) {
		$this->db->where("tour_id", $tour_id);
		$this->db->where("payer_id", $payer_id);
		$this->db->from("payment");
		$result = $this->db->get()->result();
		return $result;
	}

	function get_total($tour_id, $payer_id) {
		$this->db->where("tour_id", $tour_id);
		$this->db->where("payer_id", $payer_id);
		$this->db->select_sum("amount");
		$this->db->from("payment");
		$result = $this->db->get()->row();
		return $result->amount;
	}

	function insert() {
		$this->prepare_variables();
		$this->db->insert("payment", $this);
		return $this->db->insert_id();
	}

	function update($id, $values = []) {
		$this->db->where("id", $id);
		if (empty($array)) {
			$this->prepare_variables();
			$this->db->update("payment", $this);
		}
		else {
			$this->db->update("payment", $values);
		}
	}

	function delete($id) {
		$this->db->where("id", $id);
		$this->db->delete("payment", [
			"id" => $id,
		]);
	}

}
