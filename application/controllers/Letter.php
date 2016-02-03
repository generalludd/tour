<?php
defined('BASEPATH') or exit('No direct script access allowed');

// letter.php Chris Dart Mar 14, 2014 9:32:59 PM chrisdart@cerebratorium.com
class Letter extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("letter_model", "letter");
        $this->load->model("tour_model", "tour");
        $this->load->model("payer_model", "payer");
    }

    function select ()
    {
        $payer_id = $this->input->get("payer_id");
        $tour_id = $this->input->get("tour_id");
        $data["payer_id"] = $payer_id;
        $data["tour_id"] = $tour_id;
        $data["letters"] = $this->letter->get_for_tour($tour_id);
        $this->load->view("letter/select", $data);
    }

    function view ()
    {
        $id = $this->uri->segment(3);
        $letter = $this->letter->get($id);
        $data["tour"] = $this->tour->get($letter->tour_id);
        $data["letter"] = $letter;
        $data["target"] = "letter/view";
        $data["title"] = sprintf("Viewing '%s' Letter for '%s' Tour", $letter->title, $data["tour"]->tour_name);
        $this->load->view("page/index", $data);
    }

    function create ()
    {
        $data["tour_id"] = $this->uri->segment(3);
        $data["tour"] = $this->tour->get($data["tour_id"]);
        $data["letter"] = NULL;
        $data["target"] = "letter/edit";
        $data["action"] = "insert";
        $data["title"] = "Editing Letter";
        $this->load->view("page/index", $data);
    }

    function insert ()
    {
        $id = $this->letter->insert();
        redirect("letter/view/$id");
    }

    function edit ()
    {
        $id = $this->uri->segment(3);
        $letter = $this->letter->get($id);
        $tour = $this->tour->get($letter->tour_id);
        $data["letter"] = $letter;
        $data["tour_id"] = $letter->tour_id;
        $data["tour"] = $tour;
        $data["target"] = "letter/edit";
        $data["title"] = sprintf("Editing '%s' Letter for %s' Tour", $letter->title, $tour->tour_name);
        $data["action"] = "update";
        $this->load->view("page/index", $data);
    }

    function update ()
    {
        $id = $this->input->post("id");
        $this->letter->update($id);
        redirect("letter/view/$id");
    }
    
    function delete(){
    	if($id = $this->input->post("id")){
    		$tour_id = $this->input->post("tour_id");
    		$this->letter->delete($id);
    		redirect("tour/view/$tour_id");
    	}
    }
}