<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer.php Chris Dart Dec 13, 2013 7:53:31 PM chrisdart@cerebratorium.com
class Payer extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("payer_model", "payer");
		$this->load->model("tourist_model", "tourist");
		$this->load->model("payment_model", "payment");
	}

	function view() {
	}

	function create() {
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
		$payer = $this->payer->get_for_tour($payer_id, $tour_id);
		$payer->payments = $this->payment->get_all($tour_id, $payer->payer_id);
		$data["payer"] = $payer;
		$data["tourists"] = $this->tourist->get_by_payer($payer_id, $tour_id);
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
	 * @param string $payer_id
	 * @param string $tour_id
	 */
	function edit($payer_id = FALSE, $tour_id = FALSE, $ajax = FALSE) {

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
		$payer = $this->payer->get_for_tour($payer_id, $tour_id);
		$payer->payments = $this->payment->get_all($tour_id, $payer->payer_id);
		$data['amount'] = $this->payment->get_total($tour_id, $payer->payer_id);
		$data["payer"] = $payer;
		$data["tourists"] = $this->tourist->get_by_payer($payer_id, $tour_id);
		$data["room_rate"] = get_room_rate($payer);
		$data["tour_price"] = get_tour_price($payer);
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
	function insert() {
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

	function update() {
		$payer_id = $this->input->post("payer_id");
		$tour_id = $this->input->post("tour_id");
		$this->payer->update($payer_id, $tour_id);
		if ($this->input->post("is_cancelled") == 1) {
			$this->load->model("roommate_model", "roommate");
			//get everyone on the payer's ticket and delete them from the roommate list for the tour.
			$tourists = $this->tourist->get_for_payer($payer_id, $tour_id);
			foreach ($tourists as $tourist) {
				$deletion = ["tour_id" => $tour_id, "person_id" => $tourist->person_id];
				$this->roommate->delete($deletion);
			}
		}
		redirect("/tourist/view_all/$tour_id");
	}

	function update_value() {
		$id = $this->input->post("id");
		$values = [
			$this->input->post("field") => $value = trim($this->input->post("value")),
		];
		$this->payer->update($id, $values);
		echo $this->input->post("value");
	}

	function select_tourists() {
		$data["action"] = "select_tourist";
		$data["tour_id"] = $this->input->get("tour_id");
		$data["payer_id"] = $this->input->get("payer_id");
		$this->load->view("tourist/mini_selector", $data);
	}

	function select_payer($tour_id, $person_id) {
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

	function delete() {
		$payer_id = $this->input->post("payer_id");
		$tour_id = $this->input->post("tour_id");
		$this->payer->delete($payer_id, $tour_id);
		$this->tourist->delete_payer($payer_id, $tour_id);
		$this->load->model("roommate_model", "roommate");
		$this->roommate->delete_payer($payer_id, $tour_id);
	}

}
