<?php

defined('BASEPATH') or exit('No direct script access allowed');

// person.php Chris Dart Dec 10, 2013 8:15:47 PM chrisdart@cerebratorium.com
class Person_model extends MY_Model {

	var $first_name;

	var $last_name;

	var $email;

	var $shirt_size;

	var $address_id;

	var $note;

	var $status = 1;

	var $is_veteran;

	function prepare_variables(): void {
		$variables = [
			'first_name',
			'last_name',
			'email',
			'shirt_size',
			'is_veteran',
			'address_id',
			'note',
		];
		prepare_variables($this, $variables);
	}

	function get($id, $fields = FALSE): ?object {
		$this->db->where('person.id', $id)
		->from('person');
		if ($fields) {
			$this->db->select($fields);
		}


		$person = $this->db->get()->row();
		if(empty($person)){
			return null;
		}
		$this->load->model('phone_model', 'phone');
		$person->phones = $this->phone->get_for_person($id);
		if (!empty($person->address_id)) {
			$this->load->model('address_model', 'address');
			$person->address = $this->address->get($person->address_id);
			$person->housemates = $this->get_housemates($person->address_id, $person->id);
		}
		else {
			$person->address = NULL;
		}
		$person->name = $person->first_name . ' ' . $person->last_name;

		return $person;
	}

	/**
	 *
	 * @param array $options
	 *
	 * @return array of objects
	 *
	 *         $options can contain:
	 *         initial (alpha character as in the initial letter of last names
	 *         to return a filtered list on last name)
	 *         veterans (boolean: true = selects only people who have been on a
	 *         tour).
	 *         tour_id (selects only people on a give tour_id);
	 *         email (boolean: true = only contacts with emails)
	 *
	 *
	 */
	function get_all(array $options = []): array {
		$query = $this->db->select('person.*');
		$query->order_by('person.last_name')
			->order_by('person.first_name');
		$include_address = FALSE;
		if(!empty($options['veterans'])){
			if($options['veterans'] == 1) {
				$query->where('is_veteran', 1);
			}elseif($options['veterans'] == -1) {
			  $query->where('is_veteran', NULL, FALSE);
			}
		}
		// Show only active users by default.
		if (empty($options['show_disabled'])) {
			$query->where('status', 1);
		}

		if (array_key_exists('initial', $options) && $options['initial']) {
			$initial = $options['initial'];
			$query->like('last_name', $initial, 'after');
		}
		if (!empty($options['email_only'])) {
			$query->where('email !=', NULL);
			$query->select('person.first_name, person.last_name, person.email,person.id,person.status,person.is_veteran');
		}
		if (array_key_exists('include_address', $options)) {
			$include_address = TRUE;
		}
		if (!empty($options['has_shirtsize'])) {
			if ($options['has_shirtsize'] === 1) {
				$query->where('shirtsize !=', NULL);
			}
			else {
				$query->where_null('shirtsize');
			}
		}

		$query->from('person');

		if (!empty($options['order_by'])) {
			[$field, $direction] = $values = explode('-', $options['order_by']);
			$query->order_by($field, $direction);
		}
		$query->group_by('person.id');
		return $query->get()->result();
	}

	function insert($include_address = FALSE) {
		$this->prepare_variables();
		$this->db->insert('person', $this);
		$id = $this->db->insert_id();
		if ($include_address) {
			$this->load->model('address_model');
			$this->address_model->insert_for_user($id);

			$this->load->model('phone_model');
			$this->phone_model->insert_for_user($id);
		}
		return $id;
	}

	function update($id, $values = []) {
		$this->db->where('id', $id);
		if (empty($values)) {
			$this->prepare_variables();
			$this->db->update('person', $this);
		}
		else {
			$this->db->update('person', $values);
			if ($values == 1) {
				$keys = array_keys($values);
				return $this->get_value($id, $keys[0]);
			}
		}
	}

