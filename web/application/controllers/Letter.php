<?php

defined('BASEPATH') or exit('No direct script access allowed');

// letter.php Chris Dart Mar 14, 2014 9:32:59 PM chrisdart@cerebratorium.com
class Letter extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model("letter_model", "letter");
		$this->load->model("tour_model", "tour");
		$this->load->model("payer_model", "payer");
	}

	function select($payer_id, $tour_id): void {
		$data["payer_id"] = $payer_id;
		$data["tour_id"] = $tour_id;
		$data["letters"] = $this->letter->get_for_tour($tour_id);
		$data['target'] = 'letter/select';
		$data['title'] = 'Select a letter';
		if ($this->input->get('ajax')) {
			$this->load->view("page/modal", $data);
		}
		else {
			$this->load->view('page/index', $data);
		}
	}

	function view($id): void {
		$letter = $this->letter->get($id);
		$data["tour"] = $this->tour->get($letter->tour_id);
		$data["letter"] = $letter;
		$data["target"] = "letter/view";
		$data['scripts'] = [site_url('js/letter.js')];
		$data["title"] = sprintf("Viewing '%s' Letter for '%s' Tour", $letter->title, $data["tour"]->tour_name);
		$this->load->view("page/index", $data);
	}

	function create(): void {
		$data["tour_id"] = $this->uri->segment(3);
		$data["tour"] = $this->tour->get($data["tour_id"]);
		$data["letter"] = NULL;
		$data["target"] = "letter/edit";
		$data["action"] = "insert";
		$data['scripts'] = [
			site_url('js/letter.js'),
			site_url('js/editor.js'),
			'https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js',
		];		$data["title"] = "Editing Letter";
		$this->load->view("page/index", $data);
	}

	function insert(): void {
		$id = $this->letter->insert();
		redirect("letter/view/$id");
	}

	function edit(): void {
		$id = $this->uri->segment(3);
		$letter = $this->letter->get($id);
		$tour = $this->tour->get($letter->tour_id);
		$data["letter"] = $letter;
		$data["tour_id"] = $letter->tour_id;
		$data["tour"] = $tour;
		$data["target"] = "letter/edit";
		$data["title"] = sprintf("Editing '%s' Letter for %s' Tour", $letter->title, $tour->tour_name);
		$data["action"] = "update";
		$data['scripts'] = [
			site_url('js/letter.js'),
			site_url('js/editor.js'),
			'https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js',
		];

		$this->load->view("page/index", $data);
	}

	function update(): void {
		$id = $this->input->post("id");
		$this->letter->update($id);
		$this->session->set_flashdata('notice', 'Letter template updated.');
		redirect("letter/view/$id");
	}

	function delete(): void {
		if (!empty($id = $this->input->get('letter_id'))) {
			$letter = $this->letter->get($id);
			$data['identifiers'] = [
				'id' => $id,
				'tour_id' => $letter->tour_id,
			];
			$data['entity'] = 'Letter';
			$data['message'] = 'Are you sure you want to delete ' . $letter->title . '?';
			$data['action'] = 'letter/delete';
			$data['target'] = 'dialogs/delete';
			$data['title'] = 'Delete a letter';
			if ($this->input->get('ajax')) {
				$this->load->view("page/modal", $data);
			}
			else {
				$this->load->view('page/index', $data);
			}
		}
		else {
			$id = $this->input->post('id');
			$tour_id = $this->input->post('tour_id');
			$this->letter->delete($id);
			$this->session->set_flashdata('notice', 'Letter template deleted.');
			redirect("tours/view/$tour_id");
		}
	}

}
