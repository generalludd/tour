<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
// $data == array(type (a or span), class, id, href)
// @TODO Document this because it is pretty funky

/**
 *
 * @param array $data
 *
 * @return bool|string boolean array
 *         required:
 *         "text" key for the button text
 *         optional:
 *         "item" is not used here but is used by the create_button_bar script.
 *         this should be improved in a later version so it just focuses on
 *         either the class or id
 *         "type" defaults to "a" but can be "div" "span" or other tag if the
 *         type=>"pass-through" then it just returns the "text" as-is without
 *         any further processing
 *         "href" defaults to "#" is only used if "type" is "a" (default)
 *         "class" defaults to "button" but can be replaced by any other classes
 *         as defined in the css or javascript
 *         "id" is completely optional
 *         "enclosure" is an option array with type class and id keys. This is
 *         used if the particular button needs an added container (for AJAX
 *         manipulation)
 *        "data" is an array of compound arrays of key=> value pairs
 *
 *         EXAMPLES
 *         A button that provides a standard url (type and class are defaults
 *         "a" and "button");
 *         $data = array( "text" => "View Record", "href" =>
 *         "/index.php/record/view/2352");
 *         returns: <a href="/index.php/record/view/2352" class="button">View
 *         Record</a>
 *
 *         A button that triggers a jquery script by class with an id that is
 *         parsed by the jQuery to parse for a relevant database table key:
 *         $data = array( "text" => "Edit Record", "type" => "span", "class" =>
 *         "button edit-record" "id" => "er_2532" );
 *         returns <span class="button edit-record" id="er_2532">Edit
 *         Record</span>
 *
 *         A Button that needs a surrounding span for jQuery mainpulation:
 *         $data = array( "text" => "Edit Record", "type" => "span", "class" =>
 *         "button edit-record" "id" => "er_2532",
 *         "enclosure" => array("type" => "span", "id" => "edit-record-span" )
 *         );
 *         returns:<span id="edit-record-span"><span class="button edit-record"
 *         id="er_2532">Edit Record</span></span>
 */
function create_button(array $data): bool|string {
	if (array_key_exists("text", $data)) {
		$type = "a";
		$href = "";
		$title = "";
		$target = "";
		$text = $data["text"];
		if (array_key_exists("type", $data)) {
			if (isset($data["type"])) {
				$type = $data["type"];
			}
		}
		else {
			if (array_key_exists("href", $data)) {
				$href = "href='" . $data["href"] . "'";
			}
			else {
				$href = "href='#'";
			}
		}

		if (array_key_exists("target", $data)) {
			$target = "target='" . $data["target"] . "'";
		}

		if (array_key_exists("title", $data)) {
			$title = "title ='" . $data["title"] . "'";
		}
		$data_attributes = [];
		if (array_key_exists('data', $data)) {
			foreach ($data['data'] as $key => $value) {
				$data_attributes[] = sprintf('data-%s="%s"', $key, $value);
			}
		}
		if ($type != "pass-through") {

			if (array_key_exists("class", $data)) {
				if (!is_array($data["class"])) {
					$data["class"] = explode(" ", $data["class"]);
				}
			}
			else {
				$data["class"] = [
					"button",
				];
			}

			if (array_key_exists("selection", $data)) {
				if (preg_match("/" . str_replace("/", "\/", $data["selection"]) . "/", $_SERVER['REQUEST_URI'])) {
					$data["class"][] = "active";
				}
			}
			$class = sprintf("class='%s'", implode(" ", $data["class"]));

			$id = "";
			if (array_key_exists("id", $data)) {
				$id = "id='" . $data["id"] . "'";
			}
			$data_attributes = implode(' ', $data_attributes);
			$button = "<$type $href $id $class $target $title $data_attributes>$text</$type>";

			if (array_key_exists("enclosure", $data)) {
				if (array_key_exists("type", $data["enclosure"])) {
					$enc_type = $data["enclosure"]["type"];
					$enc_class = "";
					$enc_id = "";
					if (array_key_exists("class", $data["enclosure"])) {
						$enc_class = "class='" . $data["enclosure"]["class"] . "'";
					}
					if (array_key_exists("id", $data["enclosure"])) {
						$enc_id = "id='" . $data["enclosure"]["id"] . "'";
					}
					$button = "<$enc_type $enc_class $enc_id>$button</$enc_type>";
				}
			}

		}
		else {
			return $data["text"];
		}
		return $button;
	}
	else {
		return FALSE;
	}
}
function get_button_bar_object(array $buttons, array $options = []): object {
	$button_bar = new stdClass();
	$button_bar->classes = ['button-list'];
	if ($options) {
		if (!empty($options["id"])) {
			$button_bar->id = $options["id"];
		}

		if (!empty($options["selection"])) {
			$button_bar->selection = $options["selection"];
		}

		if (!empty($options["class"])) {
			if(!is_array($options["class"])){
				$options["class"] = [$options["class"]];
			}
			$button_bar->classes = array_merge($button_bar->classes,$options["class"]);
		}
	}
	$button_bar->buttons = [];

	// the "selection" option indicates the page in the interface. Currently as
	// indicated by the uri->segment(1)
	foreach ($buttons as $button) {
		if (array_key_exists("selection", $button) && !empty($button_bar->selection)) {
			if ($button["selection"] == $button_bar->selection) {

				if (!empty($button["class"])) {
					if(!is_array($button["class"])){
						$button["class"] = [$button["class"]];
					}
					$button["class"][]= "active";
				}
				else {
					$button["class"][] = "button";
				}
			}
		}
		$button_bar->buttons[] = $button;
	}
	$button_bar->options = $options;
	return $button_bar;
}
/**
 *
 * @param array $buttons
 * @param array $options
 *
 * @return string
 *
 * Using the create_button function, this accepts an array
 *         of button arrays
 *         and an optional array including id,
 *         selection, which is passed along to the create_button array.
 *         class, which provides class values for the button bar.
 */
