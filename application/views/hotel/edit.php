<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Dec 29, 2013 9:15:32 PM chrisdart@cerebratorium.com

?>

<form id="hotel-editor" name="hotel-editor"
			action="<?php print site_url("hotel/$action"); ?>" method="post">
	<input type="hidden" name="id" id="id"
				 value="<?php print get_value($hotel, "id"); ?>"/>

	<?php print create_input($hotel, "hotel_name", "Hotel Name", ["envelope" => "div"]); ?>
	<div>
		<label for="tour_id">Tour:&nbsp;</label>
		<?php print form_dropdown("tour_id", $tour_list, get_value($hotel, "tour_id", $tour->id), "id='tour_id'"); ?>
	</div>
	<?php print create_input($hotel, "stay", "Tour Stay Number", $options = [
		"envelope" => "div",
		"type" => "number",
		"required" => TRUE,
		'default'=> $hotel->stay,
	]); ?>
	<div class="input-block row">

		<?php print create_input($hotel, "arrival_date", "Arrival Date", $options = [
			"envelope" => "div",
			"envelope_class" => "inline",
			"format" => "date",
			"type" => "date",
			"class" => "datefield",
		]); ?>
	</div>
	<?php print create_input($hotel, "arrival_time", "Arrival Time", $options = [
		"envelope" => "div",
		"format" => "time",
		"type" => "time",
	]); ?>
	<div class="input-block row">
		<?php print create_input($hotel, "departure_date", "Departure Date", $options = [
			"envelope" => "div",
			"envelope_class" => "inline",
			"format" => "date",
			"type" => "date",
			"class" => "datefield",
		]); ?>
		<?php print create_input($hotel, "departure_time", "Departure Time", $options = [
			"envelope" => "div",
			"format" => "time",
			"type" => "time",
		]); ?>
	</div>
	<div class="input-block row">
		<?php print create_input($hotel, "phone", "Phone", $options = [
			"envelope" => "div",
			"envelope_class" => "inline",
			"format" => "tel",
			"type" => "tel",
		]); ?>
		<?php print create_input($hotel, "fax", "Fax", $options = [
			"envelope" => "div",
			"format" => "tel",
			"type" => "tel",
		]); ?>
	</div>
	<div class="input-block row">
		<?php print create_input($hotel, "email", "Email", $options = [
			"envelope" => "div",
			"envelope_class" => "inline",
			"format" => "email",
			"type" => "email",
		]); ?>
		<?php print create_input($hotel, "url", "Website", $options = [
			"envelope" => "div",
			"format" => "url",
			"type" => "url",
		]); ?>
	</div>
	<h5 class="notice">Note: You can add special contacts after you click
		"<?php print ucfirst($action); ?>"</h5>
	<div class="block">
		<label for="address">Address</label><br/>
		<textarea id="address" name="address" class="address-field">
<?php print get_value($hotel, "address"); ?>
</textarea>
	</div>
	<div class="block">
		<?php print create_input($hotel, "note", "Notes", [
			"envelope" => "div",
			"class" => "note-field",
		]); ?>
	</div>
	<div class="block">
		<input type="submit" name="submit" class="button"
					 value="<?php print ucfirst($action); ?>"/>
		<?php if ($action == "update"): ?>
			<span class="button delete delete-hotel"
						data-hotel_id="<?php print $hotel->id;?>"
						data-tour_id="<?php print $hotel->tour_id;?>"
						id="<?php print sprintf("delete-hotel_%s_%s", $hotel->id, $hotel->tour_id); ?>">Delete</span>
		<?php endif; ?>
	</div>
</form>
<script type="text/javascript">
	$(document).on("click",".delete-hotel", function(){
		console.log('here');
		let my_hotel = $(this).data("hotel_id");
		let my_tour = $(this).data("tour_id");
		let decision = confirm("This will completely delete this hotel from the database including all related contacts. Are you sure you want to continue? This cannot be undone!");
		if(decision){
			let final_decision = confirm("You have decided to permanently delete this hotel from the database. Be sure to update all other hotel records to fill the gap this leaves! This cannot be undone. Are you sure?");
			if(final_decision){
				let form_data = {
					id: my_hotel,
					ajax: 1
				};
				$.ajax({
					type: "post",
					url: base_url + "hotel/delete",
					data: form_data,
					success: function(data){
						window.location.href = base_url + "hotel/view_all/" + my_tour;
					}
				});
			}
		}
	});
</script>
