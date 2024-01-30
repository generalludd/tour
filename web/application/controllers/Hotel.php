<?php
defined('BASEPATH') or exit('No direct script access allowed');

// hotel.php Chris Dart Dec 28, 2013 10:08:31 PM chrisdart@cerebratorium.com
class Hotel extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('hotel_model', 'hotel');
		$this->load->model('contact_model', 'contact');
	}

	function view($hotel_id)
	{
		$this->load->model('room_model', 'room');
		$hotel = $this->hotel->get($hotel_id);
		$data['contacts'] = $this->contact->get_all($hotel_id);
		$data['hotel'] = $hotel;
		$rooms = $this->room->get_for_tour($hotel->tour_id, $hotel->stay);
		$room_types = [
			'Double' => 0,
			'Single' => 0,
			'King' => 0,
			'Triple' => 0,
			'Quad' => 0,
		];
		foreach ($rooms as $room) {
			$room_types[$room->size]++;
		}
		$data['room_types'] = $room_types;
		$data['rooms'] = $rooms;
		$data['target'] = 'hotel/view';
		$data['title'] = sprintf('Viewing Details for Hotel: %s', $hotel->hotel_name);
		$this->load->view('page/index', $data);
	}

	function view_for_tour($tour_id)
	{
		$this->load->model('tour_model', 'tour');
		$tour = $this->tour->get($tour_id);
		$data['hotels'] = $tour->hotels;
		$data['target'] = 'hotel/list';
		$data['title'] = sprintf('Showing Hotels for Tour: %s', $tour->tour_name);
		$data['tour'] = $tour;
		$this->load->view('page/index', $data);
	}

	function view_all()
	{
		$this->load->model('tour_model', 'tour');
		$tours = $this->tour->get_all(TRUE);
		$data['tours'] = $tours;
	}

	function create($tour_id)
	{
		$this->load->model('tour_model', 'tour');
		$tour_list = $this->tour->get_all(FALSE, 'tour_name,id');
		$tour = $this->tour->get($tour_id, 'id, tour_name');
		$data['tour'] = $tour;

		$data['tour_list'] = get_keyed_pairs($tour_list, [
			'id',
			'tour_name',
		], TRUE);
		$data['hotel'] = NULL;
		$data['action'] = 'insert';
		$data['stay'] = $this->input->get('stay');
		if ($this->input->get('ajax')) {
			$this->load->view('hotel/edit', $data);
		}
	}

	function insert()
	{
		$id = $this->hotel->insert();
		redirect('hotel/view/' . $id);
	}

	function edit($hotel_id)
	{
		$hotel = $this->hotel->get($hotel_id);
		$this->load->model('tour_model', 'tour');
		$tour_list = $this->tour->get_all(FALSE);
		$tour = $this->tour->get($hotel->tour_id, 'id, tour_name');
		$data['tour'] = $tour;

		$data['tour_list'] = get_keyed_pairs($tour_list, [
			'id',
			'tour_name',
		], TRUE);
		$data['hotel'] = $hotel;
		$data['action'] = 'update';
		$data['target'] = 'hotel/edit';
		$data['title'] = 'Edit ' . $hotel->hotel_name;
		if ($this->input->get('ajax')) {
			$this->load->view('hotel/edit', $data);
		} else {
			$this->load->view('page/index', $data);
		}
	}

	function update()
	{
		$id = $this->input->post('id');
		$this->hotel->update($id);
		redirect('hotel/view/' . $id);
	}

	function update_value()
	{
		$id = $this->input->post('id');
		$value = trim($this->input->post('value'));
		$field = $this->input->post('field');
		if (strpos($field, 'date')) {
			$value = format_date($value, 'mysql');
		}
		$values = [
			$field => $value,
		];
		$this->hotel->update($id, $values);
		echo $this->input->post('value');
	}

	function delete()
	{
		$id = $this->input->post('id');
		$this->hotel->delete($id);
	}
}
