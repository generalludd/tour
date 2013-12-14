<?php
defined('BASEPATH') or exit('No direct script access allowed');

// tour_model.php Chris Dart Dec 13, 2013 7:57:28 PM chrisdart@cerebratorium.com
class Tour_model extends MY_Model
{
    var $tour_name;
    var $start_date;
    var $end_date;
    var $due_date;
    var $full_price;
    var $banquet_price;
    var $early_price;
    var $regular_price;
    var $single_rate;
    var $triple_rate;
    var $quad_rate;

    function __construct ()
    {

        parent::__construct();
    }

    function prepare_variables ()
    {

        $variables = array(
                "tour_name",
                "start_date",
                "end_date",
                "due_date",
                "full_price",
                "banquet_price",
                "early_price",
                "regular_price",
                "single_rate",
                "triple_rate",
                "quad_rate"
        );
        prepare_variables($this, $variables);
    }

    function get ($id)
    {

       return parent::get("tour", $id, $values = array());
    }

    function update ($id, $values = array())
    {

        parent::update("tour", $id, $values);
    }
}