function create_button_bar(array $buttons, array $options = []): string {
	$button_bar = get_button_bar_object($buttons, $options);
	$classes = implode(' ', $button_bar->classes);
	$id = '';
	if(!empty($button_bar->id)){
		$id = 'id="' . $button_bar->id . '"';
	}
	$output_buttons = [];
	foreach($button_bar->buttons as $button){
		$output_buttons[] = create_button($button);
	}
	$rendered_buttons = '<div class="button-item">';
	$rendered_buttons .= implode("</div><div class='button-item'>", $output_buttons);
	$rendered_buttons .= '</div>';
	$template = "<div class='$classes'>$rendered_buttons</div>";
	$output = "<div class='button-box' $id >$template</div>";
	return $output;
}

/**
 * create a field set that can be optionally edited with AJAX on the fly.
 *
 * @param string $field_name
 * @param string $value
 * @param string $label
 * @param array $options (envelope, class, attributes)
 */
function create_field(string $field_name, string $value = NULL, string $label, array $options = []): string {
	$envelope = 'p';
	if (array_key_exists('envelope', $options)) {
		$envelope = $options['envelope'];
	}

	$id = sprintf("id='%s'", $field_name);
	if (array_key_exists('id', $options)) {
		$id = sprintf("id='%s_%s'", $field_name, $options['id']);
	}

	/*
	 * The id is split with the "-" delimiter in javascript when the field is
	 * clicked
	 */
	$output[] = sprintf("<%s class='field-envelope' id='field-%s'>", $envelope, $field_name);
	if ($label) {
		$output[] = sprintf("<label>%s:&nbsp;</label>", $label);
	}
	if ($value == "") {
		$value = "&nbsp;";
	}

	$classes[] = 'field';

	/* add additional classes to the actual field */
	if (array_key_exists('editable', $options)) {
		$classes[] = 'edit-field';
	}

	if (array_key_exists('class', $options)) {
		$classes[] = $options['class'];
	}
	$field_class = implode(' ', $classes);
	$format = '';
	if (array_key_exists('format', $options)) {
		$format = sprintf("format='%s'", $options['format']);
		if ($options['format'] == 'url' && $value != '&nbsp;') {
			$value = sprintf("<a href='%s' target='_blank'>%s</a>", $value, $value);
		}
		elseif ($options['format'] == 'email' && $value != '&nbsp;') {
			$value = sprintf("<a href='mailto:%s'>%s</a>", $value, $value);
		}
	}
	$title = "";
	if (array_key_exists('title', $options)) {
		$title = sprintf("title='%s'", $options['title']);
	}
	/*
	 * Attributes are non-standard html attributes that are used by javascript
	 * these can include the type of input to be generated
	 */
	$attributes = '';
	if (array_key_exists('attributes', $options)) {
		$attributes = $options['attributes'];
	}

	$output[] = sprintf("<span class='%s' %s %s %s %s>%s</span></%s>", $field_class, $title, $attributes, $format, $id, $value, $envelope);
	return implode("\r", $output);
}