	function find_people($name, $options = []): array {
		$query = $this->db->from('person');
		$query->order_by('last_name', 'ASC')
			->where('status', 1)
			->order_by('first_name', 'ASC');
		$names = explode(' ', $name);
		if (count($names) == 1) {
			$query->like('first_name', $names[0])
				->or_like('last_name', $names[0]);
		}
		elseif (count($names) == 2) {
			$query->like('first_name', $names[0])
				->like('last_name', $names[1]);
		}
		else {
			return [];
		}

		if (array_key_exists('select', $options)) {
			$query->select($options['select']);
			$query->select('status');
		}
		else {
			$query->select('person.*');
		}
		if (array_key_exists('has_address', $options)) {
			$query->join('address', 'person.address_id = address.id');
			$query->where('address.id IS NOT NULL', NULL, FALSE);
		}
		$results = $query->get()->result();
		$output = [];
		// This step produces an associative array of person.id => person objects.
		foreach ($results as $result) {
			if ($result->status == 1) {
				$output[$result->id] = $result;
			}
		}
		return $output;
	}

	function get_housemates($address_id, $person_id) {
		$this->db->where('person.address_id', $address_id);
		$this->db->where('person.id !=', $person_id);
		$this->db->where('status', 1); // only show non-disabled entries
		$this->db->order_by('person.last_name, person.first_name');
		$this->db->from('person');
		$result = $this->db->get()->result();
		return $result;
	}

	/**
	 * get all the residents for a given address.
	 *
	 * @param int $address_id
	 *
	 * @return array of objects
	 */
	function get_residents($address_id): array {
		$this->db->from('person');
		$this->db->where('address_id', $address_id);
		return $this->db->get()->result();
	}

	/**
	 * get the row number of the current record to view the next or previous
	 * record
	 *
	 * @param int $id
	 */
	function get_row($id) {
		$result = $this->db->query(
			'SELECT row  FROM  (SELECT @rownum:=@rownum+1 row, a.*
        FROM person a, (SELECT @rownum:=0) r
        ORDER BY last_name, first_name, id) as article_with_rows
        WHERE id = $id')->row();
		return $result->row;
	}

	function get_next_person($id) {
		$row = $this->get_row($id);
		if ($row == $this->db->count_all('person')) {
			$output = $id;
		}
		else {
			$query = ('SELECT `id` FROM `person` ORDER BY `last_name`,`first_name`, `id` LIMIT $row, 1');
			$result = $this->db->query($query)->row();
			$output = $result->id;
		}
		return $output;
	}

	function get_previous_person($id) {
		$row = $this->get_row($id);

		if ($row == 1) {
			$output = $id;
		}
		else {
			$row = $row - 2;
			$query = ('SELECT `id` FROM `person` ORDER BY `last_name`, `first_name`, `id` LIMIT $row, 1');

			$result = $this->db->query($query)->row();
			$output = $result->id;
		}
		return $output;
	}

	function get_initials(): array {
		$this->db->from('person');
		$this->db->select('last_name', FALSE);
		$this->db->order_by('last_name');
		$results = $this->db->get()->result();
		$rows = [];
		foreach ($results as $result) {
			$initial = strtoupper(substr($result->last_name, 0, 1));
			$rows[$initial] = (object) ['initial' => $initial];
		}
		return $rows;
	}

	function get_by_letter($letter) {
		$this->db->where('last_name LIKE "$letter%"', NULL, FALSE);
		$this->db->from('person');
		$this->db->order_by('last_name');
		$this->db->order_by('first_name');
		return $this->db->get()->result();
	}

	/**
	 * Remove a person from the list of searchable individuals
	 *
	 * @param int $id
	 */
	function disable($id) {
		$this->db->where('id', $id);
		$this->db->update('person', [
			'status' => 0,
		]);
		$this->session->set_flashdata('notice', 'This person\'s record has been disabled. It could not be deleted because is connected to at least one tour.');
	}

