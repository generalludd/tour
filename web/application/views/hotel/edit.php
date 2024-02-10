<?php defined('BASEPATH') or exit('No direct script access allowed');
if(empty($action) || empty($tour)){
	return FALSE;
}


?>

<form id="hotel-editor" name="hotel-editor" action="<?php print site_url('hotel/' . $action); ?>" method="post">
	<input type="hidden" name="id" id="id" value="<?php print get_value($hotel, 'id'); ?>"/>
	<?php print create_input($hotel, 'hotel_name', 'Hotel Name', ['envelope' => 'div',]); ?>
	<h3>
		<?php print $tour->tour_name;?>
		<input type="hidden" name="tour_id" id="tour_id" value="<?php print $tour->id; ?>"/>
	</h3>
	<?php print create_input($hotel, 'stay', 'Tour Stay Number', $options = [
			'envelope' => 'div',
			'type' => 'number',
			'required' => TRUE,
			'default' => get_value($hotel, 'stay'),
	]); ?>
	<div class="input-block diptych">
		<div>
			<?php print create_input($hotel, 'arrival_date', 'Arrival Date', $options = [
					'envelope' => 'div',
					'envelope_class' => 'vertical',
					'format' => 'date',
					'type' => 'date',
					'class' => 'datefield',
			]); ?>

			<?php print create_input($hotel, 'arrival_time', 'Arrival Time', $options = [
					'envelope' => 'div',
					'envelope_class' => 'vertical',

					'format' => 'time',
					'type' => 'time',
			]); ?>
		</div>
		<div>
			<?php print create_input($hotel, 'departure_date', 'Departure Date', [
					'envelope' => 'div',
					'envelope_class' => 'vertical',
					'format' => 'date',
					'type' => 'date',
					'class' => 'datefield',
			]); ?>
			<?php print create_input($hotel, 'departure_time', 'Departure Time', [
					'envelope' => 'div',
					'envelope_class' => 'vertical',
					'format' => 'time',
					'type' => 'time',
			]); ?>
		</div>
	</div>
	<div class="input-block diptych">
		<?php print create_input($hotel, 'phone', 'Phone', [
				'envelope' => 'div',
				'envelope_class' => 'vertical',
				'format' => 'tel',
				'type' => 'tel',
		]); ?>
		<?php print create_input($hotel, 'fax', 'Fax', [
				'envelope' => 'div',
				'envelope_class' => 'vertical',
				'format' => 'tel',
				'type' => 'tel',
		]); ?>
	</div>
	<div class="input-block diptych">
		<?php print create_input($hotel, 'email', 'Email', [
				'envelope' => 'div',
				'envelope_class' => 'vertical',
				'format' => 'email',
				'type' => 'email',
		]); ?>
		<?php print create_input($hotel, 'url', 'Website', [
				'envelope' => 'div',
				'envelope_class' => 'vertical',
				'format' => 'url',
				'type' => 'url',
		]); ?>
	</div>
	<h5 class="notice">Note: You can add special contacts after you click
										 "<?php print ucfirst($action); ?>"</h5>
	<div class="block">
		<label for="address">Address</label><br/>
		<textarea id="address" name="address" class="address-field">
<?php print get_value($hotel, 'address'); ?>
</textarea>
	</div>
	<div class="block">
		<?php print create_input($hotel, 'note', 'Notes', [
				'envelope' => 'div',
				'class' => 'note-field',
		]); ?>
	</div>
	<div class="block">
		<input type="submit" name="submit" class="button" value="<?php print ucfirst($action); ?>"/>
	</div>
</form>

