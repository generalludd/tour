<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payment.php Chris Dart Mar 9, 2014 7:20:32 PM chrisdart@cerebratorium.com
class Payment extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("payment_model", "payment");
	}

	function get_amount_paid(): void {
		$tour_id = $this->input->get("tour_id");
		$payer_id = $this->input->get("payer_id");
		$this->load->model("payment_model", "payment");
		$output = $this->payment->get_total($tour_id, $payer_id);
		print_r($output);
	}

	function view_list($tour_id = NULL, $payer_id = NULL, $type = "payment"): void {
		if (!$tour_id || !$payer_id) {
			$tour_id = $this->input->get("tour_id");
			$payer_id = $this->input->get("payer_id");
		}
		$data["payments"] = $this->payment->get_all($tour_id, $payer_id);
		$data["tour_id"] = $tour_id;
		$data["payer_id"] = $payer_id;
		if ($type == "payment") {
			$this->load->view("payment/list", $data);
		}
		else {
			$this->load->view("payment/reimbursement", $data);

		}
	}

	function create(): void {
		$data["tour_id"] = $this->input->get("tour_id");
		$data["payer_id"] = $this->input->get("payer_id");
		$data["type"] = $this->input->get("type");
		$this->load->view("payment/insert", $data);
	}

	function insert($type) {
		$id = $this->payment->insert();
		$payment = $this->payment->get($id);
		$this->view_list($payment->tour_id, $payment->payer_id, $type);
	}

	function edit() {
	}

	function update() {
	}

	function delete($type) {
		$id = $this->input->post("id");
		$payment = $this->payment->get($id);
		$this->payment->delete($id);
		$this->view_list($payment->tour_id, $payment->payer_id, $type);
	}

}
