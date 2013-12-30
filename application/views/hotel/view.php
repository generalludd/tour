<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Dec 29, 2013 10:17:41 PM chrisdart@cerebratorium.com
$buttons[] = array("text"=>"Edit Hotel","type"=>"span","class"=>"button edit edit-hotel","id"=>sprintf("edit-hotel_%s",$hotel->id));
print create_button_bar($buttons);
 ?>
<input type="hidden" id="id" name="id" value="<?=get_value($hotel, "id");?>"/>

<div class="grouping block hotel-info" id="hotel">

<p>
<?=create_edit_field ("hotel_name", get_value($hotel,"hotel_name"), "Hotel Name");?>
</p>
<p>
<label for="tour_name">Tour Name: </label>
<?=$hotel->tour_name;?>
</p>
<p>
<?=create_edit_field ("arrival_date", format_date( get_value($hotel,"arrival_date")), "Hotel Name",array("format"=>"date","type"=>"date"));?>
</p>
<p>
<?=create_edit_field ("arrival_time", get_value($hotel,"arrival_time"), "Arrival Time",array("format"=>"time","type"=>"time"));?>
</p>
<p>
<?=create_edit_field ("departure_date",format_date( get_value($hotel,"departure_date")), "Departure Date",array("format"=>"date","type"=>"date"));?>
</p>
<p>
<?=create_edit_field ("departure_time", get_value($hotel,"departure_time"), "Departure Time",array("format"=>"time","type"=>"time"));?>
</p>
<p>
<?=create_edit_field ("phone", get_value($hotel,"phone"), "Phone",array("format"=>"tel","type"=>"tel"));?>
</p>
<p>
<?=create_edit_field ("fax", get_value($hotel,"fax"), "Fax",array("format"=>"tel","type"=>"tel"));?>
</p>
<p>
<?=create_edit_field ("email", get_value($hotel,"email"), "Email",array("format"=>"email","type"=>"email"));?>
</p>
<p>
<?=create_edit_field ("contact_name", get_value($hotel,"contact_name"), "Contact Name");?>
</p>
<p>
<?=create_edit_field ("address", get_value($hotel,"address"), "Address", array("format"=>"textarea","type"=>"textarea"));?>
</p>

</div>