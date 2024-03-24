<?php

defined('BASEPATH') or exit ('No direct script access allowed');

// tourist.php Chris Dart Dec 13, 2013 7:53:43 PM chrisdart@cerebratorium.com
class Tourist extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('tourist_model', 'tourist');
		$this->load->model('payment_model', 'payment');
	}

	function index() {
		$array = [
			'XXXL' => 5,
			'XL' => 0,
			'XXL' => 2,
			'S' => 3,
			'M' => 16,
			'L' => 27,
			'Unknown' => 45,
		];
		$order = [
			'Unknown',
			'S',
			'M',
			'L',
			'XL',
			'XXL',
			'XXXL',
		];
	}

	function view() {}

	function view_all($tour_id): void {
		$this->load->model('tour_model', 'tour');
		$this->load->model('payer_model', 'payer');
		$tour = $this->tour->get($tour_id);
		$data ['tour'] = $tour;
		$data ['title'] = 'Tourist List: ' . $tour->tour_name;
		$data ['target'] = 'tourist/list';
		if ($this->input->get('export')) {
			$this->load->helper('download');
			$this->load->view('tourist/export', $data);
		}
		else {
			$this->load->view('page/index', $data);
		}
	}

	/**
	 * This presents a fork where the user can select the type of tourist: payer
	 * or tourist.
	 */
	function select_tourist_type(): void {
		$data ['id'] = $this->input->get('id');
		$this->load->view('tourist/select_type', $data);
	}

	function view_for_tourist($person_id): void {
		$this->load->model('person_model', 'person');
		$tourist = $this->person->get($person_id);
		$tourist->person_id = $person_id;
		$data ['tourist'] = $tourist;
		$data ['tours'] = $this->tourist->get_by_tourist($person_id);
		$data ['title'] = sprintf('Showing Tours for %s', $tourist->first_name, $tourist->last_name);
		$data ['target'] = 'tourist/tour_list';
		$this->load->view('page/index', $data);
	}

	function export(): void {
		$options = get_cookie('person_filter');
		$options = unserialize($options);
		$options ['include_address'] = TRUE;
		$data ['people'] = $this->person->get_all($options);
		$data ['target'] = 'Person Export';
		$data ['title'] = 'Export of People';
		$this->load->helper('download');
		$this->load->view('person/export', $data);
	}

	function create(): void {
		$data ['tourist'] = NULL;
		$this->load->model('variable_model', 'variable');
		$data['first_name'] = $this->input->get('first_name');
		$data['last_name'] = $this->input->get('last_name');
		$data['tour_id'] = $this->input->get('tour_id');
		$data['payer_id'] = $this->input->get('payer_id');
		$data['action'] = 'insert';
		$shirt_sizes = $this->variable->get_pairs('shirt_size');
		$data ['shirt_sizes'] = get_keyed_pairs($shirt_sizes, [
			'value',
			'name',
		], TRUE);
		$this->load->view('tourist/edit', $data);
	}

	function insert(): void {
		if($this->input->get('ajax') === '1') {
			$json = file_get_contents('php://input');
			$input =
				json_decode($json, TRUE);
			$payer_id = $input['payer_id'];
			$tour_id = $input['tour_id'];
			$person_id = $input['tourist_id'];
		} else {
			$payer_id = $this->input->post('payer_id');
			$tour_id = $this->input->post('tour_id');
			$person_id = $this->input->post('tourist_id');
		}
		$payments = $this->payment->get_all($tour_id, $person_id);
		$this->load->model('payer_model','payer');
		$note = $this->payer->get_value($person_id,$tour_id, 'note');
		if (empty($person_id)) {
			$this->load->model('person_model', 'person');
			$person_id = $this->person->insert();
		}
		$data ['payer_id'] = $payer_id;
		$data ['tour_id'] = $tour_id;
		$data ['person_id'] = $person_id;
		$target = '/payer/edit?payer_id=' . $payer_id . '&tour_id=' . $tour_id;
		$message = [];
		$this->tourist->insert($data);
		if(!empty($payments)){
			$this->load->model('person_model','person');
			$person = $this->person->get($person_id);
			$this->payment->insertPayments($payer_id, $payments);
			$message[] = $person->name . '\'s payments have been merged into this payer\'s record.';
			// delete the payer;
			$this->payer->delete($person_id, $tour_id);
			$message[] = '<br/>' . $person->name . '\'s payer record has been deleted';
			$this->tourist->delete_payer($person_id, $tour_id);
		}
		if(!empty($note)){
			$this->payer->appendNote($payer_id, $tour_id , $note->note);
		}
		if(!empty($message)){
			$this->session->set_flashdata('notice', implode('<br/>', $message));
		}
		if($this->input->get('ajax')=== '1'){
			$output = ['target' => base_url($target)];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else {
			redirect($target);
		}
	}

	/**
	 * Insert a new person and add directly to tour
	 */
	function insert_new(): void {
		$this->load->model('person_model', 'person');
		$person_id = $this->person->insert();
		$payer_id = $this->input->post('payer_id');
		$tour_id = $this->input->post('tour_id');
		$this->db->insert('tourist', ['payer_id' => $payer_id, 'tour_id'=> $tour_id, 'person_id'=> $person_id]);
	}

	function update_value(): void {
		$id = $this->input->post('id');
		$values = [
			$this->input->post('field') => $value = trim($this->input->post('value')),
		];
		$this->tourist->update($id, $values);
		echo $this->input->post('value');
	}

	function find_by_name($tour_id, $payer_id): void {
		$this->load->model('person_model', 'person');
		$this->load->model('payer_model', 'payer');
		$name = $this->input->get('name');
		$data ['payer_id'] = $payer_id;
		$data ['tour_id'] = $tour_id;
		$data ['name'] = $name;
		$target = 'tourist/mini_list';
		$people = $this->person->find_people($name);
		// Filter the results on people not on the tour.
		$this->tourist->remove_existing_tourists($tour_id, $people);
		$data['people'] = $people;
		$this->load->view($target, $data);
	}

	function delete(): void {
		$json = file_get_contents('php://input');
		$input = json_decode($json, TRUE);

		// Access the data like this:
		$tour_id = $input['tour_id'] ?? NULL;
		$person_id = $input['person_id'] ?? NULL;
		$payer_id = $input['payer_id'] ?? NULL;
		$this->load->model('roommate_model', 'roommate');
		$this->roommate->delete_tourist($person_id, $tour_id);
		$this->tourist->delete($person_id, $tour_id);
		$data['target'] = 'tourist/payer_list';
		$data ['tourists'] = $this->tourist->get_for_payer($payer_id, $tour_id);
		if ($input['ajax'] == 1) {
			$output = ['data' => $this->load->view($data['target'], $data, TRUE)];
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($output));
		}
		else {
			$this->load->view('page/index', $data);
		}
	}

} //end class
