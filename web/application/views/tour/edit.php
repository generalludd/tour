<?php defined('BASEPATH') or exit('No direct script access allowed');
// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com
if(empty($action) || empty($tour)){
	return;
}
?>

<form name="tour-editor" action="<?php print site_url('tours/' . $action); ?>"
	  method="post">
	<input type="hidden" value="<?php print get_value($tour, "id"); ?>"
		   name="id" id="id"/>
	<div class="block tour-info" id="tour">
		<?php $this->load->view('elements/input-field', [
				'id' => 'tour_name',
				'attributes' => [
						'value' => get_value($tour, 'tour_name'),
						'size' => 25,
				],
		]); ?>

		<?php $dates = [
				'start_date' => [
						'id' => 'start_date',
						'attributes' => [
								'type' => 'date',
								'value' => get_value($tour, 'start_date'),
						],
				],
				'end_date' => [
						'id' => 'end_date',
						'attributes' => [
								'type' => 'date',
								'value' => get_value($tour, 'end_date'),
						],
				],
				'due_date' => [
						'id' => 'due_date',
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

		<?php $fees = [

				'regular_price' => [
						'id' => 'regular_price',
						'label' => 'Regular Price (Required)',
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
						'label' => 'Pay in Full $',
						'attributes' => [
								'value' => get_value($tour, 'full_price'),
								'type' => 'numeric',
								'size' => 7,
								'class' => 'currency',
						],
				],
				'banquet_price' => [
						'id' => 'banquet_price',
						'label' => 'Banquet Price $',
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


		<?php $rooms = [
				'single_room' => [
						'id' => 'single_room',
						'label' => 'Single Room Surcharge',
						'attributes' => [
								'value' => get_value($tour, 'single_room'),
								'size' => 5,
								'type' => 'number',
								'class' => 'currency',
						],
				],
				'triple_roomm' => [
						'id' => 'triple_room',
						'label' => 'Triple Room Discount (-)',
						'attributes' => [
								'value' => get_value($tour, 'triple_room'),
								'size' => 5,
								'type' => 'number',
								'class' => 'currency',
						],

				],
				'quad_room' => [
						'id' => 'quad_room',
						'label' => 'Quad Room Discount (-)',
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
	</div>
	<input type="submit" name="save" id="save"
		   value="<?php print ucwords($action); ?>"/>
	<?php if (!empty($tour->id) && empty($tour->tourists)): ?>
		<a href="<?php print base_url('tours/delete?tour_id=' . $tour->id); ?>"
		   class="delete-tour delete button dialog">Delete</a>
	<?php endif; ?>

</form>
