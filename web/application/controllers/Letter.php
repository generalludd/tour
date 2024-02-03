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

    function select ($payer_id, $tour_id)
    {
        $data["payer_id"] = $payer_id;
        $data["tour_id"] = $tour_id;
        $data["letters"] = $this->letter->get_for_tour($tour_id);
        $data['target'] = 'letter/select';
        $data['title'] = 'Select a letter';
        if($this->input->get('ajax')) {
					$this->load->view($data['target'], $data);
				}
        else{
        	$this->load->view('page/index', $data);
				}
    }

    function view (): void {
        $id = $this->uri->segment(3);
        $letter = $this->letter->get($id);
        $data["tour"] = $this->tour->get($letter->tour_id);
        $data["letter"] = $letter;
        $data["target"] = "letter/view";
				$data['scripts'] = [site_url('js/letter.js')];
        $data["title"] = sprintf("Viewing '%s' Letter for '%s' Tour", $letter->title, $data["tour"]->tour_name);
        $this->load->view("page/index", $data);
    }

    function create (): void {
        $data["tour_id"] = $this->uri->segment(3);
        $data["tour"] = $this->tour->get($data["tour_id"]);
        $data["letter"] = NULL;
        $data["target"] = "letter/edit";
        $data["action"] = "insert";
        $data["title"] = "Editing Letter";
        $this->load->view("page/index", $data);
    }

    function insert (): void {
        $id = $this->letter->insert();
        redirect("letter/view/$id");
    }

    function edit (): void {
        $id = $this->uri->segment(3);
        $letter = $this->letter->get($id);
        $tour = $this->tour->get($letter->tour_id);
        $data["letter"] = $letter;
        $data["tour_id"] = $letter->tour_id;
        $data["tour"] = $tour;
        $data["target"] = "letter/edit";
        $data["title"] = sprintf("Editing '%s' Letter for %s' Tour", $letter->title, $tour->tour_name);
        $data["action"] = "update";
				$data['scripts'] = [site_url('js/letter.js')];

			$this->load->view("page/index", $data);
    }

    function update (): void {
        $id = $this->input->post("id");
        $this->letter->update($id);
				$this->session->set_flashdata('notice', 'Letter template updated.');
			var_dump($this->session->flashData());

			redirect("letter/view/$id");
    }
    
    function delete(): void {
			$input_stream  = json_decode($this->input->raw_input_stream, true);
    	if($id = $input_stream['id']){
    		$tour_id = $input_stream['tour_id'];
    		$this->letter->delete($id);
				$this->session->set_flashdata('notice', 'Letter template deleted.');
				var_dump($this->session->flashData());

				redirect("tours/view/$tour_id");
    	}
    }
}
