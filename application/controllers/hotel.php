<?php
defined('BASEPATH') or exit('No direct script access allowed');

// hotel.php Chris Dart Dec 28, 2013 10:08:31 PM chrisdart@cerebratorium.com
class Hotel extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("hotel_model", "hotel");
    }

    function view ()
    {
        $hotel_id = $this->uri->segment(3);
        $hotel = $this->hotel->get($hotel_id);
        $data["hotel"] = $hotel;
        $data["target"] = "hotel/view";
        $data["title"] = sprintf("Viewing Details for Hotel: %s", $hotel->hotel_name);
        $this->load->view("page/index", $data);
    }

    function view_all ()
    {
        $tour_id = $this->uri->segment(3);
        $this->load->model("tour_model", "tour");
        $tour = $this->tour->get($tour_id, "id, tour_name");
        $data["hotels"] = $this->hotel->get_for_tour($tour_id);
        $data["target"] = "hotel/list";
        $data["title"] = sprintf("Showing Hotels for Tour: %s", $tour->tour_name);
        $data["tour"] = $tour;
        $this->load->view("page/index", $data);
    }

    function create ()
    {
        $tour_id = $this->input->get("tour_id");
        $this->load->model("tour_model", "tour");
        $tour_list = $this->tour->get_all(TRUE, "tour_name,id");
        $tour = $this->tour->get($tour_id, "id, tour_name");
        $data["tour"] = $tour;

        $data["tour_list"] = get_keyed_pairs($tour_list, array(
                "id",
                "tour_name"
        ), TRUE);
        $data["hotel"] = NULL;
        $data["action"] = "insert";
        if ($this->input->get("ajax")) {
            $this->load->view("hotel/edit", $data);
        }
    }

    function insert ()
    {
        $id = $this->hotel->insert();
        redirect("hotel/view/$id");
    }

    function edit ()
    {
        $hotel_id = $this->input->get("id");
        $hotel = $this->hotel->get($hotel_id);
        $this->load->model("tour_model", "tour");
        $tour_list = $this->tour->get_all(FALSE, "tour_name,id");
        $tour = $this->tour->get($hotel->tour_id, "id, tour_name");
        $data["tour"] = $tour;

        $data["tour_list"] = get_keyed_pairs($tour_list, array(
                "id",
                "tour_name"
        ), TRUE);
        $data["hotel"] = $hotel;
        $data["action"] = "update";
        if ($this->input->get("ajax")) {
            $this->load->view("hotel/edit", $data);
        }
    }

    function update ()
    {
        $id = $this->input->post("id");
        $this->hotel->update($id);
        redirect("hotel/view/$id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $value = trim($this->input->post("value"));
        $field = $this->input->post("field");
        if (strpos($field, "date")) {
            $value = format_date($value, "mysql");
        }
        $values = array(
                $field => $value
        );
        $this->hotel->update($id, $values);
        echo $this->input->post("value");
    }
}