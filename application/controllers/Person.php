<?php
defined('BASEPATH') or exit('No direct script access allowed');

// index.php Chris Dart Dec 10, 2013 8:14:38 PM chrisdart@cerebratorium.com

/**
 * Class Person
 */
class Person extends MY_Controller
{

	/**
	 * Person constructor.
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('person_model', 'person');
		$this->load->model('phone_model', 'phone');
		$this->load->model('address_model', 'address');
	}

	/**
	 *
	 */
	function index()
	{

		$this->view_all();
	}

	/**
	 * @param $id
	 */
	function view($id)
	{
		$data = [];
		$person = $this->person->get($id);
		$data['id'] = $id;
		$data['person'] = $person;
		$data['title'] = $person->first_name . ' ' . $person->last_name;
		$data['target'] = 'person/view';
		$data['ajax'] = FALSE;
		$target = 'page/index';
		if ($this->input->get('ajax')) {
			$data['ajax'] = TRUE;
			$target = $data['target'];
		}
		$this->load->view($target, $data);
	}

	/**
	 *
	 */
	function view_next()
	{
		$id = $this->uri->segment(3);
		$next_id = $this->person->get_next_person($id);
		redirect('person/view/$next_id');
	}

	/**
	 *
	 */
	function view_previous()
	{
		$id = $this->uri->segment(3);
		$next_id = $this->person->get_previous_person($id);
		redirect('person/view/$next_id');
	}

	/**
	 * @param array $options
	 */
	function view_all($options = [])
	{
		burn_cookie('person_filter');
		$filters = [];
		$initial = FALSE;
		if ($this->input->get('initial')) {
			$initial = $this->input->get('initial');
			$filters['initial'] = $initial;
		}
		if ($this->input->get('veterans_only')) {
			$filters['veterans_only'] = TRUE;
		}
		if ($this->input->get('non_veterans')) {
			$filters['non_veterans'] = TRUE;
		}
		if ($this->input->get('email_only')) {
			$filters['email_only'] = TRUE;
		}
		if ($this->input->get('show_disabled')) {
			$filters['show_disabled'] = TRUE;
		}
		if ($this->input->get('order_by')) {
			$filters['order_by'] = $this->input->get('order_by');
		}
		if ($this->input->get('has_shirtsize')) {
			$filters['has_shirtsize'] = $this->input->get('has_shirtsize');
		}
		// get the list of letters for each of the first initials of last names
		// in the people table
		$data['initials'] = $this->person->get_initials();
		$data['people'] = $this->person->get_all($filters);
		$data['address_count'] = count($this->address->get_all($filters));
		bake_cookie('person_filters', $filters);
		$data['filters'] = $filters;
		$data['title'] = 'Address Book';
		$data['target'] = 'person/list';

		$this->load->view('page/index', $data);
	}

	/**
	 *
	 */
	function find_by_name()
	{
		$name = $this->input->get('name');
		$target = 'person/mini_list';
		$data['people'] = $this->person->find_people($name, [
			'has_address' => FALSE,
		]);
		$this->load->view($target, $data);
	}

	/**
	 *
	 */
	function find_for_address($person_id)
	{
		$name = $this->input->get('name');
		$data['people'] = $this->person->find_people($name, [
			'has_address' => TRUE,
		]);
		$data['person_id'] = $person_id;
		$target = 'address/mini_list';
		$this->load->view($target, $data);
	}

	/**
	 * @param int $person_id
	 * @param int $address_id
	 */
	function remove_address(int $person_id, int $address_id)
	{
		//if this is a post request
		if ($this->input->post('person_id') == $person_id && $this->input->post('address_id') == $address_id && $this->input->post('delete')) {
			//actually delete the address relationship.
			$housemates = $this->person->get_housemates($address_id, $person_id);
			$person = $this->person->get($person_id);
			//remove the address link from the person's record.
			$this->person->update($person_id, ['address_id' => NULL]);
			$message[] = sprintf('Removed the address %s from %s %s\'s record.', format_address($this->address->get($address_id), 'inline'), $person->first_name, $person->last_name);
			if (empty($housemates)) {
				//only one person at this address, delete the address.
				$this->address->delete($address_id);
				$message[] = sprintf('The address has also been deleted from the database since %s %s was the only person at the address', $person->first_name, $person->last_name);
			} else {
				foreach ($housemates as $housemate) {
					$names[] = $housemate->first_name . ' ' . $housemate->last_name;
				}
				$message[] = sprintf('The address was not deleted from the database because it is still in use by %s', implode(', ', $names));
			}
			$this->session->set_flashdata('notice', implode(' ', $message));
			redirect('person/view/' . $person_id);
		} else {
			//show the delete dialog
			$person = $this->person->get($person_id);
			$address = $this->address->get($address_id);
			$data = [
				'person' => $person,
				'address' => $address,
				'title' => 'Remove person from address',
				'target' => 'address/remove',
			];
			if ($this->input->get('ajax')) {
				$this->load->view('page/modal', $data);
			} else {
				$this->load->view('page/index', $data);
			}
		}
	}

