<?php defined('BASEPATH') or exit('No direct script access allowed');
// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<form name="tour-editor" action="<?php print site_url("tour/$action"); ?>"
	  method="post">
	<input type="hidden" value="<?php print get_value($tour, "id"); ?>"
		   name="id" id="id"/>
	<div class="block tour-info" id="tour">
		<?php $this->load->view('elements/input-field', [
				'label' => 'Tour Name',
				'value' => get_value($tour, 'tour_name'),
				'id' => 'tour_name',
				'size' => 25,
		]); ?>

		<?php $dates = [
				'start_date' => [
						'id' => 'start_date',
						'label' => 'Start Date',
						'type' => 'date',
						'value' => get_value($tour, 'start_date'),
				],
				'end_date' => [
						'id' => 'end_date',
						'label' => 'End Date',
						'type' => 'date',
						'value' => get_value($tour, 'end_date'),
				],
				'due_date' => [
						'id' => 'due_date',
						'label' => 'Due Date',
						'type' => 'date',
						'value' => get_value($tour, 'due_date'),
				],
		];
		foreach ($dates as $date) {
			$this->load->view('elements/input-field', $date);
		}
		?>

		<?php $fees = [
				'full_price' => [
						'id' => 'full_price',
						'value' => get_value($tour, 'full_price'),
						'label' => 'Pay in Full $',
						'type' => 'numeric',
						'size' => 7,
				],
				'regular_price' => [
						'id' => 'regular_price',
						'value' => get_value($tour, 'regular_price'),
						'label' => 'Regular Price $',
						'type' => 'number',
						'size' => 7,

				],
				'banquet_price' => [
						'id' => 'banquet_price',
						'value' => get_value($tour, 'banquet_price'),
						'label' => 'Banquet Price $',
						'type' => 'number',
						'size' => 7,

				],
				'early_price' => [
						'id' => 'early_price',
						'value' => get_value($tour, 'early_price'),
						'label' => 'Early Bird Price $',
						'type' => 'number',
						'size' => 7,

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
						'value' => get_value($tour, 'single_room'),
						'size' => 5,
						'type' => 'number',
				],
				'triple_roomm' => [
						'id' => 'triple_room',
						'label' => 'Triple Room Discount (-)',
						'value' => get_value($tour, 'triple_room'),
						'size' => 5,
						'type' => 'number',

				],
				'quad_room' => [
						'id' => 'quad_room',
						'label' => 'Quad Room Discount (-)',
						'value' => get_value($tour, 'quad_room'),
						'size' => 5,
						'type' => 'number',

				],
		];
		foreach ($rooms as $room) {
			$this->load->view('elements/input-field', $room);
		}
		?>
	</div>
	<div class='button-box'>
		<ul class='button-list'>
			<li><input type="submit" name="save" id="save"
					   value="<?php print $action; ?>"/>
			</li>
		</ul>
	</div>
</form>
