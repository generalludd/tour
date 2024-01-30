<?php defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<?php $dates = [
		'start_date' => [
				'id' => 'start_date',
				'value' => format_date(get_value($tour, 'start_date')),
				'label' => 'Start',
				'wrapper' => 'div',
		],
		'end_date' => [
				'id' => 'end_date',
				'value' => format_date(get_value($tour, 'end_date')),
				'label' => 'End',
				'wrapper' => 'div',

		],
		'due_date' => [
				'id' => 'due_date',
				'value' => format_date(get_value($tour, 'due_date')),
				'label' => 'Due',
				'wrapper' => 'div',

		],
];
$ticket_prices = [

		'regular_price' => [
				'id' => 'regular_price',
				'value' => get_value($tour, 'regular_price', NULL),
				'label' => 'Regular',
				'required' => TRUE,
				'wrapper' => 'div',
				'classes' => ['currency'],

		],
		'full_price' => [
				'id' => 'full_price',
				'value' => get_value($tour, 'full_price', NULL),
				'label' => 'Full',
				'required' => TRUE,
				'wrapper' => 'div',
				'classes' => ['currency'],

		],
		'banquet_price' => [
				'id' => 'banquet_price',
				'value' => get_value($tour, 'banquet_price', NULL),
				'label' => 'Banquet',
				'wrapper' => 'div',
				'classes' => ['currency'],

		],
		'early_price' => [
				'id' => 'early_price',
				'value' => get_value($tour, 'early_price', NULL),
				'label' => 'Early Bird',
				'wrapper' => 'div',
				'classes' => ['currency'],
		],
];
$room_fees = [
		'single_room' => [
				'id' => 'single_room',
				'value' => get_value($tour, 'single_room', NULL),
				'label' => 'Single (+)',
				'wrapper' => 'div',
				'classes' => ['currency'],

		],
		'triple_room' => [
				'id' => 'triple_room',
				'value' => get_value($tour, 'triple_room', NULL),
				'label' => 'Triple (-)',
				'wrapper' => 'div',
				'classes' => ['currency'],

		],
		'quad_room' => [
				'id' => 'quad_room',
				'value' => get_value($tour, 'quad_room', NULL),
				'label' => 'Quad (-)',
				'wrapper' => 'div',
				'classes' => ['currency'],
		],

];
?>

<div class="block quick-look">
	<div class="grouping block">
		<h4>Dates</h4>
		<?php foreach ($dates as $field): ?>
			<?php $this->load->view('elements/field-item', $field); ?>
		<?php endforeach; ?>
	</div>
	<div class="grouping block">
		<h4>Ticket Costs</h4>
		<?php foreach ($ticket_prices as $field): ?>
			<?php if (($field['value']) > 0): ?>
				<?php $this->load->view('elements/field-item', $field); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<div class="grouping block">
		<h4>Room Fees</h4>
		<?php foreach ($room_fees as $field): ?>
			<?php if (($field['value']) > 0): ?>
				<?php $this->load->view('elements/field-item', $field); ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
