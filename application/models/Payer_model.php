<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer_model.php Chris Dart Dec 14, 2013 5:47:05 PM
// chrisdart@cerebratorium.com
class Payer_model extends CI_Model
{

    var $payment_type;

    var $room_size;

    var $discount;

    var $amt_paid;

    var $is_comp = 0;

    var $is_cancelled = 0;

    var $note;


    function prepare_variables ()
    {
        $variables = array(
                "payment_type",
                "room_size",
                "discount",
                "amt_paid",
                "is_comp",
                "is_cancelled",
                "note"
        );
        for ($i = 0; $i < count($variables); $i ++) {
            $my_variable = $variables[$i];
            if ($my_value = $this->input->post($my_variable)) {
                if ($my_variable == "discount" || $my_variable == "amt_paid") {
                    $this->{$my_variable} = floatval($my_value);

                }
                $this->{$my_variable} = $this->input->post($my_variable);
            }
        }
    }

    function get_value ($payer_id, $tour_id, $field)
    {
        $this->db->from("payer");
        $this->db->where("payer_id", $payer_id);
        $this->db->where("tour_id", $tour_id);

        // @TODO convert this into an array option for multiple fields
        $this->db->select($field);
        $result = $this->db->get()->row();
        return $result;
    }

    function get_for_tour ($payer_id, $tour_id)
    {
        $this->db->from('payer');
        $this->db->join('tour', 'payer.tour_id=tour.id');
        $this->db->join('payment',
                'payer.tour_id=payment.tour_id AND payer.payer_id = payment.payer_id','LEFT');
       $this->db->join('person', 'payer.payer_id=person.id');
        $this->db->where('payer.payer_id', $payer_id);
        $this->db->where('payer.tour_id', $tour_id);
        $this->db->select(
                'tour.tour_name,tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price,tour.single_room, tour.triple_room, tour.quad_room');
        $this->db->select('person.first_name, person.last_name');
        $this->db->select('payer.*');
        $this->db->select_sum('payment.amount');
        $result = $this->db->get()->row();
        return $result;
    }

    function get_tourist_count ($payer_id, $tour_id)
    {
        $this->db->where("tourist.payer_id", $payer_id);
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->from("tourist");
        $result = $this->db->count_all_results();
        return $result;
    }

    function get_payers ($tour_id, $options = array())
    {
        $this->db->where("payer.tour_id", $tour_id);
        $this->db->where("`tour`.`id` = `payer`.`tour_id`", NULL, FALSE);
        $this->db->join("person", "person.id = payer.payer_id");
        $this->db->from("payer,tour");
        $this->db->select(
                "tour.id, tour.tour_name,tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price, tour.single_room, tour.triple_room, tour.quad_room");
        $this->db->select(
                "payer.*, person.first_name, person.last_name, person.email");
        if (array_key_exists("include_address", $options) &&
                 $options["include_address"]) {
            $this->db->join("address", "person.address_id = address.id");
            $this->db->select(
                    "address, address.city, address.state, address.zip, address.informal_salutation, address.formal_salutation");
        }
        $this->db->join("letter","letter.tour_id = payer.tour_id","LEFT");
        $this->db->join("merge","merge.payer_id = payer.payer_id AND letter.id = merge.letter_id","LEFT");
        $this->db->select("merge.id as merge_id");
        $this->db->order_by("person.last_name", "ASC");
        $this->db->order_by("person.first_name", "ASC");
        $result = $this->db->get()->result();
        return $result;
    }

		function get_payer_object($payer){
			$this->load->model('person_model','person');
			$this->load->model('payment_mode','payment');
			$payer->person = $this->person->get($payer->payer_id);
			$payer->tourists = $this->tourist->get_for_payer($payer->payer_id, $payer->tour_id);
			$payer->payments = $this->payment->get_all($payer->tour_id, $payer->payer_id);
			$payer->amt_paid = 0;
			foreach($payer->payments as $payment){
				$payer->amt_paid += $payment->amount;
			}
			switch ($payer->payment_type) {
				case 'full_price' :
					$payer->price = $payer->full_price;
					break;
				case 'banquet_price' :
					$payer->price = $payer->banquet_price;
					break;
				case 'early_price' :
					$payer->price = $payer->early_price;
					break;
				case 'regular_price' :
					$payer->price = $payer->regular_price;
					break;
				default :
					$payer->price = 0;
					break;
			}
			if ($payer->price == 0) {
				$payer->room_rate = 0;
			}
			else {
				switch ($payer->room_size) {
					case 'single_room' :
						$payer->room_rate = $payer->single_room;
						break;
					case 'triple_room' :
						$payer->room_rate = $payer->triple_room;
						break;
					case 'quad_room' :
						$payer->room_rate = $payer->quad_room;
						break;
					default :
						$payer->room_rate = 0;
						break;
				}
			}
			if ($payer->is_comp == 1 || $payer->is_cancelled) {
				$payer->price = 0;
				$payer->room_rate = 0;
			}
			$payer->amt_due = get_payment_due($payer);
			return $payer;
		}

    /**
     * Get the number room types for a given tour (single, double, triple, quad)
     *
     * @param int $tour_id
     * @return object
     */
    function get_room_types (int $tour_id)
    {
        $this->db->select("count(room_size) as count, room_size");
        $this->db->from("payer");
        $this->db->where("tour_id", $tour_id);
        $this->db->group_by("room_size");
        $result = $this->db->get()->result();
        return $result;
    }

    function update ($payer_id, $tour_id)
    {
        $this->db->where("tour_id", $tour_id);
        $this->db->where("payer_id", $payer_id);
        $this->prepare_variables();
        $this->db->update("payer", $this);
    }

    function insert ($payer_id, $tour_id)
    {
        $insert_array = array(
                "payer_id" => $payer_id,
                "tour_id" => $tour_id
        );
        $this->db->insert('payer', $insert_array);
    }

    function delete ($payer_id, $tour_id)
    {
        $this->db->where("payer_id", $payer_id);
        $this->db->where("tour_id", $tour_id);
        $this->db->delete("payer");
    }
}
