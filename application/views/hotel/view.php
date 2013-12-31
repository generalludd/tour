<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Dec 29, 2013 10:17:41 PM chrisdart@cerebratorium.com
$buttons[] = array("text"=>"Edit Hotel","type"=>"span","class"=>"button edit edit-hotel","id"=>sprintf("edit-hotel_%s",$hotel->id));
print create_button_bar($buttons);
 ?>
<input type="hidden" id="id" name="id" value="<?=get_value($hotel, "id");?>"/>

<div class="grouping block hotel-info" id="hotel">
<div class="column">
<?=create_edit_field ("hotel_name", get_value($hotel,"hotel_name"), "Hotel Name", array("envelope"=>"div"));?>
<?=create_edit_field ("phone", get_value($hotel,"phone"), "Phone",array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
<?=create_edit_field ("fax", get_value($hotel,"fax"), "Fax",array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
<?=create_edit_field ("email", get_value($hotel,"email"), "Email",array("envelope"=>"div","format"=>"email","type"=>"email"));?>
<?=create_edit_field ("url", get_value($hotel,"url"), "Website",array("envelope"=>"div","format"=>"url","type"=>"url"));?>
<?=create_edit_field ("contact_name", get_value($hotel,"contact_name"), "Contact Name", array("envelope"=>"div"));?>
<?=create_edit_field ("address", get_value($hotel,"address"), "Address", array("class"=>"textarea","envelope"=>"div"));?>
</div>
<div class="column">
<div class="field-envelope" id="field-tour_id">
<label>Tour Name:&nbsp;</label>
<span class="field" id="tour_name"><?=$hotel->tour_name;?></span>
</div>
<?=create_edit_field("stay",get_value($hotel,"stay"), "Stay", array("envelope","div","format"=>"number","type","number"));?>
<?=create_edit_field ("arrival_date", format_date( get_value($hotel,"arrival_date")), "Hotel Name",array("envelope"=>"div","format"=>"date","type"=>"date"));?>
<?=create_edit_field ("arrival_time", get_value($hotel,"arrival_time"), "Arrival Time",array("envelope"=>"div","format"=>"time","type"=>"time"));?>
<?=create_edit_field ("departure_date",format_date( get_value($hotel,"departure_date")), "Departure Date",array("envelope"=>"div","format"=>"date","type"=>"date"));?>
<?=create_edit_field ("departure_time", get_value($hotel,"departure_time"), "Departure Time",array("envelope"=>"div","format"=>"time","type"=>"time"));?>
</div>
</div>

