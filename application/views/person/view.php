<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com
$buttons[] = array("text" => "Edit Person","type"=>"span", "class"=>"button edit edit-person","id"=>sprintf("edit-person_%s",$person->id));
$buttons[] = array("text"=>"Join Tour", "type"=>"span","class"=>"button new mini select-tour", "id"=>sprintf("join-tour_%s",$person->id));
$buttons[] = array("text" => sprintf("Tour History", $person->first_name), "href"=> site_url("/tourist/view_for_tourist/$person->id"), "class"=>"button show-tours-for-tourist");
$nav_buttons[] = array("text" => "<- Previous Record", "class"=>"button navigation previous-person-record", "href"=>site_url("person/view_previous/$person->id"));
$nav_buttons[] = array("text" => "Next Record ->", "class"=>"button navigation next-person-record","href"=>site_url("person/view_next/$person->id"));
$address_buttons["select_housemate"] = array("text" => "Select Housemate", "type"=>"span", "class"=>"button small edit change-housemate","id"=>sprintf("change-housemate_%s",get_value($person,"id",$id)));
$address_buttons["add_address"] = array("text" => "Add Address", "type"=>"span", "class"=>"button small new add-address","id"=>sprintf("add-address_%s",get_value($person,"id",$id)));
$move_button[] = array("text" => "Move", "type"=>"span","title"=>"move this person to another address in the database", "class"=>"button small edit change-housemate","id"=>sprintf("change-housemate_%s",get_value($person,"id",$id)));
$phone_button[] = array("text" => "Add Phone", "type"=>"span", "class"=>"button small new add-phone","id"=>sprintf("add-phone_%s",$person->id));
$restore_button[] = array("text" => "Restore Record", "type" =>"span", "class"=>"button new restore-person","id"=>sprintf("restore-person_%s",get_value($person, "id")));
?>
<? if(get_value($person, "status") == 0): ?>
<div class="notice">
	This person's record has been disabled which means you deleted it at
	some point, but, because they were on at least one tour, they could not
	be permanently deleted from the database.<br />
	<div>
<?=create_button_bar($restore_button);?>
</div>
</div>

<? endif; ?>
<?=create_button_bar($nav_buttons);?>

<h3>Person Record: <?=sprintf("%s %s", $person->first_name, $person->last_name);?></h3>
<?=create_button_bar($buttons);?>

<div class="content">
	<div
		class="grouping block person-info"
		id="person">
		<input
			type="hidden"
			id="id"
			name="id"
			value="<?=get_value($person, "id", $id);?>" /> <input
			type="hidden"
			id="address_id"
			name="address_id"
			value="<?=get_value($person, "address_id");?>" />
		<div class='field-set'>
	<?=create_field("first_name", get_value($person, "first_name"), "First Name",array("envelope"=>"div"));?>
</div>
		<div class='field-set'>
	<?=create_field("last_name", get_value($person,"last_name"), "Last Name",array("envelope"=>"div"));?>
</div>
		<div class='field-set'>
	<?=create_field("email", get_value($person,"email"), "Email",array("envelope"=>"div","format"=>"email"));?>
</div>
		<div class='field-set'>
	<?=create_field("shirt_size", get_value($person,"shirt_size"), "Shirt Size",array("envelope"=>"div","class"=>"dropdown","attributes"=>"menu='shirt_size'"));?>
</div>
<div class='field-set'>
<label for="note">Note:</label><br/>
<?=get_value($person, "note");?>
</div>
<div class="field-set">
<label for="is_veteran">Is Veteran: </label>
<input type="checkbox" value="1" id="is_veteran" name="is_veteran" <?=get_value($person, "is_veteran", FALSE) ? "checked" : "";?>/>
<span class="ajax-info" id="is_veteran-ajax-response"></span>
</div>
		<div
			id="phone"
			class="grouping phone-grouping">

<? if(get_value($person, "phones", FALSE)) : ?>
<p>
				<label>Phones</label>
			</p>
<? $this->load->view("phone/view",$person->phones); ?>
<? endif; ?>
<?=create_button_bar($phone_button);?>
</div>
	</div>
	<fieldset
		class="grouping block address-info"
		id="address">
		<!--<h5>Address</h5>-->
<? if(count($person->address)>0): ?>
<label>Address:</label><br />
<?=format_address($person->address,"inline");?>&nbsp;<?=create_button(array("text"=>"Edit","type"=>"span", "class"=>"button small edit edit-address","id"=>sprintf("edit-address_%s_%s",$person->address_id, $person->id)));?>
<?=create_field("informal_salutation", $person->address->informal_salutation,"Informal Salutation");?>
<?=create_field("formal_salutation", $person->address->formal_salutation,"Formal Salutation");?>
<div class="block housemate-info"
			id="housemate">
<? if(count($person->housemates) > 0):?>
<p>
				<label>Housemates</label>
			</p>
			<table class="block">
<? foreach($person->housemates as $housemate):?>
<tr>
					<td><a href="<?=site_url("person/view/$housemate->id");?>"><?=sprintf("%s %s", $housemate->first_name,$housemate->last_name);?></a></td>
				</tr>
<? endforeach; ?>
</table>
<? endif;?>
<?=create_button_bar(array(array("text" => "Add Housemate", "type"=>"span", "class"=>"button small new add-housemate","id"=>sprintf("add-housemate_%s_%s",$person->id, $person->address->id))));?>
</div>
<? else: ?>
<?=create_button_bar($address_buttons);?>

<? endif; ?>
</fieldset>
</div>
</div>
