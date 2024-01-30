<?php
defined('BASEPATH') or exit('No direct script access allowed');

// phone.php Chris Dart Dec 13, 2013 6:40:43 PM chrisdart@cerebratorium.com
class Phone extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model('phone_model', 'phone');
    }

    function index ()
    {
        $this->view();
    }

    function create ($person_id)
    {
        $data['person_id'] = $person_id;
        $data['phone'] = FALSE;
        $data['action'] = 'insert';
        $data['title'] = 'Add a phone number';
        $data['target'] = 'phone/edit';
        if ($this->input->get('ajax') == 1) {
            $this->load->view($data['target'], $data);
        }
        else {
        	$this->load->view('page/index', $data);
				}
    }

    function edit ($phone_id)
    {
        $person_id = $this->input->get('person_id');
        $phone = $this->phone->get($phone_id);
        $is_primary = $this->phone->get_phone_person($phone_id, array('is_primary'));
        $phone->is_primary = $is_primary->is_primary;
        $data['phone'] = $phone;
        $data['person_id'] = $person_id;
        $data['action'] = 'update';
        $data['title'] = 'Edit a phone number';
        $data['target'] = 'phone/edit';
        if ($this->input->get('ajax') == 1) {
            $this->load->view('phone/edit', $data);
        }
        else {
        	$this->load->view('page/index', $data);
				}
    }

    function insert ()
    {
        $person_id = $this->input->post('person_id');
       $id =  $this->phone->insert_for_person($person_id);
        $is_primary = $this->input->post('is_primary');
        $this->phone->set_primary($id, $is_primary);
        redirect('person/view/' . $person_id);
    }

    function update_value ()
    {
        $id = $this->input->post('id');
        $values = array(
                $this->input->post('field') => $value = trim($this->input->post('value'))
        );
        $this->phone->update($id, $values);
        print $this->input->post('value');
    }

    function update ()
    {
        $id = $this->input->post('phone_id');
        $this->phone->update($id);
        $result = $this->phone->get_phone_person($id, array(
                'person_id'
        ));
        $is_primary = $this->input->post('is_primary');
        $this->phone->set_primary($id, $is_primary);
        redirect('person/view/' . $result->person_id);
    }

    function delete ()
    {
        $id = $this->input->post('id');
        $this->phone->delete($id);
        print TRUE;
    }

}
