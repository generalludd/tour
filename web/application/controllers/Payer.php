<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer.php Chris Dart Dec 13, 2013 7:53:31 PM chrisdart@cerebratorium.com
class Payer extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('payer_model', 'payer');
		$this->load->model('tourist_model', 'tourist');
		$this->load->model('payment_model', 'payment');
		$this->load->model('tour_model', 'tour');
		$this->load->model('person_model', 'person');
	}

	function view($payer_id = FALSE, $tour_id = FALSE, $ajax = FALSE): void {
		$this->edit($payer_id, $tour_id, $ajax);
	}

	function create(): void {
		$this->load->model("variable_model", "variable");
		$payer_id = $this->input->get("payer_id");
		$data["payer_id"] = $payer_id;
		$tour_id = $this->input->get("tour_id");
		$data["tour_id"] = $tour_id;
		// create a new records in payer and tourist that will be loaded here.
		// This allows the creation
		// of at least one tourist record as well.
		$this->payer->insert($payer_id, $tour_id);

		// create the intial payer record for this payer-as-tourist
		$this->tourist->insert_payer($payer_id, $tour_id);
		$data["room_sizes"] = get_keyed_pairs($this->variable->get_pairs("room_size"), [
			"value",
			"name",
		]);
		$data["payment_types"] = get_keyed_pairs($this->variable->get_pairs("payment_type"), [
			"value",
			"name",
		]);
		$payer = $this->payer->getForTour($payer_id, $tour_id);
		$payer->payments = $this->payment->get_all($tour_id, $payer->payer_id);
		$data["payer"] = $payer;
		$data["tourists"] = $this->tourist->get_for_payer($payer_id, $tour_id);
		$data["room_rate"] = 0;
		$data["tour_price"] = 0;
		$data["target"] = "payer/edit";
		$data["title"] = "Creating Payer";
		$data["action"] = "update";
		$this->load->view("page/index", $data);
	}

	/**
	 * This script can be run via url or called internally such as when it is
	 * called in $this->insert() function
	 *
	 * @param string|null $payer_id
	 * @param string|null $tour_id
	 * @param bool $ajax
	 */
	function edit(string $payer_id = NULL, string $tour_id = NULL, bool $ajax = FALSE): void {

		$this->load->model("variable_model", "variable");
		if (empty($payer_id)) {
			$payer_id = (int) $this->input->get("payer_id");
		}

		$data["payer_id"] = $payer_id;

		if (empty($tour_id)) {
			$tour_id = $this->input->get("tour_id");
		}
		$data["tour_id"] = $tour_id;
		$data["room_sizes"] = get_keyed_pairs($this->variable->get_pairs("room_size"), [
			"value",
			"name",
		]);
		$this->load->model('tour_model','tour');
		$data['payment_types'] = $this->tour->get_payment_types($tour_id);
		$payer = $this->payer->getForTour($payer_id, $tour_id);
		$data["payer"] = $payer;

		$data["target"] = "payer/edit";
		$data["title"] = "Editing Payer";
		$data["action"] = "update";
		if($ajax){
			$this->load->view('page/modal',$data);
		}
		else{
			$this->load->view("page/index", $data);
		}
	}

	/**
	 * there is no alternative if this is called without the ajax=1 post query
	 * variable.
	 */
	function insert(): void {
		$payer_id = $this->input->post("payer_id");
		$tour_id = $this->input->post("tour_id");
		$this->payer->insert($payer_id, $tour_id);
		$this->tourist->insert([
			"payer_id" => $payer_id,
			"tour_id" => $tour_id,
			"person_id" => $payer_id,
		]);
		$this->edit($payer_id, $tour_id, $this->input->post('ajax'));
	}

	function update(): void {
		$payer_id = $this->input->post('payer_id');
		$tour_id = $this->input->post('tour_id');
		$this->payer->update($payer_id, $tour_id);
		$this->deleteRoommates($payer_id, $tour_id);
		redirect('/tourist/view_all/' .$tour_id);
	}

	function update_value(int $payer_id, int $tour_id): void {
		$value = $this->input->post("value");
		$field = $this->input->post("field");
		if($field === 'is_cancelled'){
			$this->deleteRoommates($payer_id, $tour_id);
		}
		$this->payer->updateValue($payer_id, $tour_id, $field, $value);
	}

	function select_tourists(): void {
		$data["action"] = "select_tourist";
		$data["tour_id"] = $this->input->get("tour_id");
		$data["payer_id"] = $this->input->get("payer_id");
		$this->load->view("tourist/mini_selector", $data);
	}

	function select_payer($tour_id, $person_id): void {
		$this->load->model("person_model", "person");
		$this->load->model("tour_model", "tour");
		$data["tour_name"] = $this->tour->get_value($tour_id, "tour_name");
		$data["tour_id"] = $tour_id;
		$tourist = $this->person->get($person_id, "first_name,last_name");
		$data["tourist_name"] = sprintf("%s %s", $tourist->first_name, $tourist->last_name);
		$data["tourist_id"] = $person_id;
		$data["payers"] = $this->payer->get_payers($tour_id);
		$data['target'] = 'payer/select_list';
		$data['title'] = 'Select a Payer';
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);

		}
		else {
			$this->load->view('page/index', $data);
		}
	}

	function delete(): void {
		if( $this->input->get('tour_id') &&  $this->input->get('payer_id')) {
			$tour_id = $this->input->get('tour_id');
			$payer_id = $this->input->get('payer_id');
			$tour = $this->tour->get($tour_id);
			$payer = $this->person->get($payer_id);
			$data['identifiers'] = [
				'tour_id' => $tour_id,
				'payer_id' => $payer_id,
			];
			$data['title'] = 'Payment by ' . $payer->first_name . ' ' . $payer->last_name . ' for ' . $tour->tour_name;
			$data['action'] ='payer/delete';
			$data['message'] = 'This is only possible for someone who was accidentally added to a tour and has no rooms or payments associated with their tour. Use "Cancelled" if they already have paid money.';
			$data['target'] = 'dialogs/delete';
			if($this->input->get('ajax')){
				$this->load->view('page/modal', $data);
			}
			else {
				$this->load->view('page/index', $data);
			}
		}
		else {
			$payer_id = $this->input->post('payer_id');
			$tour_id = $this->input->post('tour_id');
			$this->payer->delete($payer_id, $tour_id);
			$this->tourist->delete_payer($payer_id, $tour_id);
			$this->load->model('roommate_model', 'roommate');
			$this->roommate->delete_payer($payer_id, $tour_id);
			redirect('tours/view/' . $tour_id);
		}

	}

	/**
	 * @param $payer_id
	 * @param $tour_id
	 *
	 * @return void
	 */
	public function deleteRoommates($payer_id, $tour_id): void {
		if ($this->input->post('is_cancelled') == 1) {
			$this->load->model('roommate_model', 'roommate');
			//get everyone on the payer's ticket and delete them from the roommate list for the tour.
			$this->roommate->delete_payer($payer_id, $tour_id);
			$this->session->set_flashdata('alert', 'This reservation has been cancelled. All roommate entries have been deleted from all hotel stays');
		}
	}

}
