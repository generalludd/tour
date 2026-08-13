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
		// $fields may exclude the name columns, so don't assume they are set.
		$person->name = trim(($person->first_name ?? '') . ' ' . ($person->last_name ?? ''));

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
		if (!empty($options['has_address'])) {
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
		$duplicate = $this->get($duplicate_id);

		$this->db->trans_start();

		if (!empty($preferences['phones'])) {
			foreach ($preferences['phones'] as $phone) {
				$this->db->where('phone_id', $phone)
					->update('phone_person', ['person_id' => $source->id]);
			}
		}

		// Update the addresses.
		$values = [
			'address_id' => $preferences['address_id'],
			'email' => $preferences['email'],
			'shirt_size' => $preferences['shirt_size'],
			'status' => $preferences['status'],
		];
		/*
		 * The merge form only renders the is_veteran checkbox when the two
		 * records disagree, so a missing post value means "no opinion" rather
		 * than "not a veteran". Writing the NULL through would clear the flag
		 * on the record we are keeping.
		 */
		if (isset($preferences['is_veteran'])) {
			$values['is_veteran'] = $preferences['is_veteran'];
		}
		$this->update($source->id, $values);

		// Move every tour association off the duplicate and onto the source.
		$report = $this->move_tour_references((int) $duplicate->id, (int) $source->id);

		// finally delete the duplicate; it no longer holds any tour records, so
		// delete() will remove it outright rather than falling back to disable().
		$result = $this->delete($duplicate->id);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('alert', 'The merge failed and no changes were saved. Please try again.');
			return;
		}

		$this->report_merge($duplicate, $report, $result);
	}

	/**
	 * Repoint every tour-related record belonging to $duplicate_id at
	 * $source_id.
	 *
	 * A person can be attached to a tour three ways: as the payer, as a
	 * traveller in someone's party, and as the payer that other travellers
	 * hang off. All three have to move, along with the rooming list, payments
	 * and letter merges that key off the payer.
	 *
	 * Several of these tables have composite primary keys, so where the source
	 * already holds the row we would be moving into, the duplicate's row is
	 * dropped instead of updated.
	 *
	 * @param int $duplicate_id
	 * @param int $source_id
	 *
	 * @return array
	 *   Counts of what moved, plus 'review_tours': names of tours where both
	 *   records were payers and the money fields need a human decision.
	 */
	private function move_tour_references(int $duplicate_id, int $source_id): array {
		$this->load->model('payer_model', 'payer');
		$this->load->model('tourist_model', 'tourist');
		$this->load->model('merge_model', 'merge');

		$report = [
			'payers' => 0,
			'registrations' => 0,
			'registrations_dropped' => 0,
			'roommates' => 0,
			'roommates_dropped' => 0,
			'payments' => 0,
			'review_tours' => [],
		];

		/*
		 * Payments key on (tour_id, payer_id) but have their own auto-increment
		 * primary key, so they can never collide and can all move at once. This
		 * has to happen whether or not the payer row itself survives, so that
		 * the money follows the person.
		 *
		 * Payment_model::update() can't be used here: it tests `empty($array)`
		 * on an undefined variable, so it always ignores the values passed in
		 * and repopulates the row from POST instead.
		 */
		$this->db->where('payer_id', $duplicate_id)
			->update('payment', ['payer_id' => $source_id]);
		$report['payments'] = $this->db->affected_rows();

		// 1. Payer rows. PK is (payer_id, tour_id).
		foreach ($this->payer->get_for_person($duplicate_id) as $payer) {
			$tour_id = (int) $payer->tour_id;
			/*
			 * get_value() rather than getPriceLevels(): the latter is declared
			 * `: object` but returns row(), so a miss is a TypeError.
			 */
			$collides = !empty($this->payer->get_value($source_id, $tour_id, 'payer_id'));
			if ($collides) {
				/*
				 * Both records paid for this tour. Keep the source's row --
				 * discount, surcharge, amt_paid and is_comp can't be reconciled
				 * automatically -- but carry the note across and flag the tour
				 * for review.
				 */
				if (!empty($payer->note)) {
					$this->payer->appendNote($source_id, $tour_id, $payer->note);
				}
				$this->payer->delete($duplicate_id, $tour_id);
				$tour = $this->db->select('tour_name')
					->from('tour')
					->where('id', $tour_id)
					->get()
					->row();
				$report['review_tours'][] = empty($tour) ? 'tour ' . $tour_id : $tour->tour_name;
			}
			else {
				$this->db->where('payer_id', $duplicate_id)
					->where('tour_id', $tour_id)
					->update('payer', ['payer_id' => $source_id]);
				$report['payers']++;
			}
		}

		/*
		 * 2. Travellers whose ticket the duplicate paid for. This must run
		 * after the payer rows move so the rest of the party follows the
		 * surviving payer. The tourist PK is (tour_id, person_id), so changing
		 * payer_id can't collide.
		 */
		$this->db->where('payer_id', $duplicate_id)
			->update('tourist', ['payer_id' => $source_id]);

		// 3. The duplicate's own registrations. PK is (tour_id, person_id).
		foreach ($this->tourist->get($duplicate_id) as $registration) {
			$tour_id = (int) $registration->tour_id;
			if (!empty($this->tourist->get($source_id, [$tour_id]))) {
				/*
				 * The source is already registered on this tour, so the
				 * duplicate's row would violate the primary key. Drop it.
				 * Tourist_model::delete() can't be used -- it guards against
				 * removing a payer's own row.
				 */
				$this->db->where('tour_id', $tour_id)
					->where('person_id', $duplicate_id)
					->delete('tourist');
				$report['registrations_dropped']++;
			}
			else {
				$this->db->where('tour_id', $tour_id)
					->where('person_id', $duplicate_id)
					->update('tourist', ['person_id' => $source_id]);
				$report['registrations']++;
			}
		}

		// 4. Rooming list. PK is (person_id, tour_id, stay).
		$roommates = $this->db->from('roommate')
			->where('person_id', $duplicate_id)
			->get()
			->result();
		foreach ($roommates as $roommate) {
			$exists = $this->db->from('roommate')
				->where('person_id', $source_id)
				->where('tour_id', $roommate->tour_id)
				->where('stay', $roommate->stay)
				->get()
				->row();
			if (!empty($exists)) {
				$this->db->where('person_id', $duplicate_id)
					->where('tour_id', $roommate->tour_id)
					->where('stay', $roommate->stay)
					->delete('roommate');
				$report['roommates_dropped']++;
			}
			else {
				$this->db->where('person_id', $duplicate_id)
					->where('tour_id', $roommate->tour_id)
					->where('stay', $roommate->stay)
					->update('roommate', ['person_id' => $source_id]);
				$report['roommates']++;
			}
		}

		// 5. Letter merges. UNIQUE key is (payer_id, letter_id).
		$merges = $this->db->from('merge')
			->where('payer_id', $duplicate_id)
			->get()
			->result();
		foreach ($merges as $letter_merge) {
			if (!empty($this->merge->get_for_payer($source_id, $letter_merge->letter_id))) {
				$this->db->where('id', $letter_merge->id)->delete('merge');
			}
			else {
				$this->merge->update($letter_merge->id, ['payer_id' => $source_id]);
			}
		}

		return $report;
	}

	/**
	 * Summarise a completed merge for the user.
	 *
	 * @param object $duplicate
	 * @param array $report
	 *   The return value of move_tour_references().
	 * @param string $result
	 *   The return value of delete(): 'deleted' or 'disabled'.
	 */
	private function report_merge(object $duplicate, array $report, string $result): void {
		$moved = [];
		if ($report['payers']) {
			$moved[] = $report['payers'] . ' ' . ($report['payers'] == 1 ? 'payer record' : 'payer records');
		}
		if ($report['registrations']) {
			$moved[] = $report['registrations'] . ' tour ' . ($report['registrations'] == 1 ? 'registration' : 'registrations');
		}
		if ($report['roommates']) {
			$moved[] = $report['roommates'] . ' rooming ' . ($report['roommates'] == 1 ? 'assignment' : 'assignments');
		}
		if ($report['payments']) {
			$moved[] = $report['payments'] . ' ' . ($report['payments'] == 1 ? 'payment' : 'payments');
		}

		$notice = [];
		if ($result == 'deleted') {
			$notice[] = sprintf('%s has been merged and the duplicate record was deleted.', $duplicate->name);
		}
		else {
			$notice[] = sprintf('%s has been merged, but the duplicate record could only be disabled, not deleted.', $duplicate->name);
		}
		if (!empty($moved)) {
			$notice[] = 'Moved ' . $this->readable_list($moved) . '.';
		}
		$dropped = $report['registrations_dropped'] + $report['roommates_dropped'];
		if ($dropped) {
			$notice[] = sprintf('%d duplicate %s dropped because the record you kept was already on those tours.', $dropped, $dropped == 1 ? 'entry was' : 'entries were');
		}
		$this->session->set_flashdata('notice', implode(' ', $notice));

		if (!empty($report['review_tours'])) {
			$this->session->set_flashdata('warning', sprintf(
				'Both records were payers on %s, so only the record you kept was preserved. Please review the payment details there.',
				$this->readable_list($report['review_tours'])
			));
		}
	}

	/**
	 * Comma-and list for a sentence.
	 *
	 * grammatical_implode() prefixes every item with a space, which doubles up
	 * against a ', ' glue once the list reaches three items, so squash runs of
	 * whitespace on the way out.
	 *
	 * @param array $items
	 *
	 * @return string
	 */
	private function readable_list(array $items): string {
		return trim(preg_replace('/\s+/', ' ', grammatical_implode(', ', $items)));
	}

}
