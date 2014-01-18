<?php defined('BASEPATH') OR exit('No direct script access allowed');

// email.php Chris Dart Jan 12, 2014 10:02:22 PM chrisdart@cerebratorium.com

class Email extends MY_Controller
{

    function __construct(){
        parent::__construct();
        $this->load->model("person_model","person");
        $this->load->library("email");
    }


    function email_people ()
    {
        $options = array();
        $options["email_only"] = TRUE;
        $veterans_only = FALSE;
        if ($this->input->get("veterans_only") == 1) {
            $options["veterans_only"] = TRUE;
        }
        $people = $this->person->get_all($options);

       // $body = $this->input->post("body");
$message = "hello";
$subject = "message";
       // $subject = $this->input->post("subject");
        foreach ($people as $person) {
            $this->email->from($person->email);
            $this->email->to("chris@cerebratorium.com");
            $this->email->cc($person->email);

            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            echo "$person->email<br/>";
            sleep(1);
        }
    }


}