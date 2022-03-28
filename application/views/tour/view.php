<?php defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<input type="hidden" value="<?php print get_value($tour, "id"); ?>" name="id"
	   id="id"/>
<h2><?php print get_value($tour, "tour_name"); ?></h2>
<?php $buttons['edit_tour'] = [
		'text' => 'Edit',
		'href' => base_url('tour/edit/' . $tour->id),
		'class' => 'button edit dialog',
];

$buttons['tourists'] = [
		'text' => 'Tourists',
		'class' => 'button view-tourists mini',
		'href' => site_url('tourist/view_all/' . $tour->id),
];
$buttons['hotels'] = [
		'text' => 'Hotels',
		'href' => site_url('hotel/view_all/' . $tour->id),
		'class' => 'button view-hotels',
];

?>
<?php print create_button_bar($buttons);
$fields = [
		'start_date' => [
				'id' => 'start_date',
				'value' => format_date(get_value($tour, 'start_date')),
				'label' => 'Start Date',
		],
		'end_date' => [
				'id' => 'end_date',
				'value' => format_date(get_value($tour, 'end_date')),
				'label' => 'End Date',
		],
		'due_date' => [
				'id' => 'due_date',
				'value' => format_date(get_value($tour, 'due_date')),
				'label' => 'Due Date',
		],
		'full_price' => [
				'id' => 'full_price',
				'value' => format_money(get_value($tour, 'full_price', NULL)),
				'label' => 'Full Price',
				'required' => TRUE,
		],
		'banquet_price' => [
				'id' => 'banquet_price',
				'value' => format_money(get_value($tour, 'banquet_price', NULL)),
				'label' => 'Banquet Price',
		],
		'early_price' => [
				'id' => 'early_price',
				'value' => format_money(get_value($tour, 'early_price', NULL)),
				'label' => 'Early Bird Price',
		],
		'regular_price' => [
				'id' => 'regular_price',
				'value' => format_money(get_value($tour, 'regular_price', NULL)),
				'label' => 'Regular Price',
				'required' => TRUE,
		],
		'single_room' => [
				'id' => 'single_room',
				'value' => format_money(get_value($tour, 'single_room', NULL)),
				'label' => 'Single Room Fee +',
		],
		'triple_room' => [
				'id' => 'triple_room',
				'value' => format_money(get_value($tour, 'triple_room', NULL)),
				'label' => 'Triple Room Discount',
		],
		'quad_room' => [
				'id' => 'quad_room',
				'value' => format_money(get_value($tour, 'quad_room', NULL)),
				'label' => 'Quad Room Discount',
		],

];
?>
<div class="grouping block tour-info" id="tour">
	<?php foreach ($fields as $field): ?>
		<?php $this->load->view('elements/field-item', $field); ?>
	<?php endforeach; ?>
</div>
<div class="letter-list-block" id="tour-letters">
	<?php $data["letters"] = $letters;
	$data["tour_id"] = $tour->id; ?>
	<?php $this->load->view("letter/list", $data); ?>
</div>