	/**
	 *
	 */
	function edit()
	{
		$id = $this->uri->segment(3);
		$data = [];
		$data['person'] = $this->person->get($id);
		$data['title'] = sprintf('Person Record: %s %s', $data['person']->first_name, $data['person']->last_name);
		$this->load->model('variable_model', 'variable');
		$shirt_sizes = $this->variable->get_pairs('shirt_size');
		$data['shirt_sizes'] = get_keyed_pairs($shirt_sizes, [
			'value',
			'name',
		], TRUE);
		// we get the tour count because if a person has not been on any tours, they can be deleted, otherwise they can only be marked as inactive.
		$this->load->model('tourist_model', 'tourist');
		$data['tour_count'] = count($this->tourist->get($id));
		$data['target'] = 'person/edit';
		$data['action'] = 'update';
		if ($this->input->get('ajax') == 1) {
			$this->load->view($data['target'], $data);
		} else {
			$this->load->view('page/index', $data);
		}
	}

	/**
	 *
	 */
	function create()
	{
		// create a record in the db and get the insertion id. Then go to the
		// edit user page with
		$data['person'] = FALSE;
		$data['tour_count'] = 0;
		$this->load->model('variable_model', 'variable');
		$shirt_sizes = $this->variable->get_pairs('shirt_size');
		$data['shirt_sizes'] = get_keyed_pairs($shirt_sizes, [
			'value',
			'name',
		], TRUE);
		$data['action'] = 'insert';
		$data['target'] = 'person/edit';
		$data['title'] = 'Add a new person to the person list';
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);
		} else {
			$this->load->view('page/index', $data);
		}
	}

	/**
	 * @param $address_id
	 */
	function add_housemate($address_id)
	{
		$data['person'] = (object) [];
		$data['person']->address_id = $address_id;
		$this->load->model('variable_model', 'variable');
		$shirt_sizes = $this->variable->get_pairs('shirt_size');
		$data['shirt_sizes'] = get_keyed_pairs($shirt_sizes, [
			'value',
			'name',
		], TRUE);
		$data['action'] = 'insert';
		$data['target'] = 'person/edit';
		$data['title'] = 'Add a housemate';
		if ($this->input->get('ajax')) {
			$this->load->view('person/edit', $data);
		} else {
			$this->load->view('page/index', $data);
		}
	}


	/**
	 *
	 */
	function insert()
	{
		$person_id = $this->person->insert(FALSE);
		redirect('person/view/$person_id');
	}

	/**
	 *
	 */
	function update()
	{
		$id = $this->input->post('id');
		$this->person->update($id);
		redirect('person/view/$id');
	}

	/**
	 *
	 */
	function update_value()
	{
		$id = $this->input->post('id');
		$values = [
			$this->input->post('field') => trim($this->input->post('value')),
		];
		$this->person->update($id, $values);
		if (!empty($target = $this->input->post('target'))) {
			$person = $this->person->get($id);
			if ($this->input->post('ajax')) {
				echo $this->load->view($target, ['person' => $person], TRUE);
			} else {
				$this->load->view('page/index', ['person' => $person, 'target' => $target]);
			}
		}
	}

	/**
	 *
	 */
	function show_filter()
	{
		$data['initials'] = get_keyed_pairs($this->person->get_initials(), [
			'initial',
			'initial',
		], TRUE);
		$data['shirtsize_choice'] = [
			'-' => '-None-',
			'1'=> 'Yes',
			'0' => 'No',
		];
		$data['order_by_options'] = [
			NULL => '- No Sort - ',
			'person.email-ASC' => 'Email (A-Z)',
			'person.email-DESC' => 'Email (Z-A)',
			'person.shirt_size-DESC' => 'Shirt Size',
		];
		$this->load->view('person/filter', $data);
	}

	/**
	 *
	 */
	function export()
	{
		$options = $this->input->cookie('person_filters');
		$options = unserialize($options);
		$this->export_addresses($options);
	}

	/**
	 * @param null $options
	 */
	function export_addresses($options = NULL)
	{
		$options['export'] = TRUE;
		$this->load->model('address_model', 'address');
		$data['addresses'] = $this->address->get_all($options);
		$data['target'] = 'Address Export';
		$data['title'] = 'Export of Addresses';
		$this->load->helper('download');
		$this->load->view('address/export', $data);
	}

	/**
	 * generate and export a vcard for the given person
	 *
	 * @param $id
	 */
	function vcard($id)
	{
		$person = $this->person->get($id);
		$phones = $this->phone->get_for_person($id);
		$person->phones = $phones;
		$person->address = $this->address->get($person->address_id);
		$data['person'] = $person;
		$this->load->helper('download');
		$vcard = $this->load->view('address/vcard', $data, TRUE);
		$date_stamp = date('Y-m-d_H-i-s');
		$file_name = sprintf('%s-%s_%s.vcf', $person->first_name, $person->last_name, $date_stamp);
		force_download($file_name, $vcard, 'text/x-vcard');
	}

	/**
	 * the person_model determines whether to delete a person or just disable
	 * them.
	 * If a person is in the tourist database they will only be disabled.
	 * See person_model->delete for more details.
	 */
	function delete($disable = FALSE)
	{
		$id = $this->input->post('id');
		$person = $this->person->get($id);
		if ($id) {
			$this->person->delete($id);
		}
		if ($disable) {
			$this->session->set_flashdata('notice', sprintf('%s\'s record has been disabled. It could not be deleted because is connected to at least one tour.', person_link($person)));
		} else {
			$this->session->set_flashdata('notice', sprintf('%s %s, their phone numbers and other information have been completely from the database because they have never been on a tour.', $person->first_name, $person->last_name));
		}
		redirect('person/view_all');
	}

	/**
	 *
	 */
	function disable()
	{
		$this->delete(TRUE);
	}

	/**
	 *
	 */
	function restore()
	{
		$id = $this->input->post('id');
		if ($id) {
			$this->person->restore($id);
		}
	}
}
