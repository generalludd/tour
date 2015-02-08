<?php
defined('BASEPATH') or exit('No direct script access allowed');

// index.php Chris Dart Dec 10, 2013 8:14:38 PM chrisdart@cerebratorium.com
class Person extends MY_Controller
{

    function __construct ()
    {
        parent::__construct();
        $this->load->model("person_model", "person");
        $this->load->model("phone_model", "phone");
        $this->load->model("address_model", "address");
    }

    function index ()
    {
    	 
        $this->view_all();
    }

    function view ()
    {
        $id = $this->uri->segment(3);

        $data = array();
        // $data["person"] = array();

        $person = $this->person->get($id);
        $phones = $this->phone->get_for_person($id);
        $person->phones = $phones;
        $person->address = $this->address->get($person->address_id);
        $person->housemates = $this->person->get_housemates($person->address_id, $person->id);
        $data["id"] = $id;
        $data["person"] = $person;
        $data["title"] = sprintf("Person Record: %s %s", $data["person"]->first_name, $data["person"]->last_name);
        $data["target"] = "person/view";
        $this->load->view("page/index", $data);
    }

    function view_next ()
    {
        $id = $this->uri->segment(3);
        $next_id = $this->person->get_next_person($id);
        redirect("person/view/$next_id");
    }

    function view_previous ()
    {
        $id = $this->uri->segment(3);
        $next_id = $this->person->get_previous_person($id);
        redirect("person/view/$next_id");
    }

    function view_all ($options = array())
    {
    
    	
        burn_cookie("person_filter");
        $filters = array();
        $initial = FALSE;
        if ($this->input->get("initial")) {
            $initial = $this->input->get("initial");
            $filters["initial"] = $initial;
        }
        if ($this->input->get("veterans_only")) {
            $filters["veterans_only"] = TRUE;
        }
        if ($this->input->get("email_only")) {
            $filters["email_only"] = TRUE;
        }
        if ($this->input->get("show_disabled")) {
            $filters["show_disabled"] = TRUE;
        }
        // get the list of letters for each of the first initials of last names
        // in the people table
        $data["initials"] = $this->person->get_initials();
        $data["people"] = $this->person->get_all($filters);
        bake_cookie("person_filters", $filters);
        $data["filters"] = $filters;
        $data["title"] = "Address Book";
        $data["target"] = "person/list";

        $this->load->view("page/index", $data);
    }

    function find_by_name ()
    {
        $name = $this->input->get("name");
        $tour_id = FALSE;
        if ($this->input->get("tour_id")) {
            $tour_id = $this->input->get("tour_id");
        }
        $payer_id = FALSE;
        if ($this->input->get("payer_id")) {
            $payer_id = $this->input->get("payer_id");
        }
        $data["payer_id"] = $payer_id;
        $data["tour_id"] = $tour_id;
        $target = "person/mini_list";
        $data["people"] = $this->person->find_people($name, array(
                "payer_id" => $payer_id,
                "tour_id" => $tour_id
        ));
        $this->load->view($target, $data);
    }

    function find_for_address ()
    {
        $name = $this->input->get("name");
        $data["people"] = $this->person->find_people($name, array(
                "has_address" => TRUE
        ));
        $target = "address/mini_list";
        $this->load->view($target, $data);
    }

    function edit ()
    {
        $id = $this->uri->segment(3);
        $data = array();
        $data["person"] = $this->person->get($id);
        $data["title"] = sprintf("Person Record: %s %s", $data["person"]->first_name, $data["person"]->last_name);
        $this->load->model("variable_model", "variable");
        $shirt_sizes = $this->variable->get_pairs("shirt_size");
        $data["shirt_sizes"] = get_keyed_pairs($shirt_sizes, array(
                "value",
                "name"
        ), TRUE);
        $this->load->model("tourist_model", "tourist");
        $data["tour_count"] = count($this->tourist->get($id));
        $data["target"] = "person/edit";
        $data["action"] = "update";
        if ($this->input->get("ajax") == 1) {
            $this->load->view($data["target"], $data);
        } else {
            $this->load->view("page/index", $data);
        }
    }

    function create ()
    {
        // create a record in the db and get the insertion id. Then go to the
        // edit user page with
        $data["person"] = FALSE;
        $data["tour_count"] = 0;
        $this->load->model("variable_model", "variable");
        $shirt_sizes = $this->variable->get_pairs("shirt_size");
        $data["shirt_sizes"] = get_keyed_pairs($shirt_sizes, array(
                "value",
                "name"
        ), TRUE);
        $data["action"] = "insert";
        $this->load->view("person/edit", $data);
    }

    function add_housemate ()
    {
        $data["person"] = (object) array();
        $data["person"]->address_id = $this->input->post("address_id");
        $this->load->model("variable_model", "variable");
        $shirt_sizes = $this->variable->get_pairs("shirt_size");
        $data["shirt_sizes"] = get_keyed_pairs($shirt_sizes, array(
                "value",
                "name"
        ), TRUE);
        $data["action"] = "insert";
        $this->load->view("person/edit", $data);
    }

    function insert ()
    {
        $person_id = $this->person->insert(FALSE);
        redirect("person/view/$person_id");
    }

    function update ()
    {
        $id = $this->input->post("id");
        $this->person->update($id);
        redirect("person/view/$id");
    }

    function update_value ()
    {
        $id = $this->input->post("id");
        $values = array(
                $this->input->post("field") => trim($this->input->post("value"))
        );
        $this->person->update($id, $values);
    }

    function show_filter ()
    {
        $data["initials"] = get_keyed_pairs($this->person->get_initials(), array(
                "initial",
                "initial"
        ), TRUE);
        $this->load->view("person/filter", $data);
    }

    function export ()
    {
       /*  $options = $this->input->cookie("person_filters");
        $options = unserialize($options); */
        $this->export_addresses();
        
    }

    function export_addresses ($options = NULL)
    {
        $options["export"] = TRUE;
        $this->load->model("address_model", "address");
        $data["addresses"] = $this->address->get_all($options);
        $data['target'] = 'Address Export';
        $data['title'] = "Export of Addresses";
        $this->load->helper('download');
        print_r($data);
        die();
        $this->load->view('address/export', $data);
    }

    /**
     * the person_model determines whether to delete a person or just disable
     * them.
     * If a person is in the tourist database they will only be disabled.
     * See person_model->delete for more details.
     */
    function delete ()
    {
        $id = $this->input->get_post("id");
        if ($id) {
            $this->person->delete($id);
        }
        redirect("person/view_all");
    }

    function restore ()
    {
        $id = $this->input->post("id");
        if ($id) {
            $this->person->restore($id);
        }
    }
}