	function restore($id) {
		$this->db->where('id', $id);
		$this->db->update('person', [
			'status' => 1,
		]);
	}

	function delete($id): string {
		$this->load->model('tourist_model', 'tourist');
		if (count($this->tourist->get($id)) == 0) {
			$address_id = $this->get($id, 'address_id')->address_id;
			if ($address_id) {
				$housemates = count($this->get_housemates($address_id, $id));
				if ($housemates == 0) {
					$this->load->model('address_model', 'address');
					$this->address->delete($address_id);
				}
			}
			$this->load->model('phone_model', 'phone');
			$this->phone->delete_for_person($id);
			$this->db->where('id', $id);
			$this->db->delete('person');
			return 'deleted';
		}
		else {
			$this->disable($id);
			return 'disabled';
		}
	}

	/**
	 * @param $person_id
	 *
	 * @return array
	 *
	 * Get a list of people who have the same first and last name as the person.
	 */
	function get_duplicates($person_id): array {
		$person = $this->get($person_id);
		$this->db->from('person')
			->select('id')
			->where('first_name', $person->first_name)
			->like('last_name', $person->last_name, 'after')
			->where('id !=', $person_id);
		$ids = $this->db->get()->result();
		$output = [];
		if (!empty($ids)) {
			foreach ($ids as $id) {
				$output[] = $this->get($id->id);
			}
		}
		return $output;
	}

	function merge($source_id, $duplicate_id, array $preferences): void {
		// get the source person, address and phone.
		$source = $this->get($source_id);
		$this->load->model('tourist_model', 'tourist');
		$source->tours = $this->tourist->get_by_tourist($source_id);
		$duplicate = $this->get($duplicate_id);
		$duplicate->tours = $this->tourist->get_by_tourist($duplicate_id);
		if (!empty($preferences['phones'])) {
			foreach ($preferences['phones'] as $phone) {
				$this->db->where('phone_id', $phone)
					->update('phone_person', ['person_id' => $source->id]);
			}
		}

		// Update the addresses.
		$this->update($source->id, [
			'address_id' => $preferences['address_id'],
			'email' => $preferences['email'],
			'shirt_size' => $preferences['shirt_size'],
			'is_veteran' => $preferences['is_veteran'],
			'status' => $preferences['status'],
		]);
		// Merge the tours
		if (!empty($duplicate->tours)) {
			foreach ($duplicate->tours as $tour) {
				if ($tour->person_id === $tour->payer_id) {
					$this->db->where('tour_id', $tour->tour_id)
						->where('payer_id', $tour->payer_id)
						->update('payer', ['payer_id' => $source->id]);
					$this->db->where('tour_id', $tour->tour_id)
						->where('payer_id', $tour->payer_id)
						->update('tourist', ['payer_id' => $source->id]);
				}

				// Update the tourist record.
				// first check that the new record does not exist:
				$exists = $this->db->from('tourist')->where('tour_id', $tour->tour_id)
					->where('person_id', $source->id)
					->where('payer_id', $tour->payer_id)
					->get()->row();
				if(!empty($exists)) {
					// It already exists, delete the duplicate tour record.
					$this->db->where('tour_id', $tour->tour_id)
						->where('person_id', $tour->person_id)
						->where('payer_id', $tour->payer_id)
						->delete('tourist');
				}else {
					$this->db->where('tour_id', $tour->tour_id)
						->where('person_id', $tour->person_id)
						->update('tourist', ['person_id' => $source->id]);
				}

				// update the payer record

				// Update roommate records.
				$this->db->where('tour_id', $tour->tour_id)
					->where('person_id', $tour->person_id)
					->update('roommate', ['person_id' => $source->id]);
			}
		}
		// finally delete the duplicate;
		$this->delete($duplicate->id);
		$this->session->set_flashdata('alert', 'The duplicate record has been merged and deleted.');
	}

}
