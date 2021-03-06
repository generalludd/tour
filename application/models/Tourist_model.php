<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tourist_model.php Chris Dart Dec 14, 2013 4:00:34 PM
// chrisdart@cerebratorium.com
class Tourist_model extends CI_Model
{
    var $tour_id;
    var $payer_id;
    var $person_id;


	/**
	 * get all the tours for a given person.
	 * $tours is a simple array of tour ids This is used to limit the results
	 * to selected tour(s).
	 *
	 * @param int $person_id
	 * @param array $tours
	 *
	 * @return mixed
	 */
    function get (int $person_id, $tours = [])
    {
        $this->db->from("tourist");
        $this->db->where("person_id", $person_id);
        if(!empty($tours)) {
					$this->db->where_not_in("tour_id", $tours);
				}
        $result = $this->db->get()->result();
        return $result;
    }

    function get_by_tour ($tour_id)
    {
        $this->db->select("tourist.tour_id, tourist.person_id");
        $this->db->select("person.first_name, person.last_name, person.shirt_size,person.email");
        $this->db->select(
                "tour.tour_name, tour.full_price, tour.banquet_price, tour.early_price, tour.regular_price, tour.single_room, tour.triple_room, tour.quad_room");
        $this->db->select("payer.payer_id,payer.payment_type, payer.room_size, payer.discount, payer.amt_paid, payer.is_comp, payer.is_cancelled, payer.notes");
        $this->db->from("tourist");
        $this->db->join("person", "person.id = tourist.person_id");
        $this->db->join("tour", "tour.id = tourist.tour_id");
        $this->db->join("payer", "payer.tour_id = tourist.tour_id");
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->where("`payer`.`payer_id` = `tourist`.`payer_id`", NULL, FALSE);
        $this->db->order_by("tourist.payer_id, tourist.person_id,person.last_name, person.first_name");
        $result = $this->db->get()->result();
        return $result;
    }


    function get_for_payer($payer_id, $tour_id)
    {
        $this->db->from("tourist");
        $this->db->join("person", "tourist.person_id = person.id");
        $this->db->where("tourist.payer_id", $payer_id);
        $this->db->where("tourist.tour_id", $tour_id);
        $this->db->select("tourist.person_id, person.first_name, person.last_name, person.shirt_size, person.email, tourist.payer_id, tourist.tour_id");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_by_tourist ($person_id)
    {
        $this->db->where("person_id", $person_id);
        $this->db->where("payer.tour_id = tour.id",NULL, FALSE);
        $this->db->from("tourist");
        $this->db->join("payer", "tourist.payer_id = payer.payer_id");
        $this->db->join("tour", "tour.id = tourist.tour_id");
        $this->db->group_by("tourist.tour_id");
        $this->db->order_by("tour.end_date", "DESC");
        $result = $this->db->get()->result();
        return $result;
    }

    function get_shirt_totals($tour_id){

    }

    function insert ($data = FALSE)
    {
        if (is_array($data)) {
            if (array_key_exists("payer_id", $data) && array_key_exists("tour_id", $data) && array_key_exists("person_id", $data)) {
                $payer_id = $data["payer_id"];
                $person_id = $data["person_id"];
                $tour_id = $data["tour_id"];
            }

            $query = "INSERT IGNORE INTO `tourist` (`payer_id`, `tour_id`, `person_id`) VALUES('$payer_id', '$tour_id', $person_id);";
            $this->db->query($query);
        }
    }

    /**
     * insert the payer as a person for the initial insert for a person/tour key
     */
    function insert_payer ($payer_id, $tour_id)
    {
        $query = "INSERT IGNORE INTO `tourist` (`payer_id`, `tour_id`, `person_id`) VALUES('$payer_id', '$tour_id', $payer_id);";
        $this->db->query($query);
    }

    function delete ($person_id, $tour_id)
    {
        // don't delete the payer here!
        $this->db->where_not_in("payer_id", $person_id);

        $this->db->delete("tourist", array(
                "tour_id" => $tour_id,
                "person_id" => $person_id
        ));
    }

    function delete_payer ($payer_id, $tour_id)
    {
        $this->db->where("payer_id", $payer_id);
        $this->db->where("tour_id", $tour_id);
        $this->db->delete("tourist");
    }



    /**
     * DEPRECATED
     * @param int $payer_id
     * @param int $tour_id
     */
    function get_by_payer ($payer_id, $tour_id)
    {
        return $this->get_for_payer($payer_id, $tour_id);

    }
}
