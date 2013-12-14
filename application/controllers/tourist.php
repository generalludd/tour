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
        $this->tourist->update($id, $values);
        echo $this->input->post("value");
    }
}