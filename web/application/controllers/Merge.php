<?php

defined('BASEPATH') or exit('No direct script access allowed');

// merge.php Chris Dart Mar 15, 2014 2:35:26 PM chrisdart@cerebratorium.com
class Merge extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("merge_model", "merge");
		$this->load->model("letter_model", "letter");
		$this->load->model("payer_model", "payer");
		$this->load->model("tour_model", "tour");
		$this->load->model("tourist_model", "tourist");
	}

	function create(): void {
		$tour_id = $this->input->get("tour_id");
		$payer_id = $this->input->get("payer_id");
		$letter_id = $this->input->get("letter_id");
		$tour = $this->tour->get($tour_id);

		$payer = $this->payer->get_for_tour($payer_id, $tour_id);
		$payer->tourists = $this->tourist->get_for_payer($payer_id, $tour_id);
		$payer->price = get_tour_price($payer);
		$payer->rate = get_room_rate($payer);

		$tourist_count = $this->payer->get_tourist_count($payer->payer_id, $payer->tour_id);

		$payer->amt_due = get_amount_due($payer, $tourist_count);

		$payer->tourist_count = $tourist_count;
		$data["tour"] = $tour;
		// Generate a salutation based on the payer->tourists first_name and last_name values.
		$payer->salutation = $this->payer->getSalutation($payer);

		$merge_id = $this->merge->quick_insert($payer_id, $letter_id);
		$data["merge"] = $this->merge->get($merge_id);

		$data["payer"] = $payer;
		$data["letter"] = $this->letter->get($letter_id);
		$data["target"] = "merge/edit";
		$data["title"] = "Preparing a Merge Letter";
		$data["action"] = "insert";
		$data['styles'] = ['letter'];
		$data['scripts'] = ["https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"];
		$this->load->view("page/index", $data);
	}

	function update_value(): void {
		$id = $this->input->post("id");
		$field = $this->input->post("field");
		$value = $value = trim($this->input->post("value"));
		if (strstr($field, "date")) {
			$value = date('Y-m-d', strtotime($value));
		}
		$values = [
			$this->input->post("field") => $value,
		];
		$this->merge->update($id, $values);
		echo $this->input->post("value");
	}

	function get_note(): void {
		$id = $this->input->get("id");
		$data["merge"] = $this->merge->get($id);
		$this->load->view("merge/note", $data);
	}

	function create_note(): void {
		$data["merge"] = NULL;
		$this->load->view("merge/note", $data);
	}

}
