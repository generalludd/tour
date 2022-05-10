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

	function create($hotel_id) {
		$data['contact'] = (object) ['hotel_id' => $hotel_id];
		$data['hotel_name'] = $this->hotel->get($hotel_id)->hotel_name;
		$data['action'] = 'insert';
		$data['target'] = 'contact/edit';
		$data['title'] = 'Add a contact to ' . $this->hotel->get($hotel_id)->hotel_name;
		if ($this->input->get('ajax')) {
			$this->load->view($data['target'], $data);
		}
		else {
			$this->load->view('page/index', $data);
		}

	}

	function insert() {
		$hotel_id = $this->input->post('hotel_id');
		$this->contact->insert();
		redirect('hotel/view/' . $hotel_id);
	}

	function edit($id) {
		$contact = $this->contact->get($id);
		$data['hotel_id'] = $contact->hotel_id;
		$data['contact'] = $contact;
		$data['action'] = 'update';
		$this->load->view('contact/edit', $data);
	}

	function update() {
		$id = $this->input->post('id');
		$this->contact->update($id);
		$hotel_id = $this->input->post('hotel_id');
		redirect('hotel/view/' . $hotel_id);
	}

	function delete() {
		if ($this->input->get('contact_id')) {
			$data['identifiers'] = [
				'contact_id' => $this->input->get('contact_id'),
				'hotel_id' => $this->input->get('hotel_id'),
			];
			$data['entity'] = 'Contact';
			$data['message'] = 'Are you sure you want to delete this contact?';
			$data['action'] = 'contact/delete';
			$data['target'] = 'dialogs/delete';
			$data['title'] = 'Delete a contact';
			if ($this->input->get('ajax')) {
				$this->load->view($data['target'], $data);
			}
			else {
				$this->load->view('page/index', $data);
			}
		}
		else {
			$id = $this->input->post('contact_id');
			$hotel_id = $this->input->post('hotel_id');
			$this->contact->delete($id);
			$this->session->set_flashdata('alert', 'The contact was successfully deleted');
			redirect('hotel/view/' . $hotel_id);
		}
	}

}
