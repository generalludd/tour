<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 29, 2013 9:15:32 PM chrisdart@cerebratorium.com

?>

<form id="hotel-editor" name="hotel-editor" action="<?=site_url("hotel/$action");?>" method="post">
<input type="hidden" name="id" id="id" value="<?=get_value($hotel, "id");?>"/>

<?=create_input($hotel, "hotel_name", "Hotel Name",array("envelope"=>"div"));?>
<div>
<label for="tour_id">Tour:&nbsp;</label>
<?=form_dropdown("tour_id",$tour_list,get_value($hotel,"tour_id",$tour->id),"id='tour_id'");?>
</div>
<div class="input-block=row">
<?=create_input($hotel, "stay", "Tour Stay Number",$options = array("envelope"=>"div","type"=>"number"));?>
<?=create_input($hotel, "arrival_date", "Arrival Date", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"date","type"=>"date","class"=>"datefield"));?>
</div>
<?=create_input($hotel, "arrival_time", "Arrival Time", $options = array("envelope"=>"div","format"=>"time","type"=>"time"));?>
<div class="input-block-row">
<?=create_input($hotel, "departure_date", "Departure Date", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"date","type"=>"date","class"=>"datefield"));?>
<?=create_input($hotel, "departure_time", "Departure Time", $options = array("envelope"=>"div","format"=>"time","type"=>"time"));?>
</div>
<div class="input-block=row">
<?=create_input($hotel, "phone", "Phone", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"tel","type"=>"tel"));?>
<?=create_input($hotel, "fax", "Fax", $options = array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
</div>
<h5 class="notice">Note: You can add special contacts after you click "<?=ucfirst($action);?>"</h5>
<div class="block">
<textarea id="address" name="address" class="address-field">
<?=get_value($hotel,"address");?>
</textarea>
</div>
<div class="block">
<?=create_input($hotel, "note", "Notes", array("envelope"=>"div","class"=>"note-field"));?>
</div>
<div class="block">
<input type="submit" name="submit" class="button" value="<?=ucfirst($action);?>"/>
</div>
</form>