<?php
defined('BASEPATH') or exit('No direct script access allowed');

// address_model.php Chris Dart Dec 10, 2013 9:48:02 PM
// chrisdart@cerebratorium.com
class Address_model extends CI_Model
{

	var $address;

	var $city;

	var $state;

	var $zip;

	var $formal_salutation;

	var $informal_salutation;


	function prepare_variables()
	{
		$variables = [
			'address',
			'city',
			'state',
			'zip',
			'formal_salutation',
			'informal_salutation',
		];
		prepare_variables($this, $variables);
	}

	function get($address_id)
	{
		$this->db->where('id', $address_id);
		$this->db->from('address');
		return $this->db->get()->row();
	}

	function get_for_labels(array $options = []){
		$this->update_salutations();
		// Get all the people based on the options.
		$this->load->model('person_model', 'person');
		$people = $this->person->get_all($options);
		// Get the unique address id list;
		$address_ids = [];
		foreach($people as $person){
			$address_ids[$person->address_id] = $person->address_id;
		}
		// Get the addresses.
		return $this->db->from('address')
			->select('address.*')
			->where('address.address IS NOT NULL', NULL, FALSE)
			->where('address.city IS NOT NULL',NULL, FALSE)
			->where('address.state IS NOT NULL',NULL, FALSE)
			->where('address.zip IS NOT NULL', NULL, FALSE)
			->where_in('address.id', $address_ids)
			->order_by('address.zip')
			->get()->result();
	}

	function get_all($options = [])
	{
		$query = $this->db->from('address')
		->select('*');
		if(!empty('missing_salutations')){
			$query->where('formal_salutation', NULL)
			->or_where('informal_salutation', NULL);
		}
		return $query->get()->result();
	}

	function insert()
	{
		$this->prepare_variables();
		$this->db->insert('address', $this);
		return $this->db->insert_id();
	}

	function update($id, $values = [])
	{
		$this->db->where('id', $id);
		if (empty($values)) {
			$this->prepare_variables();
			$this->db->update('address', $this);
		} else {
			$this->db->update('address', $values);
			if ($values == 1) {
				$keys = array_keys($values);
				return $this->get($id, $keys);
			}
		}
	}

	function update_salutations(){
		$addresses = $this->get_all(['missing_salutations' => 1]);
		foreach ($addresses as $address) {
			$people = $this->person->get_residents($address->id);
			$values['formal_salutation'] = format_salutation($people, 'formal');
			$values['informal_salutation'] = format_salutation($people);
			$this->update($address->id, $values);
		}
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('address');
	}
}
