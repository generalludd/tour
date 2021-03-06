<?php

defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Jan 19, 2014 6:21:51 PM chrisdart@cerebratorium.com
$buttons["submit"] = array(
        "type" => "pass-through",
        "text" => sprintf("<input type='submit' name='submit' class='button' value='%s'/>", ucfirst($action))
);
if ($action == "update") {
    $buttons["delete"] = array(
            "text" => "Delete",
            "type" => "span",
            "class" => "button delete delete-contact",
            "id" => sprintf("delete-contact_%s_%s", $contact->id, $contact->hotel_id)
    );
}
?>
<form
	name="contact-editor"
	id="contact-editor"
	action="<?php print site_url("contact/$action");?>"
	method="post">
	<input
		type="hidden"
		name="id"
		id="id"
		value="<?php print get_value($contact,"id");?>" /> <input
		type="hidden"
		name="hotel_id"
		id="hotel_id"
		value="<?php print $hotel_id;?>" />
<?php print create_input($contact, "contact", "Contact Name", array("envelope"=>"div"));?>
<?php print create_input($contact, "position", "Position", array("envelope"=>"div"));?>
<?php print create_input($contact, "phone", "Phone", array("envelope"=>"div", "type"=>"tel"));?>
<?php print create_input($contact, "fax",  "Fax", array("envelope"=>"div", "type"=>"tel"));?>
<?php print create_input($contact, "email", "Email", array("envelope"=>"div","type"=>"email"));?>
<?php print create_button_bar($buttons);?>
</form>