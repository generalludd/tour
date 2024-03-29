<?php
defined('BASEPATH') or exit('No direct script access allowed');

// address.php Chris Dart Dec 11, 2013 9:15:31 PM chrisdart@cerebratorium.com
class Address extends My_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("address_model", "address");
	}

	/**
	 * expects a person id.
	 *
	 * @TODO got to create a messaging system if an error occurs
	 * that works well regardless of the way a script completes
	 */
	function create(): void {
		if ($this->input->get("person_id")) {
			$data["person_id"] = $this->input->get("person_id");
			$data["address"] = FALSE;
			$data["target"] = "address/edit";
			$data["title"] = "Adding an Address";
			$data["action"] = "insert";
			if ($this->input->get("ajax") == 1) {
				$this->load->view("page/modal", $data);
			}
			else {
				$this->load->view("page/index", $data);
			}
		}
	}

	/**
	 * expects a person_id in the get.
	 */
	function insert(): void {
		if ($this->input->post("person_id")) {
			$person_id = $this->input->post("person_id");
			$address_id = $this->address->insert();
			$this->load->model("person_model", "person");
			$values = [
				"address_id" => $address_id,
			];
			$this->person->update($person_id, $values);
			redirect("person/view/$person_id");
		}
	}

	/**
	 * requires address_id and person_id to function;
	 */
	function edit($address_id, $person_id): void {
		$data['person_id'] = $person_id;
		$data['address'] = $this->address->get($address_id);
		$data['action'] = 'update';
		$data['target'] = 'address/edit';
		$data['title'] = 'Editing an Address';
		if ($this->input->get('ajax') == 1) {
			$this->load->view('page/modal', $data);
		}
		else {

			$this->load->view('page/index', $data);
		}
	}

	/**
	 * expects an input id (address id).
	 * redirects to the person
	 * whose address is being edited.
	 */
	function update(): void {
		if ($this->input->post("id")) {
			$id = $this->input->post("id");
			$person_id = $this->input->post("person_id");
			$this->address->update($id);
			redirect("person/view/$person_id");
		}
	}

	function find_housemate(): void {
		$data["person_id"] = $this->input->get("person_id");
		$data['target'] = "address/find_housemate";
		if ($this->input->get("ajax") == 1) {
			$this->load->view($data['target'], $data);
		}
		else {
			$this->load->view('page/index', $data);
		}
	}

	/**
	 * requres an id from the post variable (address_id)
	 * used mostly by ajax scripts, this allows updating individual values.
	 * not currently in use.
	 */
	function update_value(): void {
		if ($this->input->post("id")) {
			$id = $this->input->post("id");
			$values = [
				$this->input->post("field") => $value = trim($this->input->post("value")),
			];
			$this->address->update($id, $values);
			echo $this->input->post("value");
		}
	}

	function update_salutations(): void {
		$this->load->model("person_model", "person");
		$this->address->update_salutations();
		redirect();
	}

	function export(): void {
		$options = $this->input->cookie("person_filters");
		$options = unserialize($options);
		$data["addresses"] = $this->address->get_for_labels($options);
		$data['target'] = 'Address Export';
		$data['title'] = "Export of Addresses";
		$this->load->helper('download');
		$this->load->view('address/export', $data);
	}

}
