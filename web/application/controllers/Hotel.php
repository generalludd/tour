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

	function view($hotel_id): void {
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

	function view_for_tour($tour_id): void {
		$this->load->model('tour_model', 'tour');
		$tour = $this->tour->get($tour_id);
		$data['hotels'] = $tour->hotels;
		$data['target'] = 'hotel/list';
		$data['title'] = sprintf('Showing Hotels for Tour: %s', $tour->tour_name);
		$data['tour'] = $tour;
		$this->load->view('page/index', $data);
	}

	function view_all(): void {
		$this->load->model('tour_model', 'tour');
		$tours = $this->tour->get_all(TRUE);
		$data['tours'] = $tours;
	}

	function create($tour_id): void {
		$this->load->model('tour_model', 'tour');
		$tour = $this->tour->get($tour_id);
		// Get hotels from the tour for the dates used.
		$stays = $this->hotel->get_all($tour_id);
		$hotel = new stdClass();
		$hotel->departure_date = $tour->end_date;
		$hotel->tour_id = $tour_id;
		if(!empty($stays)) {
			// Get the last item of the stays
			$stay = end($stays);
			$hotel->stay = $stay->stay + 1;
			$hotel->arrival_date = $stay->departure_date;
		}else {
			$hotel->stay = 1;
			$hotel->arrival_date = $tour->start_date;
		}
		$data['hotel'] = $hotel;
		$data['tour'] = $tour;
		$data['action'] = 'insert';
		$data['target'] = 'hotel/edit';
		$data['title'] = 'Add Hotel';
		$data['stay'] = $this->input->get('stay');
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);
		}else {
			$this->load->view('page/index', $data);
		}
	}

	function insert(): void {
		$id = $this->hotel->insert();
		redirect('hotel/view/' . $id);
	}

	function edit($hotel_id): void {
		$hotel = $this->hotel->get($hotel_id);
		$this->load->model('tour_model', 'tour');
		$tour = $this->tour->get($hotel->tour_id);
		$data['tour'] = $tour;
		$data['hotel'] = $hotel;
		$data['action'] = 'update';
		$data['target'] = 'hotel/edit';
		$data['title'] = 'Edit ' . $hotel->hotel_name;
		if ($this->input->get('ajax')) {
			$this->load->view('page/modal', $data);
		} else {
			$this->load->view('page/index', $data);
		}
	}

	function update(): void {
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

	function delete(): void {
		if($id = $this->input->post('id')){
			$hotel = $this->hotel->get($id);
			$delete_rooms = $this->input->post('delete_rooms') === "1";
			$this->hotel->delete($id, $delete_rooms);
			$flash =  'Hotel ' . $hotel->hotel_name . ' successfully deleted.';
			if($delete_rooms){
				$flash .= ' All Rooms were also deleted.';
			}else {
				$flash .= ' Rooms were not deleted.';
			}
			$this->session->set_flashdata('notice',$flash);
			redirect('hotel/view_for_tour/' . $hotel->tour_id);
		}else {
			$id = $this->input->get('id');
			$hotel = $this->hotel->get($id);
			$data['identifiers'] = [
				'id' => $id,
			];
			$data['additional_fields'] = [
				'Delete rooms' => form_checkbox('delete_rooms', TRUE),
			];
			$data['title'] = 'Delete a hotel';
			$data['action'] = 'hotel/delete';
			$data['target'] = 'dialogs/delete';
			$data['message'] = 'Are you sure you want to delete ' . $hotel->hotel_name . '?';
			if ($this->input->get('ajax')) {
				$this->load->view('page/modal', $data);
			}
			else {
				$this->load->view('page/index', $data);
			}
		}
	}
}
