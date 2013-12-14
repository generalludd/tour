<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tour.php Chris Dart Dec 13, 2013 7:53:18 PM chrisdart@cerebratorium.com
class Tour extends MY_Controller
{

    function __construct ()
    {

        parent::__construct();
        $this->load->model("tour_model", "tour");
        $this->load->model("data_model");
    }

    function view ()
    {
        $data["tour"] = $this->tour->get(1);
        $data["fields"] = $this->data_model->get_fields("tour");
        $data["title"] = sprintf("Tour Data: %s", $data["tour"]->tour_name );
        $data["target"] = "tour/view";
        $this->load->view("page/index", $data);

    }

    function create ()
    {

    }

    function insert ()
    {
}

    function update ()
    {
}

    function update_value ()
    {

        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim(
                        $this->input->post("value"))
        );
        $this->tour->update($id, $values);
        echo $this->input->post("value");
    }
}