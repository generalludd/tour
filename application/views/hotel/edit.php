<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 29, 2013 9:15:32 PM chrisdart@cerebratorium.com

?>

<form id="hotel-editor" name="hotel-editor" action="<?php print site_url("hotel/$action");?>" method="post">
<input type="hidden" name="id" id="id" value="<?php print get_value($hotel, "id");?>"/>

<?php print create_input($hotel, "hotel_name", "Hotel Name",array("envelope"=>"div"));?>
<div>
<label for="tour_id">Tour:&nbsp;</label>
<?php print form_dropdown("tour_id",$tour_list,get_value($hotel,"tour_id",$tour->id),"id='tour_id'");?>
</div>
<?php print create_input($hotel, "stay", "Tour Stay Number",$options = array("envelope"=>"div","type"=>"number","required"=>TRUE));?>
<div class="input-block row">

<?php print create_input($hotel, "arrival_date", "Arrival Date", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"date","type"=>"date","class"=>"datefield"));?>
</div>
<?php print create_input($hotel, "arrival_time", "Arrival Time", $options = array("envelope"=>"div","format"=>"time","type"=>"time"));?>
<div class="input-block row">
<?php print create_input($hotel, "departure_date", "Departure Date", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"date","type"=>"date","class"=>"datefield"));?>
<?php print create_input($hotel, "departure_time", "Departure Time", $options = array("envelope"=>"div","format"=>"time","type"=>"time"));?>
</div>
<div class="input-block row">
<?php print create_input($hotel, "phone", "Phone", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"tel","type"=>"tel"));?>
<?php print create_input($hotel, "fax", "Fax", $options = array("envelope"=>"div","format"=>"tel","type"=>"tel"));?>
</div>
<div class="input-block row">
<?php print create_input($hotel, "email", "Email", $options = array("envelope"=>"div","envelope_class"=>"inline","format"=>"email","type"=>"email"));?>
<?php print create_input($hotel, "url", "Website", $options = array("envelope"=>"div","format"=>"url","type"=>"url"));?>
</div>
<h5 class="notice">Note: You can add special contacts after you click "<?php print ucfirst($action);?>"</h5>
<div class="block">
<label for="address">Address</label><br/>
<textarea id="address" name="address" class="address-field">
<?php print get_value($hotel,"address");?>
</textarea>
</div>
<div class="block">
<?php print create_input($hotel, "note", "Notes", array("envelope"=>"div","class"=>"note-field"));?>
</div>
<div class="block">
<input type="submit" name="submit" class="button" value="<?php print ucfirst($action);?>"/>
<?php if($action == "update"):?>
<span class="button delete delete-hotel" id="<?php print sprintf("delete-hotel_%s_%s",$hotel->id, $hotel->tour_id);?>">Delete</span>
        <?php endif;?>
</div>
</form>