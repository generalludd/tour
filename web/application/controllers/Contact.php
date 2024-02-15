<?php
defined('BASEPATH') or exit('No direct script access allowed');

// hotel_contact.php Chris Dart Jan 19, 2014 6:15:39 PM
// chrisdart@cerebratorium.com
class Contact extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('contact_model', 'contact');
		$this->load->model('hotel_model', 'hotel');
	}

	function create($hotel_id): void {
		$data['contact'] = (object) ['hotel_id' => $hotel_id];
		$data['hotel_name'] = $this->hotel->get($hotel_id)->hotel_name;
		$data['action'] = 'insert';
		$data['target'] = 'contact/edit';
		$data['title'] = 'Add a contact to ' . $this->hotel->get($hotel_id)->hotel_name;
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);
		}
		else {
			$this->load->view('page/index', $data);
		}

	}

	function insert(): void {
		$hotel_id = $this->input->post('hotel_id');
		$this->contact->insert();
		redirect('hotel/view/' . $hotel_id);
	}

	function edit($id): void {
		$contact = $this->contact->get($id);
		$data['hotel_id'] = $contact->hotel_id;
		$data['contact'] = $contact;
		$data['action'] = 'update';
		$data['title'] = 'Edit ' . $contact->contact;
		$data['target'] = 'contact/edit';
		if($this->input->get('ajax')){
			$this->load->view('page/modal', $data);
		}
		else{
			$this->load->view('page/index', $data);
		}
	}

	function update(): void {
		$id = $this->input->post('id');
		$this->contact->update($id);
		$hotel_id = $this->input->post('hotel_id');
		redirect('hotel/view/' . $hotel_id);
	}

	function delete(): void {
		if ($this->input->get('contact_id')) {
			$contact_id = $this->input->get('contact_id');
			$contact = $this->contact->get($contact_id);

			$data['identifiers'] = [
				'contact_id' => $contact_id,
				'hotel_id' => $contact->hotel_id,
			];
			$data['entity'] = 'Contact';
			$data['message'] = 'Are you sure you want to delete ' . $contact->contact . '?';
			$data['action'] = 'contact/delete';
			$data['target'] = 'dialogs/delete';
			$data['title'] = 'Delete a contact';
			if ($this->input->get('ajax')) {
				$this->load->view("page/modal", $data);
			}
			else {
				$this->load->view('page/index', $data);
			}
		}
		else {
			$id = $this->input->post('contact_id');
			$hotel_id = $this->input->post('hotel_id');
			$this->contact->delete($id);
			$this->session->set_flashdata('notice', 'The contact was successfully deleted');
			redirect('hotel/view/' . $hotel_id);
		}
	}

}
