<?php defined('BASEPATH') or exit('No direct script access allowed');
// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com
if (empty($action) || empty($tour)) {
	return;
}
?>
<form name="tour-editor" action="<?php print site_url('tours/' . $action); ?>"
			method="post">
	<input type="hidden" value="<?php print get_value($tour, "id"); ?>"
				 name="id" id="id"/>
	<div class="block tour-info" id="tour">
		<div class="triptych">
			<fieldset>
				<legend>Tour Information</legend>
				<?php $this->load->view('elements/input-field', [
					'id' => 'tour_name',
					'wrapper_classes' => ['vertical'],

					'attributes' => [
						'value' => get_value($tour, 'tour_name'),
						'size' => 25,
					],
				]); ?>

				<?php $dates = [
					'start_date' => [
						'id' => 'start_date',
						'wrapper_classes' => ['vertical'],

						'attributes' => [
							'type' => 'date',
							'value' => get_value($tour, 'start_date'),
						],
					],
					'end_date' => [
						'id' => 'end_date',
						'wrapper_classes' => ['vertical'],

						'attributes' => [
							'type' => 'date',
							'value' => get_value($tour, 'end_date'),
						],
					],
					'due_date' => [
						'id' => 'due_date',
						'wrapper_classes' => ['vertical'],

						'attributes' => [
							'type' => 'date',
							'value' => get_value($tour, 'due_date'),
						],
					],
				];
				foreach ($dates as $date) {
					$this->load->view('elements/input-field', $date);
				}
				?>
			</fieldset>
			<fieldset>
				<legend>Prices</legend>
				<?php $fees = [

					'regular_price' => [
						'id' => 'regular_price',
						'label' => 'Regular Price (Required)',
						'wrapper_classes' => ['vertical'],
						'prefix' => '$',
						'attributes' => [
							'required' => 'required',
							'value' => get_value($tour, 'regular_price'),
							'type' => 'number',
							'class' => 'currency',
							'size' => 7,
						],
					],
					'full_price' => [
						'id' => 'full_price',
						'wrapper_classes' => ['vertical'],
						'prefix' => '$',
						'label' => 'Pay in Full',
						'attributes' => [
							'value' => get_value($tour, 'full_price'),
							'type' => 'numeric',
							'size' => 7,
							'class' => 'currency',
						],
					],
					'banquet_price' => [
						'id' => 'banquet_price',
						'wrapper_classes' => ['vertical'],
						'prefix' => '$',
						'label' => 'Banquet Price',
						'attributes' => [
							'value' => get_value($tour, 'banquet_price'),
							'type' => 'number',
							'class' => 'currency',
							'size' => 7,
						],

					],
					'early_price' => [
						'id' => 'early_price',
						'label' => 'Early Bird Price $',
						'wrapper_classes' => ['vertical'],
						'prefix' => '$',
						'attributes' => [
							'value' => get_value($tour, 'early_price'),
							'type' => 'number',
							'class' => 'currency',
							'size' => 7,
						],

					],

				];
				foreach ($fees as $fee) {
					$this->load->view('elements/input-field', $fee);
				}
				?>
			</fieldset>
			<fieldset>
				<legend>Room Prices</legend>

				<?php $rooms = [
					'single_room' => [
						'id' => 'single_room',
						'label' => 'Single Room Surcharge',
						'wrapper_classes' => ['vertical'],
						'prefix'=> '+',
						'attributes' => [
							'value' => get_value($tour, 'single_room'),
							'size' => 5,
							'type' => 'number',
							'class' => 'currency',
						],
					],
					'triple_room' => [
						'id' => 'triple_room',
						'label' => 'Triple Room Discount',
						'prefix' => '-',
						'wrapper_classes' => ['vertical'],
						'attributes' => [
							'value' => get_value($tour, 'triple_room'),
							'size' => 5,
							'type' => 'number',
							'class' => 'currency',
						],

					],
					'quad_room' => [
						'id' => 'quad_room',
						'label' => 'Quad Room Discount',
						'wrapper_classes' => ['vertical'],
						'prefix' => '-',
						'attributes' => [
							'value' => get_value($tour, 'quad_room'),
							'size' => 5,
							'type' => 'number',
							'class' => 'currency',
						],
					],
				];
				foreach ($rooms as $room) {
					$this->load->view('elements/input-field', $room);
				}
				?>
			</fieldset>
		</div>
		<div class="button-bar">
			<input type="submit" name="save" id="save" class="button"
						 value="<?php print ucwords($action); ?>"/>
			<?php if (!empty($tour->id) && empty($tour->tourists)): ?>
				<a href="<?php print base_url('tours/delete?tour_id=' . $tour->id); ?>"
					 class="delete-tour delete button dialog">Delete</a>
			<?php endif; ?>
		</div>
</form>