/**
 * create a displayed field easily on complex forms for AJAX-ready in-place
 * editing.
 *
 * @param string $field_name
 * @param string $value
 * @param string $label
 * @param array $options
 *          (envelope, class, attributes)
 *
 * @return string
 */
function edit_field($field_name, $value, $label, $table, $id, $options = []): string {
	$envelope = "p";
	if (array_key_exists("envelope", $options)) {
		$envelope = $options ["envelope"];
	}

	/* The id is split with the "-" delimiter in javascript when the field is clicked */
	$output [] = sprintf("<%s class='field-envelope' id='%s__%s__%s'>", $envelope, $table, $field_name, $id);
	if ($label) {
		$output [] = sprintf("<label>%s:&nbsp;</label>", $label);
	}
	if ($value == "") {
		$value = "&nbsp;";
	}

	/* add additional classes to the actual field */
	$classes [] = "edit-field field";
	if (array_key_exists("class", $options)) {
		$classes [] = $options ["class"];
	}
	$field_class = implode(" ", $classes);
	$format = "";
	if (array_key_exists("format", $options)) {
		$format = sprintf("format='%s'", $options ["format"]);
	}


	/*
	 * Attributes are non-standard html attributes that are used by javascript these can include the type of input to be generated
	*/
	$attributes = "";
	if (array_key_exists("attributes", $options)) {
		$attributes = $options ["attributes"];
	}
	$output [] = sprintf("<span class='%s' %s %s name='%s'>%s</span></%s>", $field_class, $attributes, $format, $field_name, $value, $envelope);
	return implode("\r", $output);

}

function create_input($object, $name, $label, $options = []): string {
	$id = $name;
	if (array_key_exists("id", $options)) {
		$id = $options["id"];
	}
	$class = "";
	if (array_key_exists("class", $options)) {
		$class = $options["class"];
	}

	$required = "";
	if (array_key_exists("required", $options)) {
		$required = "required";
	}

	$label_class = "";
	if (array_key_exists("label_class", $options)) {
		$label_class = $options["label_class"];
	}
	$default_value = array_key_exists('default', $options) ? $options['default'] : NULL;

	$value = get_value($object, $name, $default_value);

	$envelope = "p";
	if (array_key_exists("envelope", $options)) {
		$envelope = $options["envelope"];
	}

	$envelope_class = "input-block";
	if (array_key_exists("envelope_class", $options)) {
		$envelope_class = sprintf("%s %s", $envelope_class, $options["envelope_class"]);
	}
	$format = FALSE;
	if (array_key_exists("format", $options)) {
		$format = $options["format"];
	}
	if ($format) {
		if ($format == "money") {
			$value = format_money($value);
		}
	}
	$type = "text";
	if (array_key_exists("type", $options)) {
		$type = $options["type"];
		if ($type == "date") {
			$type = "date";
		}
	}
	$checked = "";
	if ($type == "checkbox" && $value == 1) {
		$checked = "checked";
	}
	return sprintf("<%s class='%s'><label for='%s' class='%s'>%s: </label><input type='%s' name='%s' id='%s' value='%s' class='input %s' %s %s/></%s>", $envelope,
		$envelope_class, $name, $label_class, $label, $type, $name, $id, $value, $class, $checked, $required, $envelope);
}

/**
 * create a checkbox with labels
 *
 * @param string $name
 * @param array $values
 * @param array $selections @TODO add id option
 */
function create_checkbox(string $name, array $values, array $selections = []): string {
	$output = [];
	foreach ($values as $value) {
		$checked = "";
		if (in_array($value->value, $selections)) {
			$checked = "checked";
		}
		$output[] = sprintf("<label>%s</label><input type='checkbox' name='%s' value='%s' %s/>", $value->value, $name, $value->value, $checked);
	}
	return implode("\r", $output);
}

function create_dropdown($name, $values, $options = []): void {
	if (array_key_exists("envelope", $options)) {
	}
}

function get_page_title(string $title = NULL): string {
	$output = 'Ball Park Tours';
	if(!empty($title)){
		$output .= ': ' . $title;
	}
	return $output;
}

/**
 * @return string[]
 */
function veterans_choices(): array {
	return [
		'0' => '-Any-',
		'1' => 'Veterans',
		'-1' => 'Non-Veterans',
	];
}
