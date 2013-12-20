<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tourist.php Chris Dart Dec 13, 2013 7:53:43 PM chrisdart@cerebratorium.com
class Tourist extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("tourist_model", "tourist");
    }

    function view ()
    {
    }

    function show_all ()
    {
        $this->load->model("payer_model", "payer");
        $this->load->model("phone_model", "phone");
        $tourists = $this->tourist->get_by_tour($this->uri->segment(3));
        $data["tourists"] = array();
        $data["tour_name"] = $tourists[0]->tour_name;

        $data["title"] = sprintf("%s: Tourists", $data["tour_name"]);
        foreach ($tourists as $tourist) {
            $my_list = array();
            $my_list["is_payer"] = false;
            $current_payer = $tourist->payer_id;
            $my_list["person_id"] = $tourist->person_id;
            $my_list["tour_id"] = $tourist->tour_id;
            $my_list["payer_id"] = $tourist->payer_id;
            $my_list["first_name"] = $tourist->first_name;
            $my_list["last_name"] = $tourist->last_name;
            $my_list["shirt_size"] = $tourist->shirt_size;
            $my_list["email"] = $tourist->email;
            if ($current_payer == $tourist->person_id) {
                $my_list["is_payer"] = TRUE;

                $price = 0;
                switch ($tourist->payment_type) {
                    case "full_price":
                        $price = $tourist->full_price;
                        break;
                    case "banquet_price":
                        $price = $tourist->banquet_price;
                        break;
                    case "early_price":
                        $price = $tourist->early_price;
                        break;
                    case "regular_price":
                        $price = $tourist->regular_price;
                        break;
                }

                switch ($tourist->room_size) {
                    case "single_room":
                        $rate = $tourist->single_room;
                        break;
                    case "triple_room":
                        $rate = $tourist->triple_room;
                        break;
                    case "quad_room":
                        $rate = $tourist->quad_room;
                        break;
                    default:
                        $rate = 0;
                        break;
                }
                $tourist_count = $this->payer->get_tourist_count($tourist->payer_id, $tourist->tour_id);
                $my_list["phones"] = $this->phone->get_for_person($tourist->payer_id);
                $price = $price * $tourist_count;
                $my_list["discount"] = $tourist->discount;
                $my_list["amt_paid"] = $tourist->amt_paid;
                if (! $tourist->room_size) {
                    $my_list["room_size"] = "Double";
                } else {
                    $my_list["room_size"] = $tourist->room_size;
                }
                $my_list["room_rate"] = $rate;
                $my_list["price"] = $price;
                $my_list["payment_type"] = $tourist->payment_type;
                $my_list["amt_due"] = $price - $tourist->amt_paid + $tourist->discount + $rate;
            }
            $data["tourists"][] = (object) $my_list;
        }
        $data["target"] = "tourist/list";
        $this->load->view("page/index", $data);
    }

    function create ()
    {
    }

    function insert ()
    {
        $data["payer_id"] = $this->input->post("payer_id");
        $data["tour_id"] = $this->input->post("tour_id");
        $data["person_id"] = $this->input->post("person_id");
        if ($this->input->post("ajax") == 1) {
            $target = "tourist/payer_list";
            $this->tourist->insert($data);
            $data["tourists"] = $this->tourist->get_by_payer($data["payer_id"], $data["tour_id"]);
            $this->load->view($target, $data);
        }
    }

    function update ()
    {
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
        );
        $this->tourist->update($id, $values);
        echo $this->input->post("value");
    }

    function find_by_name()
    {
        $this->load->model("person_model","person");
        $name = $this->input->get("name");
        $tour_id = "NULL";
        if($this->input->get("tour_id")){
            $tour_id = $this->input->get("tour_id");
        }
        $payer_id = NULL;
        if($this->input->get("payer_id")){
            $payer_id = $this->input->get("payer_id");
        }
        $data["payer_id"] = $payer_id;
        $data["tour_id"] = $tour_id;
        $target = "person/mini_list";
        $data["people"] = $this->person->find_people($name, $payer_id, $tour_id);
        $this->load->view($target, $data);
    }

    function delete ()
    {
        $tour_id = $this->input->post("tour_id");
        $person_id = $this->input->post("person_id");
        $payer_id = $this->input->post("payer_id");
        if ($this->input->post("ajax") == 1) {
            $this->tourist->delete($person_id, $tour_id);
            $target = "tourist/payer_list";
            $data["tourists"] = $this->tourist->get_by_payer($payer_id, $tour_id);
             $this->load->view($target, $data);

        }
    }
} //end class