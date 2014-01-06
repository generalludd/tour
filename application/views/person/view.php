<?php
defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 11, 2013 7:47:55 PM chrisdart@cerebratorium.com
$buttons[] = array("text"=>"Join Tour", "type"=>"span","class"=>"button new mini select-tour", "id"=>sprintf("join-tour_%s",$person->id));
$buttons[] = array("text" => sprintf("Tours History", $person->first_name), "href"=> site_url("/tourist/show_for_tourist/$person->id"), "class"=>"button show-tours-for-tourist");
$buttons[] = array("text" => "<- Previous Record", "class"=>"button navigation previous-person-record", "href"=>site_url("person/view_previous/$person->id"));
$buttons[] = array("text" => "Next Record ->", "class"=>"button navigation next-person-record","href"=>site_url("person/view_next/$person->id"));
?>
<h3>Person Record: <?=sprintf("%s %s", $person->first_name, $person->last_name);?></h3>
<?=create_button_bar($buttons);?>
<div class="content">
<div class="grouping block person-info" id="person">
<input type="hidden" id="id" name="id" value="<?=get_value($person, "id", $id);?>"/>
<input type="hidden" id="address_id" name="address_id" value="<?=get_value($person, "address_id");?>"/>
<div class='field-set'>
	<?=create_edit_field("first_name", get_value($person, "first_name"), "First Name",array("envelope"=>"span"));?>
</div>
<div class='field-set'>
	<?=create_edit_field("last_name", get_value($person,"last_name"), "Last Name",array("envelope"=>"span"));?>
</div>
<div class='field-set'>
	<?=create_edit_field("email", get_value($person,"email"), "Email",array("envelope"=>"span"));?>
</div>
<span class='field-set'>
	<?=create_edit_field("shirt_size", get_value($person,"shirt_size"), "Shirt Size",array("envelope"=>"div","class"=>"dropdown","attributes"=>"menu='shirt_size'"));?>
</span>
<div id="phone" class="grouping phone-grouping">

<? if(get_value($person, "phones", FALSE)) : ?>
<p>
<label>Phones</label>
</p>
<? $this->load->view("phone/view",$person->phones); ?>
<? endif; ?>
<?=create_button_bar(array(array("text" => "Add Phone", "type"=>"span", "class"=>"button small new add-phone","id"=>sprintf("add-phone_%s",$person->id))));?>
</div>
</div>
<fieldset class="grouping block address-info" id="address">
<!--<h5>Address</h5>-->
<? if(count($person->address)>0): ?>
<label>Address:</label><br/>
<?=format_address($person->address,"inline");?>&nbsp;<?=create_button(array("text"=>"Edit","type"=>"span", "class"=>"button small edit edit-address","id"=>sprintf("edit-address_%s_%s",$person->address_id, $person->id)));?>


<div class="block housemate-info" id="housemate">
<? if(count($person->housemates) > 0):?>
<p>
<label>Housemates</label>
</p>
<table class="block">
<? foreach($person->housemates as $housemate):?>
<tr>
<td>
<a href="<?=site_url("person/view/$housemate->id");?>"><?=sprintf("%s %s", $housemate->first_name,$housemate->last_name);?></a></td>
</tr>
<? endforeach; ?>
</table>
<? endif;?>
<?=create_button_bar(array(array("text" => "Add Housemate", "type"=>"span", "class"=>"button small new add-housemate","id"=>sprintf("add-housemate_%s_%s",$person->id, $person->address->id))));?>

</div>
<? else: ?>
<?=create_button_bar(array(array("text" => "Add Address", "type"=>"span", "class"=>"button small new add-address","id"=>sprintf("add-address_%s",get_value($person,"id",$id)))));?>

<? endif; ?>
</fieldset>
</div>
