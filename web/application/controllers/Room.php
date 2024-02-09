<?php
defined('BASEPATH') or exit('No direct script access allowed');

// room.php Chris Dart May 23, 2014 10:11:59 PM chrisdart@cerebratorium.com
class Room extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("room_model", "room");
		$this->load->model("variable_model", "variable");
	}

	function create($tour_id, $stay) {
		$data['room'] = $this->room->create($tour_id, $stay);
		$data['tour_id'] = $tour_id;
		$data['stay'] = $stay;
		$data['sizes'] = get_keyed_pairs(
			$this->variable->get_pairs('room_type',
				[
					'direction' => 'ASC',
					'field' => 'value',
				]),
			[
				'value',
				'name',
			]);
		$data['action'] = 'update';
		$data['target'] = 'room/edit';
		$data['title'] = 'Add room';
		if ($this->input->get('ajax')) {
			$this->load->view('room/edit', $data);

		}
		else {
			$this->load->view('page/index', $data);
		}

	}


	function edit_value() {
		$data['name'] = $this->input->get('field');
		$value = $this->input->get('value');
		$data['value'] = $value;
		if (is_array($value)) {
			$data['value'] = implode(',', $value);
		}
		$data['id'] = $this->input->get('id');
		$data['size'] = strlen($data['value']) + 5;
		$data['type'] = $this->input->get('type');
		$data['category'] = $this->input->get('category');

		$output = match ($data['type']) {
			'dropdown' => $this->_get_dropdown($data['category'],
				$data['value'], $data['name']),
			'multiselect' => $this->_get_multiselect($data['category'],
				$data['value'], $data['name']),
			'textarea' => form_textarea($data, $data['value']),
			default => form_input($data),
		};

		echo $output;
	}

	function update_value() {
		$id = $this->input->post('id');
		$value = $this->input->post('value');
		if (is_array($value)) {
			$value = implode(',', $value);
		}
		$values = [
			$this->input->post('field') => $value,
		];
		$this->room->update($id, $values);
		echo $value;
	}

	function delete() {
		$id = $this->input->post('id');
		if ($this->room->delete($id)) {
			$output = TRUE;
		}
		else {
			$output = FALSE;
		}
		echo $output;
	}

	function _get_dropdown($category, $value, $field) {
		$this->load->model('variable_model', 'variable');
		$categories = $this->variable->get_pairs($category,
			[
				'field' => 'value',
				'direction' => 'ASC',
			]);
		$pairs = get_keyed_pairs($categories,
			[
				'value',
				'name',
			]);
		return form_dropdown($field, $pairs, $value, 'class="live-field"');
	}

}
