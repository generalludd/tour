<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Letter_model extends CI_Model {

	protected string $title;

	protected string $body;

	protected string $cancellation;

	protected int $tour_id;

	protected string $creation_date;

	function prepare_variables(): void {
		$variables = [
			"title",
			"body",
			"cancellation",
			"tour_id",
			"creation_date",
		];
		prepare_variables($this, $variables);
	}

	function get($id) {
		$this->db->where("id", $id);
		$this->db->from("letter");
		$result = $this->db->get()->row();
		return $result;
	}

	function get_for_tour($tour_id) {
		$this->db->where("tour_id", $tour_id);
		$this->db->from("letter");
		return $this->db->get()->result();
	}

	function insert() {
		$this->prepare_variables();
		$this->db->insert("letter", $this);
		return $this->db->insert_id();
	}

	function update($id): void {
		$this->prepare_variables();
		$this->db->where("id", $id);
		$this->db->update("letter", $this);
	}

	function delete($id): void {
		$this->db->delete('letter', ["id" => $id]);
		$this->db->delete('merge', ["letter_id" => $id]);
	}

}
