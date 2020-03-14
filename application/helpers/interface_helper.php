<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
// $data == array(type (a or span), class, id, href)
// @TODO Document this because it is pretty funky

/**
 *
 * @param array $data
 * @return string boolean array
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
 * 				"data" is an array of compound arrays of key=> value pairs
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
 *
 */
function create_button ($data)
{
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
        } else {
            if (array_key_exists("href", $data)) {
                $href = "href='" . $data["href"] . "'";
            } else {
                $href = "href='#'";
            }
        }

        if (array_key_exists("target", $data)) {
            $target = "target='" . $data["target"] . "'";
        }

        if (array_key_exists("title", $data)) {
            $title = "title ='" . $data["title"] . "'";
        }
			$data_attributes= [];
			if(array_key_exists('data', $data)){
        	foreach ($data['data'] as $key=>$value){
        		$data_attributes[] = sprintf('data-%s="%s"', $key, $value);
					}
				}
        if ($type != "pass-through") {

            if (array_key_exists("class", $data)) {
                if (! is_array($data["class"])) {
                    $data["class"] = array(
                            $data["class"]
                    );
                }
            } else {
                $data["class"] = array(
                        "button"
                );
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
        } else {
            return $data["text"];
        }
        return $button;
    } else {
        return FALSE;
    }
}

/**
 *
 * @param compound array $buttons
 * @param array $options
 * @return string Using the create_button function aove, this accepts an array
 *         of button arrays
 *         and an optional array including id,
 *         selection, which is passed along to the create_button array.
 *         class, which provides class values for the button bar.
 */
function create_button_bar ($buttons, $options = NULL)
{
    $id = "";
    $selection = "";
    $class = "mini";
    if ($options) {
        if (array_key_exists("id", $options)) {
            $id = sprintf("id='%s'", $options["id"]);
        }

        if (array_key_exists("selection", $options)) {
            $selection = $options["selection"];
        }

        if (array_key_exists("class", $options)) {
            $class = $options["class"];
        }
    }
    $button_list = array();

    // the "selection" option indicates the page in the interface. Currently as
    // indicated by the uri->segment(1)
    foreach ($buttons as $button) {
        if (array_key_exists("selection", $button)) {
            if ($button["selection"] == $selection) {

                if (array_key_exists("class", $button)) {
                    $button["class"] .= " active";
                } else {
                    $button["class"] = "button active";
                }
            }
        }
        $button_list[] = create_button($button);
    }

    $contents = implode("</li><li>", $button_list);
    $template = "<ul class='button-list'><li>$contents</li></ul>";
    $output = "<div class='button-box $class'  $id>$template</div>";
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
function create_field ($field_name, $value, $label, $options = array())
{
    $envelope = "p";
    if (array_key_exists("envelope", $options)) {
        $envelope = $options["envelope"];
    }

    $id = sprintf("id='%s'", $field_name);
    if (array_key_exists("id", $options)) {
        $id = sprintf("id='%s_%s'", $field_name, $options["id"]);
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

    $classes[] = "field";

    /* add additional classes to the actual field */
    if (array_key_exists("editable", $options)) {
        $classes[] = "edit-field";
    }

    if (array_key_exists("class", $options)) {
        $classes[] = $options["class"];
    }
    $field_class = implode(" ", $classes);
    $format = "";
    if (array_key_exists("format", $options)) {
        $format = sprintf("format='%s'", $options["format"]);
        if ($options["format"] == "url" && $value != "&nbsp;") {
            $value = sprintf("<a href='%s' target='_blank'>%s</a>", $value, $value);
        } elseif ($options["format"] == "email" && $value != "&nbsp;") {
            $value = sprintf("<a href='mailto:%s'>%s</a>", $value, $value);
        }
    }
$title = "";
    if(array_key_exists("title", $options)){
$title = sprintf("title='%s'", $options["title"]);
    }
    /*
     * Attributes are non-standard html attributes that are used by javascript
     * these can include the type of input to be generated
     */
    $attributes = "";
    if (array_key_exists("attributes", $options)) {
        $attributes = $options["attributes"];
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
function edit_field($field_name, $value, $label, $table, $id, $options = array()) {
    $envelope = "p";
    if (array_key_exists ( "envelope", $options )) {
        $envelope = $options ["envelope"];
    }

    /* The id is split with the "-" delimiter in javascript when the field is clicked */
    $output [] = sprintf ( "<%s class='field-envelope' id='%s__%s__%s'>", $envelope, $table,$field_name, $id );
if($label){
    $output [] = sprintf ( "<label>%s:&nbsp;</label>", $label );
}
    if ($value == "") {
        $value = "&nbsp;";
    }

    /* add additional classes to the actual field */
    $classes [] = "edit-field field";
    if (array_key_exists ( "class", $options )) {
        $classes [] = $options ["class"];
    }
    $field_class = implode ( " ", $classes );
    $format = "";
    if (array_key_exists ( "format", $options )) {
        $format = sprintf ( "format='%s'", $options ["format"] );
    }


    /*
     * Attributes are non-standard html attributes that are used by javascript these can include the type of input to be generated
    */
    $attributes = "";
    if (array_key_exists ( "attributes", $options )) {
        $attributes = $options ["attributes"];
    }
    $output [] = sprintf ( "<span class='%s' %s %s name='%s'>%s</span></%s>", $field_class, $attributes, $format,$field_name, $value, $envelope );
    return implode ( "\r", $output );

}

function create_input ($object, $name, $label, $options = array())
{
    $id = $name;
    if (array_key_exists("id", $options)) {
        $id = $options["id"];
    }
    $class = "";
    if (array_key_exists("class", $options)) {
        $class = $options["class"];
    }
    
    $required="";
    if(array_key_exists("required",$options)){
    	$required = "required";
    }

    $label_class = "";
    if (array_key_exists("label_class", $options)) {
        $label_class = $options["label_class"];
    }
	$default_value = array_key_exists('default',$options)?$options['default']:'';

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
        if ($format == "date") {
            $value = format_date($value);
        }

        if ($format == "money") {
            $value = format_money($value);
        }
    }
    $type = "text";
    if (array_key_exists("type", $options)) {
        $type = $options["type"];
        if($type=="date"){
        	$type="text";
        }
    }
    $checked = "";
    if ($type == "checkbox" && $value == 1) {
        $checked = "checked";
    }
    return sprintf("<%s class='%s'><label for='%s' class='%s'>%s: </label><input type='%s' name='%s' id='%s' value='%s' class='input %s' %s %s/></%s>", $envelope,
            $envelope_class, $name, $label_class, $label, $type, $name, $id, $value, $class, $checked,$required, $envelope);
}

/**
 * create a checkbox with labels
 *
 * @param string $name
 * @param array $values
 * @param array $selections @TODO add id option
 */
function create_checkbox ($name, $values, $selections = array())
{
    $output = array();
    foreach ($values as $value) {
        $checked = "";
        if (in_array($value->value, $selections)) {
            $checked = "checked";
        }
        $output[] = sprintf("<label>%s</label><input type='checkbox' name='%s' value='%s' %s/>", $value->value, $name, $value->value, $checked);
    }
    return implode("\r", $output);
}

function create_dropdown ($name, $values, $options = array())
{
    if (array_key_exists("envelope", $options)) {
    }
}
