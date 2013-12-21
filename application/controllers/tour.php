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
        $values = array(
                $this->input->post("field") => $value = trim($this->input->post("value"))
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
                    $output = format_date($output->$field);
                    break;
                case "money":
                    $output = format_money($output->$field);
                    break;
            }
        }
        if ($this->input->get("ajax") == 1) {
            echo $output->$field;
        } else {
            return $output->$field;
        }
    }

    function show_current ()
    {
        $id = $this->input->get("id");
        $data["id"] = $id;
        $data["tours"] = $this->tour->get_current($id);
        $data["query"] = $this->db->last_query();
        $this->load->view("tour/select", $data);
    }
}
