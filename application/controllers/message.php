<?php
defined('BASEPATH') or exit('No direct script access allowed');

// message.php Chris Dart Jan 13, 2014 8:41:02 PM chrisdart@cerebratorium.com
class Message extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("message_model", "message");
        $this->load->helper("message_helper");
    }

    function create ()
    {
        $tour_id = $this->input->get("tour_id");
        $this->load->model("tour_model", "tour");
        $data["tour"] = $this->tour->get($tour_id);

        $data["tour_id"] = $tour_id;
        $data["message"] = NULL;
        $data["action"] = "Send";
        $data["target"] = "message/edit";
        $data["title"] = "New Message for Tour";
        $this->load->view("page/index", $data);
    }

    function send ()
    {
        $this->load->model("payer_model", "payer");
        $tour_id = $this->input->post("tour_id");
        $data["payers"] = $this->payer->get_payers($tour_id);

        $id = $this->message->insert();
        $data["message"] = $this->message->get($id);

        $this->prepare($data);
        $options = array();
        $options["email_only"] = TRUE;
        $veterans_only = FALSE;
        if ($this->input->get("veterans_only") == 1) {
            $options["veterans_only"] = TRUE;
        }

        $receipts = $this->receipt->get_all($id);

        $this->load->library("email");

        foreach ($receipts as $receipt) {
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
        }
    }



    function insert ()
    {
        $this->message->insert();
    }

    function prepare ($data)
    {
        $this->load->model("tourist_model", "tourist");
        $this->load->model("receipt_model", "receipt");

        $payers = $data["payers"];
        $message = $data["message"];
        foreach ($payers as $payer) {
            if ($payer->email) {
                $tourists = $this->tourist->get_for_payer($payer->payer_id, $payer->tour_id);
                $amt_due = get_amt_due(get_tour_price($payer), get_room_rate($payer), count($tourists), $payer->discount, $payer->amt_paid);
                $names = format_salutation($tourists);
                $text = replace_text($message->body, "NAMES", $names);
                $text = replace_text($text, "AMOUNT-PAID", number_format($payer->amt_paid, 2));
                $text = replace_text($text, "AMOUNT-DUE", number_format($amt_due, 2));
                $this->receipt->insert($message->id, $payer->payer_id, 0, $text);
            }
        }
    }
}