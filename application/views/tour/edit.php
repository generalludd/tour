<?php defined('BASEPATH') or exit('No direct script access allowed');
// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<form name="tour-editor" action="<?php print site_url("tour/$action"); ?>"
	  method="post">
	<input type="hidden" value="<?php print get_value($tour, "id"); ?>"
		   name="id" id="id"/>
	<div class="block tour-info" id="tour">
		<?php print create_input($tour, 'tour_name', 'Tour Name'); ?>

		<?php print create_input($tour, 'start_date', 'Start Date', [
				'type' => 'date',
				'class' => 'date',
		]); ?>

		<?php print create_input($tour, 'end_date', 'End Date', [
				'type' => 'date',
				'class' => 'date',
		]); ?>

		<?php print create_input($tour, 'due_date', 'Due Date', [
				'type' => 'date',
				'class' => 'date',
		]); ?>

		<?php print create_input($tour, 'full_price', 'Pay in Full $', [
				'type' => 'number',
				'format' => 'money',
			'required' => TRUE,
				'default' => 0,

		]); ?>

		<?php print create_input($tour, 'banquet_price', 'Veterans Price $', [
				'type' => 'number',
				'format' => 'money',
				'default' => 0,

		]); ?>

		<?php print create_input($tour, 'early_price', 'Early Price $', [
				'type' => 'number',
				'format' => 'money',
				'default' => 0,

		]); ?>
		<?php print create_input($tour, 'regular_price', 'Regular Price $', [
				'type' => 'number',
				'class' => 'money',
				'default' => 0,
				'required'=> TRUE,
		]); ?>
		<?php print create_input($tour, 'single_room', 'Single Room Adjustment $', [
				'type' => 'number',
				'format' => 'money',
				'default' => 0,

		]); ?>
		<?php print create_input($tour, 'triple_room', 'Triple Room Adjustment (include a ' - ') $', [
				'type' => 'number',
				'format' => 'money',
				'default' => 0,

		]); ?>
		<?php print create_input($tour, 'quad_room', 'Quad Room Adjustment (include a ' - ') $', [
				'type' => 'number',
				'format' => 'money',
			'default' => 0,
		]); ?>
	</div>
	<div class='button-box'>
		<ul class='button-list'>
			<li><input type="submit" name="save" id="save"
					   value="<?php print $action; ?>"/>
			</li>
		</ul>
	</div>
</form>
