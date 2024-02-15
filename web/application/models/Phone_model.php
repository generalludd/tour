<?php

defined('BASEPATH') or exit('No direct script access allowed');

// phone_model.php Chris Dart Dec 10, 2013 10:04:19 PM
// chrisdart@cerebratorium.com
class Phone_model extends CI_Model {

	var $phone;

	var $phone_type;

	function prepare_variables(): void {
		$variables = [
			"phone",
			"phone_type",
		];

		if ($this->input->post("phone")) {
			$this->phone = $this->input->post("phone");
		}

		if ($this->input->post("phone_type")) {
			$this->phone_type = $this->input->post("phone_type");
		}
		// prepare_variables($this, $variables);
	}

	function get($id) {
		$this->db->where("phone.id", $id)
			->select("phone.id,phone.phone,phone.phone_type,phone_person.person_id,phone_person.is_primary")
			->join('phone_person', 'phone_person.phone_id = phone.id')
			->from("phone");
		return $this->db->get()->row();
	}

	function get_phone_person($phone_id, $fields = ["person_id"]) {
		$this->db->from("phone_person");
		$this->db->where("phone_id", $phone_id);
		$fields = implode(",", $fields);
		$this->db->select($fields);
		$result = $this->db->get()->row();
		return $result;
	}

	function insert_for_person($person_id) {
		$this->prepare_variables();
		$this->db->insert("phone", $this);
		$id = $this->db->insert_id();
		$relation_array = [
			"person_id" => $person_id,
			"phone_id" => $id,
		];

		$this->db->insert("phone_person", $relation_array);
		return $id;
	}

	function get_for_person($person_id) {
		$this->db->from("phone_person");
		$this->db->join("phone", "phone_person.phone_id = phone.id");
		$this->db->select("phone.id,phone.phone,phone.phone_type");
		$this->db->where("phone_person.person_id", $person_id);

		$result = $this->db->get()->result();
		return $result;
	}

	function update($id, $values = []) {
		$this->db->where("id", $id);
		if (empty($values)) {
			$this->prepare_variables();
			$this->db->update("phone", $this);
		}
		else {
			$this->db->update("phone", $values);
			if ($values == 1) {
				$keys = array_keys($values);
				return $this->get_value($id, $keys[0]);
			}
		}
	}

	function set_primary($id, $is_primary) {
		$this->db->where("phone_id", $id);
		$this->db->update("phone_person", [
			"is_primary" => $is_primary,
		]);
	}

	function delete($id) {
		$this->db->delete("phone", [
			"id" => $id,
		]);
		$this->db->delete("phone_person", [
			"phone_id" => $id,
		]);
	}

	function delete_for_person($id) {
		$phones = $this->get_for_person($id);
		foreach ($phones as $phone) {
			$this->delete($phone->id);
		}
	}

	function fix() {
		// $this->db->where("phone.person_link = person.address_id", NULL,
		// FALSE);
		// $this->db->where("phone.person_link != 0", NULL,FALSE);
		// $this->db->from("person,phone");
		// $this->db->select("person.id as person_id,phone.id as phone_id");
		// $result = $this->db->get()->result();
		// print $this->db->last_query();
		// return $result;
	}

}
