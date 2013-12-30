<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 29, 2013 9:15:32 PM chrisdart@cerebratorium.com

?>

<form id="hotel-editor" name="hotel-editor" action="<?=site_url("hotel/$action");?>" method="post">
<input type="hidden" name="id" id="id" value="<?=get_value($hotel, "id");?>"/>
<p>
<?=create_input($hotel, "hotel_name", "Hotel Name");?>
</p>
<p>
<label for="tour_id">Tour:&nbsp;</label>
<?=form_dropdown("tour_id",$tour_list,get_value($hotel,"tour_id",$tour->id),"id='tour_id'");?>
</p>
<p>
<?=create_input($hotel, "arrival_date", "Arrival Date", $options = array("format"=>"date","type"=>"date"));?>
</p>
<p>
<?=create_input($hotel, "arrival_time", "Arrival Time", $options = array("format"=>"time","type"=>"time"));?>
</p>
<p>
<?=create_input($hotel, "departure_date", "Departure Date", $options = array("format"=>"date","type"=>"date"));?>
</p>
<p>
<?=create_input($hotel, "departure_time", "Departure Time", $options = array("format"=>"time","type"=>"time"));?>
</p>
<p>
<?=create_input($hotel, "phone", "Phone", $options = array("format"=>"tel","type"=>"tel"));?>
<?=create_input($hotel, "fax", "Fax", $options = array("format"=>"tel","type"=>"tel"));?>
</p>
<p>
<?=create_input($hotel, "contact_name", "Contact Name");?>
</p>
<p>
<label for="address">Address</label><br/>
<textarea name="address" id="address">
<?=get_value($hotel,"address");?>
</textarea>
</p>
<p>
<input type="submit" name="submit" class="button" value="<?=ucfirst($action);?>"/>
</p>
</form>