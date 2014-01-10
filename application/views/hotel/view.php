<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Dec 29, 2013 10:17:41 PM chrisdart@cerebratorium.com
$buttons[] = array("text"=>"Edit","type"=>"span","class"=>"button edit edit-hotel","id"=>sprintf("edit-hotel_%s",$hotel->id));
$buttons[] = array("text"=>"Roommates", "href"=>site_url(sprintf("roommate/view_for_tour/?tour_id=%s&stay=%s",get_value($hotel,"tour_id"),get_value($hotel,"stay"))));

 ?>
 <h3>Information for Hotel <?=$hotel->hotel_name; ?></h3>
 <?=create_button_bar($buttons);?>
<input type="hidden" id="id" name="id" value="<?=get_value($hotel, "id");?>"/>

<div class="grouping block hotel-info" id="hotel">
<div class="column">
<?=create_field ("hotel_name", get_value($hotel,"hotel_name"), "Hotel Name", array("envelope"=>"div"));?>
<?=create_field ("phone", get_value($hotel,"phone"), "Phone",array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
<?=create_field ("fax", get_value($hotel,"fax"), "Fax",array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
<?=create_field ("email", get_value($hotel,"email"), "Email",array("envelope"=>"div","format"=>"email","type"=>"email"));?>
<?=create_field ("url", get_value($hotel,"url"), "Website",array("envelope"=>"div","format"=>"url","type"=>"url"));?>
<?=create_field ("contact_name", get_value($hotel,"contact_name"), "Contact Name", array("envelope"=>"div"));?>
<?=create_field ("address", get_value($hotel,"address"), "Address", array("class"=>"textarea","envelope"=>"div"));?>
</div>
<div class="column">
<div class="field-envelope" id="field-tour_id">
<label>Tour Name:&nbsp;</label>
<a class="field" id="tour_name" href="<?=site_url("tour/view/$hotel->tour_id");?>"><?=$hotel->tour_name;?></a>
</div>
<?=create_field("stay",get_value($hotel,"stay"), "Stay", array("envelope"=>"div","format"=>"number","type","number"));?>
<?=create_field ("arrival_date", format_date( get_value($hotel,"arrival_date")), "Hotel Name",array("envelope"=>"div","format"=>"date","type"=>"date"));?>
<?=create_field ("arrival_time", get_value($hotel,"arrival_time"), "Arrival Time",array("envelope"=>"div","format"=>"time","type"=>"time"));?>
<?=create_field ("departure_date",format_date( get_value($hotel,"departure_date")), "Departure Date",array("envelope"=>"div","format"=>"date","type"=>"date"));?>
<?=create_field ("departure_time", get_value($hotel,"departure_time"), "Departure Time",array("envelope"=>"div","format"=>"time","type"=>"time"));?>
</div>
</div>

