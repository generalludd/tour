<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (empty($initials) || empty($shirtsize_choice) || empty($order_by_options) || empty($veterans_choice)) {
	return NULL;
}

// filter.php Chris Dart Jan 17, 2014 9:47:50 PM chrisdart@cerebratorium.com
$initial = !empty($filters['initial']) ? $filters['initial'] : FALSE;
$veterans = !empty($filters['veterans']) ? $filters['veterans'] : NULL;
$email_only = !empty($filters['email_only']) ? "checked" : "";
$show_disabled = !empty($filters['show_disabled']) ? "checked" : "";
$has_shirtsize = !empty($filters['has_shirtsize']) ? $filters['has_shirtsize'] : "";
$order_by = !empty($filters['order_by']) ? $filters['order_by'] : "";

?>
<form
	name="person-filter"
	id="person-filter"
	action="<?php print site_url("person/view_all"); ?>"
	method="get">
	<p>
		<label for="initial">Filter on Last Name Initial</label>
		<?php print form_dropdown("initial", $initials, $initial); ?>
	</p>
	<p>
		<label for="veterans">Show Only</label>
		<?php print form_dropdown('veterans', veterans_choices(), $veterans); ?>
	</p>
	<p>
		<input
			type="checkbox"
			name="email_only"
			id="email_only"
			value="1"
			<?php print $email_only; ?> />
		<label for="email_only">Show only people with email addresses</label>

	</p>
	<p>
		<input
			type="checkbox"
			name="show_disabled"
			id="show_disabled"
			value="1"
			<?php print $show_disabled; ?> />
		<label for="show_disabled">Include disabled records</label>
	</p>
	<p>
		<label for="has_shirtsize">Has shirt size?</label>
		<?php print form_dropdown('has_shirtsize', $shirtsize_choice, $has_shirtsize); ?>

	<p>
		<label for="order_by">Order by</label>
		<?php print form_dropdown('order_by', $order_by_options, $order_by); ?>

	</p>
	<p>
		<input
			type="submit"
			class="button"
			value="Filter People"/>
	</p>
</form>
