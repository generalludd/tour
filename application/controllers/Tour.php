<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tour.php Chris Dart Dec 13, 2013 7:53:18 PM chrisdart@cerebratorium.com
class Tour extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("tour_model", "tour");
	}

	function index() {
		$this->view_all();
	}
/** @deprecated  */
	function view($id) {
		redirect('tourist/view_all/' . $id);
	}

	function letters($id){
		$tour = $this->tour->get($id);
		$this->load->model('letter_model', 'letter');
		$data['letters'] = $this->letter->get_for_tour($id);
		$data['tour_id'] = $id;
		$data['tour'] = $tour;
		$data['title'] = 'Letters for ' . $tour->tour_name;
		$data['target'] = 'letter/list';
		if($this->input->get('ajax')){
			$data['ajax'] = '1';
			$this->load->view($data['target'], $data);
		}
		else {
			$this->load->view('page/index', $data);
		}

	}

	function view_all() {
		$archived = $this->input->get('archived');
		$data['archived'] = $archived?0:1;
		$data["tours"] = $this->tour->get_all($archived);
		$data["for_tourist"] = FALSE;
		if($archived){
			$data['title'] = 'Previous Tours';
		}
		else {
			$data["title"] = "Current Tours";
		}
		$data["target"] = "tour/list";
		$this->load->view("page/index", $data);
	}

	function create() {
		$data["action"] = "insert";
		$data["tour"] = (object)['id' => NULL, 'tourists'=> []];
		$this->load->view("tour/edit", $data);
	}

	function edit($id) {
		$data["tour"] = $this->tour->get($id);
		$data["action"] = "update";
		$this->load->view("tour/edit", $data);
	}

	function insert() {
		$id = $this->tour->insert();
		redirect('/tourist/view_all/' . $id);
	}

	function update() {
		// get the id for returning to the view page after editing
		$id = $this->input->post('id');
		$this->tour->update($id);
		redirect('/tourist/view_all/' . $id);
	}

	function update_value() {
		$id = $this->input->post("id");
		$field = $this->input->post("field");
		$value = $value = trim($this->input->post("value"));
		if (strstr($field, "price") || strstr($field, "rate")) {
			$value = intval($value);
		}
		elseif (strstr($field, "date")) {
			$value = format_date($value, "mysql");
		}
		$values = [
			$this->input->post("field") => $value,
		];
		$this->tour->update($id, $values);
		echo $this->input->post("value");
	}

	function delete(){
		// If we have an id in the header then we are asking if we're going to delete the tour.
		if($this->input->get('tour_id')){
			$tour = $this->tour->get($this->input->get('tour_id'));
			$data['entity'] = $tour->tour_name;
			$data['identifiers'] = [
				'tour_id' => $tour->id
			];
			$data['action'] = 'tour/delete';
			$data['message'] = 'You can only delete a tour for which there are no current participants.';
			$data['target'] = 'dialogs/delete';
			$data['title'] = 'Delete ' . $tour->tour_name;
			if($this->input->get('ajax')){
				$this->load->view($data['target'], $data);
			}
			else {
				$this->load->view('page/index', $data);
			}
		} else {
			$id = $this->input->post('tour_id');
			$tour = $this->tour->get($id);
			if(count($tour->tourists) > 0){
				$this->tour->disable($tour->id);
				$this->session->set_flashdata('alert', $tour->tour_name . ' has been disabled because there were reservations associated with it.');
			}
			else {
				$this->tour->delete($id);
				$this->session->set_flashdata('alert', $tour->tour_name . ' has been deleted.');

			}
			redirect('tour/view_all');
		}
	}

	function get_value() {
		$tour_id = $this->input->get("tour_id");
		$field = $this->input->get("field");
		$format = FALSE;
		if ($this->input->get("format")) {
			$format = $this->input->get("format");
		}
		$output = $this->tour->get_value($tour_id, $field);

		if (!empty($format) ){
			switch ($format){
				case "date":
					$output = format_date($output);
					break;
				case "money":
					$output = format_money($output);
					break;
			}
		}
		if ($this->input->get("ajax") == 1) {
			echo $output;
		}
		else {
			return $output;
		}
	}

	function show_current($person_id) {
		$this->load->model("tourist_model", "tourist");
		$data["id"] = $person_id;
		$tours = $this->tourist->get_missing_tours($person_id);

		$data['tours'] = $tours;
		$data['target'] = 'tour/select';
		$data['title'] = 'Select a Tour';
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);
		}
		else {
			$this->load->view('page/index', $data);
		}
	}

	function show_missed_tours($person_id) {
		$this->load->model("tourist_model", "tourist");
		$data = [];
		$data['id'] = $person_id;
		$data['tours'] = $this->tourist->get_missing_tours($person_id, 3);
		$data['target'] = 'tour/payer_select';
		$data['title'] = 'Add to a Past Tour';
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);
		}
		else {
			$this->load->view('page/index', $data);
		}

	}

	function show_payers($tour_id): void {
		$tour = $this->tour->get($tour_id);
		$this->load->model("payer_model", "payer");
		$this->load->model("tourist_model", "tourist");
		$this->load->model("phone_model", "phone");
		$payers = $this->payer->get_payers($tour_id);
		foreach ($payers as $payer) {
			$phones = $this->phone->get_for_person($payer->payer_id);
			$payer->phones = $phones;
			$tourists = $this->tourist->get_by_payer($payer->payer_id, $tour_id);
			$payer->tourists = $tourists;
			$payer->price = get_tour_price($payer);
			$payer->room_rate = get_room_rate($payer);
			$payer->amt_due = get_amount_due($payer);

			$tourist_count = $this->payer->get_tourist_count($payer->payer_id, $payer->tour_id);
			$payer->tourist_count = $tourist_count;
		}
		$data["tour"] = $tour;
		$data["payers"] = $payers;
		$this->load->view("payer/list", $data);
	}

}
