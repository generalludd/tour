<?php
defined('BASEPATH') or exit('No direct script access allowed');

// person.php Chris Dart Dec 10, 2013 8:15:47 PM chrisdart@cerebratorium.com
class Person_model extends CI_Model
{
    var $first_name;
    var $last_name;
    var $email;
    var $shirt_size;
    var $address_id;
    var $note;
    var $status = 1;
    var $is_veteran;


    function prepare_variables ()
    {
        $variables = array(
                "first_name",
                "last_name",
                "email",
                "shirt_size",
                "is_veteran",
                "address_id",
                "note"
        );
        prepare_variables($this, $variables);
    }

    function get ($id, $fields = false)
    {
        $this->db->where("person.id", $id);
        $this->db->from("person");
        if ($fields) {
            $this->db->select($fields);
        }

        $result = $this->db->get()->row();
        return $result;
    }

    /**
     *
     * @param array $options
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
    function get_all ($options = array())
    {
        $show_disabled = FALSE;
        $veterans_only = FALSE;
        $non_veterans = FALSE;
        $tour_id = FALSE;
        $initial = FALSE;
        $email_only = FALSE;
        $include_address = FALSE;
        if (array_key_exists("veterans_only", $options) && $options["veterans_only"]) {
            $veterans_only = TRUE;
        }
        if (array_key_exists("non_veterans", $options) && $options["non_veterans"]) {
        	$non_veterans = TRUE;
        }
        if (array_key_exists("show_disabled", $options) && $options["show_disabled"]) {
            $show_disabled = TRUE;
        }
        if (array_key_exists("tour_id", $options) && $options["tour_id"]) {
            $tour_id = $options["tour_id"];
        }
        if (array_key_exists("initial", $options) && $options["initial"]) {
            $initial = $options["initial"];
        }
        if (array_key_exists("email_only", $options) && $options["email_only"]) {
            $email_only = $options["email_only"];
        }
        if (array_key_exists("include_address", $options)) {
            $include_address = TRUE;
        }
        $this->db->select("person.*");

        if ($include_address) {
            $this->db->from("address");
            $this->db->order_by("person.address_id", "ASC");
            $this->db->where("`person`.`address_id` = `address`.`id`", NULL, FALSE);
            $this->db->where("`person`.`address_id` IS NOT NULL", NULL, FALSE);
            $this->db->select("address.address, address.city, address.state,address.zip, person.address_id");
            $this->db->join("person", "person.address_id=address.id");
            $this->db->order_by("address.id");
        } else {
            $this->db->from("person");
        }
        $this->db->order_by("person.last_name", "ASC");
        $this->db->order_by("person.first_name", "ASC");
        if ($initial) {
            $this->db->where("`person`.`last_name` LIKE '$initial%'", NULL, FALSE);
        }
        if ($veterans_only) {
            $this->db->where("person.is_veteran", 1);
        }elseif($non_veterans){
        	$this->db->where("person.is_veteran IS NULL",NULL, FALSE);
        }

        if ($tour_id) {
            $this->db->join("tourist", "tourist.person_id = person.id");
            $this->db->where("tourist.tour_id", $tour_id);
        }
        if ($email_only) {
            $this->db->where("(`person`.`email` IS NOT NULL OR `person`.`email` = '')", NULL, FALSE);
            //$this->db->or_where("`person`.`email`", "");
            $this->db->select("person.first_name, person.last_name, person.email,person.id,person.status,person.is_veteran");
            // $this->db->limit(5);
        }
        if (! $show_disabled) {
            $this->db->where("status", 1);
        }
        $this->db->group_by("person.id");
        $result = $this->db->get()->result();
        $this->session->set_flashdata("notice",$this->db->last_query());
        return $result;
    }

    function insert ($include_address = FALSE)
    {
        $this->prepare_variables();
        $this->db->insert("person", $this);
        $id = $this->db->insert_id();
        if ($include_address) {
            $this->load->model("address_model");
            $this->address_model->insert_for_user($id);

            $this->load->model("phone_model");
            $this->phone_model->insert_for_user($id);
        }
        return $id;
    }

    function update ($id, $values = array())
    {
        $this->db->where("id", $id);
        if (empty($values)) {
            $this->prepare_variables();
            $this->db->update("person", $this);
        } else {
            $this->db->update("person", $values);
            if ($values == 1) {
                $keys = array_keys($values);
                return $this->get_value($id, $keys[0]);
            }
        }
    }

    function find_people ($name, $options = array())
    {
        $this->db->where("CONCAT(`first_name`,' ', `last_name`) LIKE '%$name%'", NULL, FALSE);
        $this->db->where("status", 1);
        $this->db->order_by("first_name", "ASC");
        $this->db->order_by("last_name", "ASC");
        $this->db->from("person");
        if (array_key_exists("tour_id", $options)) {
        }
        if (array_key_exists("payer_id", $options)) {
        }
        if (array_key_exists("select", $options)) {
            $this->db->select($options["select"]);
        }
        if (array_key_exists("has_address", $options)) {
            $this->db->where("`address_id` IS NOT NULL", NULL, FALSE);
        }
        // The following are deprectated steps a vain attempt at selecting
        // tourists not already added to a tour.
        /*
         * if ($payer_id) { $this->db->where("person.id != '$payer_id'", NULL,
         * FALSE); } if ($tour_id) { $this->db->join("tourist",
         * "tourist.person_id = person.id");
         * $this->db->where_not_in("tourist.tour_id", $tour_id); }
         */
        $result = $this->db->get()->result();
        return $result;
    }

    function get_housemates ($address_id, $person_id)
    {
        $this->db->where("person.address_id", $address_id);
        $this->db->where("person.id !=", $person_id);
        $this->db->where("status", 1); // only show non-disabled entries
        $this->db->order_by("person.last_name, person.first_name");
        $this->db->from("person");
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get all the residents for a given address.
     *
     * @param int $address_id
     * @return array of objects
     */
    function get_residents ($address_id)
    {
        $this->db->from("person");
        $this->db->where("address_id", $address_id);
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * get the row number of the current record to view the next or previous
     * record
     *
     * @param int $id
     */
    function get_row ($id)
    {
        $result = $this->db->query(
                "SELECT row  FROM  (SELECT @rownum:=@rownum+1 row, a.*
        FROM person a, (SELECT @rownum:=0) r
        ORDER BY last_name, first_name, id) as article_with_rows
        WHERE id = $id")->row();
        return $result->row;
    }

    function get_next_person ($id)
    {
        $row = $this->get_row($id);
        if ($row == $this->db->count_all("person")) {
            $output = $id;
        } else {
            $query = ("SELECT `id` FROM `person` ORDER BY `last_name`,`first_name`, `id` LIMIT $row, 1");
            $result = $this->db->query($query)->row();
            $output = $result->id;
        }
        return $output;
    }

    function get_previous_person ($id)
    {
        $row = $this->get_row($id);

        if ($row == 1) {
            $output = $id;
        } else {
            $row = $row - 2;
            $query = ("SELECT `id` FROM `person` ORDER BY `last_name`, `first_name`, `id` LIMIT $row, 1");

            $result = $this->db->query($query)->row();
            $output = $result->id;
        }
        return $output;
    }

    function get_initials ()
    {
        $this->db->select("DISTINCT LEFT(last_name,1) AS initial", FALSE);
        $this->db->order_by("last_name");
        $this->db->from("person");
        return $this->db->get()->result();
    }

    function get_by_letter ($letter)
    {
        $this->db->where("last_name LIKE '$letter%'", NULL, FALSE);
        $this->db->from("person");
        $this->db->order_by("last_name");
        $this->db->order_by("first_name");
        $result = $this->db->get()->result();
        return $result;
    }

    /**
     * Remove a person from the list of searchable individuals
     *
     * @param unknown $id
     */
    function disable ($id)
    {
        $this->db->where("id", $id);
        $this->db->update("person", array(
                "status" => 0
        ));
    }

    function restore ($id)
    {
        $this->db->where("id", $id);
        $this->db->update("person", array(
                "status" => 1
        ));
    }

    function delete ($id)
    {
        $this->load->model("tourist_model", "tourist");
        if (count($this->tourist->get($id)) == 0) {
            $address_id = $this->get($id, "address_id")->address_id;
            if ($address_id) {
                $housemates = count($this->get_housemates($address_id, $id));
                if ($housemates == 0) {
                    $this->load->model("address_model", "address");
                    $this->address->delete($address_id);
                }
            }
            $this->load->model("phone_model", "phone");
            $this->phone->delete_for_person($id);
            $this->db->where("id", $id);
            $this->db->delete("person");
        } else {
            $this->disable($id);
        }
    }
}