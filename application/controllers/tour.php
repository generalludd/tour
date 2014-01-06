<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tour.php Chris Dart Dec 13, 2013 7:53:18 PM chrisdart@cerebratorium.com
class Tour extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("tour_model", "tour");
    }

    function index ()
    {
        $this->view_all();
    }

    function view ()
    {
        $id = $this->uri->segment(3);
        $data["tour"] = $this->tour->get($id);
        $data["title"] = sprintf("Tour Data: %s", $data["tour"]->tour_name);
        $data["target"] = "tour/view";
        $this->load->view("page/index", $data);
    }

    function view_all ()
    {
        $data["tours"] = $this->tour->get_all();
        $data["for_tourist"] = FALSE;
        $data["title"] = "Showing All Tours";
        $data["target"] = "tour/list";
        $this->load->view("page/index", $data);
    }


    function create ()
    {
        $data["action"] = "insert";
        $data["tour"] = NULL;
        $this->load->view("tour/edit", $data);
    }

    function edit ()
    {
        $id = $this->input->get("id");
        $data["tour"] = $this->tour->get($id);
        $data["action"] = "update";
        $this->load->view("tour/edit", $data);
    }

    function insert ()
    {
        $id = $this->tour->insert();
        redirect("/tour/view/$id");
    }

    function update ()
    {
        // get the id for returning to the view page after editing
        $id = $this->input->post("id");
        $this->tour->update($id);
        redirect("/tour/view/$id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $field = $this->input->post("field");
        $value = $value = trim($this->input->post("value"));
        if (strstr($field, "price") || strstr($field, "rate")) {
            $value = intval($value);
        } elseif (strstr($field, "date")) {
            $value = format_date($value, "mysql");
        }
        $values = array(
                $this->input->post("field") => $value
        );
        $this->tour->update($id, $values);
        echo $this->input->post("value");
    }

    function get_value ()
    {
        $tour_id = $this->input->get("tour_id");
        $field = $this->input->get("field");
        $format = FALSE;
        if ($this->input->get("format")) {
            $format = $this->input->get("format");
        }
        $output = $this->tour->get_value($tour_id, $field);

        if ($format) {
            switch ($format) {
                case "date":
                    $output = format_date($output);
                    break;
                case "money":
                    $output = format_money($output);
                    break;
            }
        }
        if ($this->input->get("ajax") == 1) {
            echo $output;
        } else {
            return $output;
        }
    }

    function show_current ()
    {
        $this->load->model("tourist_model", "tourist");
        $id = $this->input->get("id");
        $data["id"] = $id;
        $tours = $this->tour->get_current();
        $data["tours"] = $tours;
        foreach ($tours as $tour) {
            $tour_list[] = $tour->id;
        }
        $tourist_tours = $this->tourist->get($id, $tour_list);

        $data["query"] = $this->db->last_query();
        $this->load->view("tour/select", $data);
    }

    function show_payers ()
    {
        $tour_id = $this->uri->segment(3);
        $tour = $this->tour->get($tour_id);
        $this->load->model("payer_model", "payer");
        $this->load->model("tourist_model", "tourist");
        $this->load->model("phone_model", "phone");
        $payers = $this->payer->get_payers($tour_id);
        foreach ($payers as $payer) {
            $phones = $this->phone->get_for_person($payer->payer_id);
            $payer->phones = $phones;
            $tourists = $this->tourist->get_by_payer($payer->payer_id, $tour_id);
            $payer->tourists = $tourists;

            $price = 0;
            switch ($payer->payment_type) {
            	case "full_price":
            	    $price = $tour->full_price;
            	    break;
            	case "banquet_price":
            	    $price = $tour->banquet_price;
            	    break;
            	case "early_price":
            	    $price = $tour->early_price;
            	    break;
            	case "regular_price":
            	    $price = $tour->regular_price;
            	    break;
            	default:
            	    $price = 0;
            	    break;
            }
            if ($price == 0) {
                $rate = 0;
            } else {
                switch ($payer->room_size) {
                	case "single_room":
                	    $rate = $tour->single_room;
                	    break;
                	case "triple_room":
                	    $rate = $tour->triple_room;
                	    break;
                	case "quad_room":
                	    $rate = $tour->quad_room;
                	    break;
                	default:
                	    $rate = 0;
                	    break;
                }
            }
            if($payer->is_comp == 1 || $payer->is_cancelled){
                $price = 0;
                $rate = 0;
            }
            $payer->price = $price;
            $payer->room_rate = $rate;
            $payer->amt_due = $price - $payer->amt_paid + $payer->discount + $rate;

            $tourist_count = $this->payer->get_tourist_count($payer->payer_id, $payer->tour_id);
            $payer->tourist_count = $tourist_count;
        }
        $data["tour"] = $tour;
        $data["payers"] = $payers;
       $this->load->view("payer/list",$data);
    }
}
