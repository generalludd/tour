<?php
defined('BASEPATH') or exit('No direct script access allowed');

// receipt.php Chris Dart Jan 15, 2014 8:33:42 PM chrisdart@cerebratorium.com
class receipt extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("receipt_model", "receipt");
        $this->load->model("message_model", "message");
    }

    function view_all ($message_id = NULL)
    {
        if (! $message_id) {
            $message_id = $this->input->get("message_id");
        }
        $data["receipts"] = $this->receipt->get_all($message_id);
        $tour_id = $data["receipts"][0]->tour_id;
        $this->load->model("tour_model", "tour");
        $data["tour"] = $this->tour->get($tour_id);
        $data["title"] = "Message receipts";
        $data["target"] = "receipt/list";
        $this->load->view("page/index", $data);
    }

    function edit ()
    {
        $id = $this->input->get("id");
        $data["receipt"] = $this->receipt->get($id);
        $data["action"] = "update";
        $this->load->view("receipt/edit", $data);
    }

    function send ($id = NULL)
    {
        $receipt = $this->receipt->get($id);
        $this->load->library("email");
        if ($receipt->email) {

            $this->email->from($receipt->email);
            $this->email->to("chris@cerebratorium.com");
            $this->email->subject($receipt->subject);
            $this->email->message($receipt->body);
            $this->email->send();
            $this->receipt->update_value($receipt->id, array(
                    "status" => 1
            ));
            sleep(1);
        }
        redirect("receipt/view_all/$receipt->message_id");
    }

    function update ()
    {
        $id = $this->input->post("id");
        $this->receipt->update($id);
        if ($this->input->post("resend") == 1) {
            $this->send($id);
        }
    }
}