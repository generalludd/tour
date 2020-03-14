<?php
defined('BASEPATH') or exit('No direct script access allowed');

// hotel_contact.php Chris Dart Jan 19, 2014 6:15:39 PM
// chrisdart@cerebratorium.com
class Contact extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model('contact_model', 'contact');
        $this->load->model('hotel_model', 'hotel');
    }

    function create ($hotel_id)
    {
        $data['contact'] = NULL;
        $data['hotel_name'] = $this->hotel->get($hotel_id)->hotel_name;
        $data['hotel_id'] = $hotel_id;
        $data['action'] = 'insert';
        $data['target'] = 'contact/edit';
        $data['title'] = 'Add a contact to ' . $this->hotel->get($hotel_id)->hotel_name;
        if($this->input->get('ajax')){
					$this->load->view($data['target'], $data);
				}
        else{
					$this->load->view('page/index', $data);
				}

    }

    function insert ()
    {
        $hotel_id = $this->input->post('hotel_id');
        $this->contact->insert();
        redirect('hotel/view/$hotel_id');
    }

    function edit ()
    {
        $id = $this->input->get('id');

        $contact = $this->contact->get($id);
        $data['hotel_id'] = $contact->hotel_id;
        $data['contact'] = $contact;
        $data['hotel_name'] = $contact->hotel_name;
        $data['action'] = 'update';
        $this->load->view('contact/edit', $data);
    }

    function update ()
    {
        $id = $this->input->post('id');
        $this->contact->update($id);
        $hotel_id = $this->input->post('hotel_id');
        redirect('hotel/view/$hotel_id');
    }

    function delete ()
    {
        $id = $this->input->post('id');
        $hotel_id = $this->input->post('hotel_id');
        $this->contact->delete($id);
        redirect('hotel/view/$hotel_id');
    }
}
