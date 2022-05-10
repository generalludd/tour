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

	function view() {
	}

	function view_all($tour_id) {
		$export = FALSE;
		if ($this->input->get('export')) {
			$export = TRUE;
		}

		$options = [];
		if ($export) {
			$data ['target'] = 'tourist/export';
			$options ['include_address'] = TRUE;
			$this->load->helper('download');
		}
		$this->load->model('payment_model', 'payments');
		$this->load->model('tour_model', 'tour');
		$this->load->model('payer_model', 'payer');
		$this->load->model('phone_model', 'phone');
		$tour = $this->tour->get($tour_id);
		$payers = $this->payer->get_payers($tour_id, $options);
		foreach ($payers as $payer) {
			$phones = $this->phone->get_for_person($payer->payer_id);
			$payer->phones = $phones;
			$tourists = $this->tourist->get_for_payer($payer->payer_id, $tour_id);
			$payer->tourists = $tourists;
			$payer->payments = $this->payment->get_all($tour_id, $payer->payer_id);
			switch ($payer->payment_type) {
				case 'full_price' :
					$price = $tour->full_price;
					break;
				case 'banquet_price' :
					$price = $tour->banquet_price;
					break;
				case 'early_price' :
					$price = $tour->early_price;
					break;
				case 'regular_price' :
					$price = $tour->regular_price;
					break;
				default :
					$price = 0;
					break;
			}
			if ($price == 0) {
				$rate = 0;
			}
			else {
				switch ($payer->room_size) {
					case 'single_room' :
						$rate = $tour->single_room;
						break;
					case 'triple_room' :
						$rate = $tour->triple_room;
						break;
					case 'quad_room' :
						$rate = $tour->quad_room;
						break;
					default :
						$rate = 0;
						break;
				}
			}
			if ($payer->is_comp == 1 || $payer->is_cancelled) {
				$price = 0;
				$rate = 0;
			}
			$payer->price = $price;
			$payer->room_rate = $rate;
			$tourist_count = $this->payer->get_tourist_count($payer->payer_id, $payer->tour_id);
			$payments = $this->payments->get_total($tour_id, $payer->payer_id);
			$payer->amt_paid = $payments;
			$payer->amt_due = ($price + $rate) * $tourist_count - ($payer->amt_paid + $payer->discount);

			$payer->tourist_count = $tourist_count;
		}
		$data ['tour'] = $tour;
		$data ['payers'] = $payers;
		$data ['title'] = 'Tourist List: ' .  $tour->tour_name;
		if ($export) {
			$this->load->view($data ['target'], $data);
		}
		else {
			$data ['target'] = 'tourist/list';
			$this->load->view('page/index', $data);
		}
	}

	/**
	 * This presents a fork where the user can select the type of tourist: payer
	 * or tourist.
	 */
	function select_tourist_type() {
		$data ['id'] = $this->input->get('id');
		$this->load->view('tourist/select_type', $data);
	}

	function view_for_tourist($person_id) {
		$this->load->model('person_model', 'person');
		$tourist = $this->person->get($person_id);
		$tourist->person_id = $person_id;
		$data ['tourist'] = $tourist;
		$data ['tours'] = $this->tourist->get_by_tourist($person_id);
		$data ['title'] = sprintf('Showing Tours for %s', $tourist->first_name, $tourist->last_name);
		$data ['target'] = 'tourist/tour_list';
		$this->load->view('page/index', $data);
	}

	function export() {
		$options = get_cookie('person_filter');
		$options = unserialize($options);
		$options ['include_address'] = TRUE;
		$data ['people'] = $this->person->get_all($options);
		$data ['target'] = 'Person Export';
		$data ['title'] = 'Export of People';
		$this->load->helper('download');
		$this->load->view('person/export', $data);
	}

	function create() {
		$data ['action'] = 'insert';
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

	function insert() {
		$payer_id = $this->input->post('payer_id');
		$tour_id = $this->input->post('tour_id');
		$person_id = $this->input->post('person_id');
		if(empty($person_id)){
			$this->load->model('person_model', 'person');
			$person_id = $this->person->insert();
		}
		$data ['payer_id'] = $payer_id;
		$data ['tour_id'] = $tour_id;
		$data ['person_id'] = $person_id;
		$target = '/payer/edit?payer_id=' . $payer_id . '&tour_id=' . $tour_id;
		$this->tourist->insert($data);
		redirect($target);

	}

	/**
	 * Insert a new person and add directly to tour
	 */
	function insert_new() {
		$this->load->model('person_model', 'person');
		$person_id = $this->person->insert();
		$payer_id = $this->input->post('payer_id');
		$tour_id = $this->input->post('tour_id');
		$this->insert($payer_id, $tour_id, $person_id);
	}

	function update() {
	}

	function update_value() {
		$id = $this->input->post('id');
		$values = [
			$this->input->post('field') => $value = trim($this->input->post('value')),
		];
		$this->tourist->update($id, $values);
		echo $this->input->post('value');
	}

	function find_by_name($tour_id, $payer_id) {
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

	function delete() {
		$tour_id = $this->input->post('tour_id');
		$person_id = $this->input->post('person_id');
		$payer_id = $this->input->post('payer_id');
		$this->load->model('roommate_model', 'roommate');
		$this->roommate->delete_tourist($person_id, $tour_id);
		$this->tourist->delete($person_id, $tour_id);
		$data['target'] = 'tourist/payer_list';
		$data ['tourists'] = $this->tourist->get_by_payer($payer_id, $tour_id);
		if ($this->input->post('ajax') == 1) {
			$this->load->view($data['target'], $data);
		}
		else {
			$this->load->view('page/index', $data);
		}
	}

} //end class
