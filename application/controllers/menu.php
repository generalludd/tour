<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model("variable_model", "variable");
	}

	/**
	 * AJAX function to create quick-edit dropdown <select> fields (for use with the field editing AJAX functions in general.js
	 */
	function get_dropdown()
	{
		$category = $this->input->get("category");
		$value = $this->input->get("value");
		$field = $this->input->get("field");
		$categories = $this->variable->get_pairs($category, array("field"=>"value","direction"=>"ASC"));
		$pairs = get_keyed_pairs($categories, array("name","value"));
		echo form_dropdown($field, $pairs, $value, "class='save-field'");
	}

	/**
	 * AJAX function to create a quick-edit multiselect <select multiple="multiple"> fields (for use with the field editing AJAX functions in general.js
	 */
	function get_multiselect()
	{
		$category = $this->input->get("category");
		$value = explode(",",$this->input->get("value"));
		$field = $this->input->get("field");

		$categories = $this->variable->get_pairs($category, array("field"=>"value","direction"=>"ASC"));
		$pairs = get_keyed_pairs($categories, array("name","value"));

		$output = array();
		$output[] = form_multiselect($field, $pairs, $value, "id='$field'" );
		$buttons =  implode(" ", $output);
		echo $buttons . sprintf("<span class='button save-multiselect' target='%s'>Save</span>", $field);
	}


	/**
	 * This function is not currently used because parsing check boxes in AJAX is a pain in the ass.
	 */
	function get_checkbox()
	{

		$category = $this->input->get("category");
		$value = $this->input->get("value");
		$field = $this->input->get("field");
		$categories = $this->variable->get_pairs($category, array("field"=>"value","direction"=>"ASC"));
		$pairs = get_keyed_pairs($categories, array("name","value"));

		$output = array();
		for($i = 0; $i < count($categories); $i++){
			$checked = "";
			$item = $categories[$i];
			if($item->value == $value){
				$checked = "checked";
			}

			$output[] = sprintf("<label for='%s'>%s</label><input type='checkbox' name='%s[$i]' id='%s' value='%s' %s/>",
					 $item->value,$item->value,$field,$field,  $item->value, $checked);
		}
		$buttons =  implode(" ", $output);
		echo $buttons . sprintf("<span class='button save-checkbox' target='%s'>Save</span>", $field);

	}



}