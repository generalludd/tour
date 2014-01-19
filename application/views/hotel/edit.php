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
<?=create_input($hotel, "stay", "Tour Stay Number",$options = array("envelope"=>"div","type"=>"number"));?>
<?=create_input($hotel, "arrival_date", "Arrival Date", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"date","type"=>"date","class"=>"datefield"));?>

<?=create_input($hotel, "arrival_time", "Arrival Time", $options = array("envelope"=>"div","format"=>"time","type"=>"time"));?>

<?=create_input($hotel, "departure_date", "Departure Date", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"date","type"=>"date","class"=>"datefield"));?>


<?=create_input($hotel, "departure_time", "Departure Time", $options = array("envelope"=>"div","format"=>"time","type"=>"time"));?>

<?=create_input($hotel, "phone", "Phone", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"tel","type"=>"tel"));?>
<?=create_input($hotel, "fax", "Fax", $options = array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
<div class="contacts">
<fieldset class="triptych column">
<legend>First Contact</legend>
<?=create_input($hotel, "first_contact", "Name",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "first_contact_position", "Position",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "first_contact_phone", "Phone",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "first_contact_email", "Email",array("envelope"=>"div","label_class"=>"above"));?>
</fieldset>
<fieldset  class="triptych column">
<legend>Second Contact</legend>
<?=create_input($hotel, "second_contact", "Name",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "second_contact", "Position",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "second_contact_phone", "Phone",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "second_contact_email", "Email",array("envelope"=>"div","label_class"=>"above"));?>
</fieldset>
<fieldset  class="triptych column">
<legend>Third Contact</legend>
<?=create_input($hotel, "third_contact", "Name",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "third_contact", "Position",array("envelope"=>"div","label_class"=>"above"));?>

<?=create_input($hotel, "third_contact_phone", "Phone",array("envelope"=>"div","label_class"=>"above"));?>
<?=create_input($hotel, "third_contact_email", "Email",array("envelope"=>"div","label_class"=>"above"));?>
</fieldset>
</div>
<div class="block">
<?=create_input($hotel, "address","Address",array("envelope"=>"div","class"=>"address-field"));?>

</div>
<div class="block">
<?=create_input($hotel, "note", "Notes", array("envelope"=>"div","class"=>"note-field"));?>
</div>
<div class="block">
<input type="submit" name="submit" class="button" value="<?=ucfirst($action);?>"/>
</div>
</form>