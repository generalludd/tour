<?php
defined('BASEPATH') or exit('No direct script access allowed');

// merge.php Chris Dart Mar 15, 2014 2:35:26 PM chrisdart@cerebratorium.com
class Merge extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("merge_model", "merge");
        $this->load->model("letter_model", "letter");
        $this->load->model("payer_model", "payer");
        $this->load->model("tour_model", "tour");
        $this->load->model("tourist_model", "tourist");
    }

    function create ()
    {
        $tour_id = $this->input->get("tour_id");
        $payer_id = $this->input->get("payer_id");
        $letter_id = $this->input->get("letter_id");
        $tour = $this->tour->get($tour_id);

        $payer = $this->payer->get_for_tour($payer_id, $tour_id);
        $payer->tourists = $this->tourist->get_by_payer($payer_id, $tour_id);
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
        if ($payer->is_comp == 1 || $payer->is_cancelled) {
            $price = 0;
            $rate = 0;
        }
        $payer->price = $price;
        $payer->room_rate = $rate;
        $tourist_count = $this->payer->get_tourist_count($payer->payer_id, $payer->tour_id);

        $payer->amt_due = ($price + $rate) * $tourist_count - ($payer->amt_paid + $payer->discount);

        $payer->tourist_count = $tourist_count;
        $data["tour"] = $tour;

        $merge_id = $this->merge->quick_insert($payer_id, $letter_id);
        $data["merge"] = $this->merge->get($merge_id);

        $data["payer"] = $payer;
        $data["letter"] = $this->letter->get($letter_id);
        $data["target"] = "merge/edit";
        $data["title"] = "Preparing a Merge Letter";
        $data["action"] = "insert";
        $this->load->view("page/index", $data);
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $field = $this->input->post("field");
        $value = $value = trim($this->input->post("value"));
        if (strstr($field, "date")) {
            $value = format_date($value, "mysql");
        }
        $values = array(
                $this->input->post("field") => $value
        );
        $this->merge->update($id, $values);
        echo $this->input->post("value");
    }

    function get_note ()
    {
        $id = $this->input->get("id");
        $data["merge"] = $this->merge->get($id);
        $this->load->view("merge/note", $data);
    }

    function create_note ()
    {
        $data["merge"] = NULL;
        $this->load->view("merge/note", $data);
    }
}