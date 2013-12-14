<?php defined('BASEPATH') OR exit('No direct script access allowed');

// payer.php Chris Dart Dec 13, 2013 7:53:31 PM chrisdart@cerebratorium.com

class Payer extends MY_Controller
{

    function __construct ()
    {

        parent::__construct();
        $this->load->model("payer_model", "payer");
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

    function update(){

    }

    function update_value ()
    {

        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => $value = trim(
                        $this->input->post("value"))
        );
        $this->payer->update($id, $values);
        echo $this->input->post("value");
    }
}