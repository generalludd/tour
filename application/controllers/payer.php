<?php
defined('BASEPATH') or exit('No direct script access allowed');

// payer.php Chris Dart Dec 13, 2013 7:53:31 PM chrisdart@cerebratorium.com
class Payer extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("payer_model", "payer");
        $this->load->model("tourist_model", "tourist");
    }

    function view ()
    {
    }

    function create ()
    {
        $this->load->model("variable_model", "variable");
        $payer_id = $this->input->get("payer_id");
        $data["payer_id"] = $payer_id;
        $tour_id = $this->input->get("tour_id");
        $data["tour_id"] = $tour_id;
        // create a new records in payer and tourist that will be loaded here.
        // This allows the creation
        // of at least one tourist record as well.
        $this->payer->insert($payer_id, $tour_id);
        // create the intial payer record for this payer-as-tourist
        $this->tourist->insert_payer($payer_id, $tour_id);
        $data["room_sizes"] = get_keyed_pairs($this->variable->get_pairs("room_size"), array(
                "value",
                "name"
        ));
        $data["payment_types"] = get_keyed_pairs($this->variable->get_pairs("payment_type"), array(
                "value",
                "name"
        ));
        $data["payer"] = $this->payer->get_for_tour($payer_id, $tour_id);
        $data["tourists"] = $this->tourist->get_by_payer($payer_id, $tour_id);
        $data["room_rate"] = 0;
        $data["tour_price"] = 0;
        $data["target"] = "payer/edit";
        $data["title"] = "Creating Payer";
        $data["action"] = "update";
        $this->load->view("payer/edit", $data);
    }

    /**
     * This script can be run via url or called internally such as when it is
     * called in $this->insert() function
     *
     * @param string $payer_id
     * @param string $tour_id
     */
    function edit ($payer_id = FALSE, $tour_id = FALSE)
    {
        $this->load->model("variable_model", "variable");
        if (! $payer_id) {
            $payer_id = $this->input->get("payer_id");
        }
        $data["payer_id"] = $payer_id;

        if (! $tour_id) {
            $tour_id = $this->input->get("tour_id");
        }
        $data["tour_id"] = $tour_id;

        $data["room_sizes"] = get_keyed_pairs($this->variable->get_pairs("room_size"), array(
                "value",
                "name"
        ));
        $data["payment_types"] = get_keyed_pairs($this->variable->get_pairs("payment_type"), array(
                "value",
                "name"
        ));
        $payer = $this->payer->get_for_tour($payer_id, $tour_id);

        $data["payer"] = $payer;
        $data["tourists"] = $this->tourist->get_by_payer($payer_id, $tour_id);

        switch ($payer->payment_type) {
            case "full_price":
                $tour_price = $payer->full_price;
                break;
            case "banquet_price":
                $tour_price = $payer->banquet_price;
                break;
            case "early_price":
                $tour_price = $payer->early_price;
                break;
            case "regular_price":
                $tour_price = $payer->regular_price;
                break;
            default:
                $tour_price = 0;
                break;
        }

        switch ($payer->room_size) {
            case "single_room":
                $room_rate = $payer->single_room;
                break;
            case "triple_room":
                $room_rate = $payer->triple_room;
                break;
            case "quad_room":
                $room_rate = $payer->quad_room;
                break;
            default:
                $room_rate = 0;
                break;
        }
        if ($payer->is_comp == 1) {
            $tour_price = 0;
            $room_rate = 0;
        }
        $data["room_rate"] = $room_rate;
        $data["tour_price"] = $tour_price;
        $data["target"] = "payer/edit";
        $data["title"] = "Editing Payer";
        $data["action"] = "update";
        $this->load->view("payer/edit", $data);
    }

    /**
     * there is no alternative if this is called without the ajax=1 post query
     * variable.
     */
    function insert ()
    {
        $payer_id = $this->input->post("payer_id");
        $tour_id = $this->input->post("tour_id");
        $this->payer->insert($payer_id, $tour_id);
        $this->tourist->insert(array(
                "payer_id" => $payer_id,
                "tour_id" => $tour_id,
                "person_id" => $payer_id
        ));
        if ($this->input->post("ajax") == 1) {
            $this->edit($payer_id, $tour_id);
        }
    }

    function update ()
    {
        $payer_id = $this->input->post("payer_id");
        $tour_id = $this->input->post("tour_id");
        $this->payer->update($payer_id, $tour_id);
        redirect("/tourist/view_all/$tour_id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
        );
        $this->payer->update($id, $values);
        echo $this->input->post("value");
    }

    function select_tourists ()
    {
        $data["action"] = "select_tourist";
        $data["tour_id"] = $this->input->get("tour_id");
        $data["payer_id"] = $this->input->get("payer_id");
        $this->load->view("tourist/mini_selector", $data);
    }

    function select_payer ()
    {
        $this->load->model("person_model", "person");
        $this->load->model("tour_model", "tour");
        $tour_id = $this->input->get("tour_id");
        $data["tour_name"] = $this->tour->get_value($tour_id, "tour_name");
        $data["tour_id"] = $tour_id;
        $person_id = $this->input->get("person_id");
        $tourist = $this->person->get($person_id, "first_name,last_name");
        $data["tourist_name"] = sprintf("%s %s", $tourist->first_name, $tourist->last_name);
        $data["tourist_id"] = $person_id;
        $data["payers"] = $this->payer->get_payers($tour_id);

        $this->load->view("payer/select_list", $data);
    }

    function delete ()
    {
        $payer_id = $this->input->post("payer_id");
        $tour_id = $this->input->post("tour_id");
        $this->payer->delete($payer_id, $tour_id);
        $this->tourist->delete_payer($payer_id, $tour_id);
        $this->load->model("roommate_model", "roommate");
        $this->roommate->delete_payer($payer_id, $tour_id);
    }
}
