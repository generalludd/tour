<?php

defined('BASEPATH') or exit('No direct script access allowed');

// filter.php Chris Dart Jan 17, 2014 9:47:50 PM chrisdart@cerebratorium.com
$filters = unserialize(get_cookie("person_filters"));
$initial = "";
$veterans_only = "";
$non_veterans = "";
$email_only = "";
$show_disabled = "";
$order_by = NULL;
if ($filters) {
    $initial = array_key_exists("initial", $filters) ? $filters["initial"] : FALSE;
    $veterans_only = array_key_exists("veterans_only", $filters) ? "checked" : "";
    $non_veterans = array_key_exists("non_veterans",$filters) ? "checked":"";
    $email_only = array_key_exists("email_only", $filters) ? "checked" : "";
    $show_disabled = array_key_exists("show_disabled", $filters) ? "checked" : "";
    $order_by = array_key_exists('order_by',$filters)? 'checked':'';
}
?>
<form
	name="person-filter"
	id="person-filter"
	action="<?php print site_url("person/view_all");?>"
	method="get">
	<p>
		<label for="initial">Filter on Last Name Initial</label>
<?php print form_dropdown("initial",$initials,$initial);?>
</p>
	<p>
		<label for="veterans_only">Show Veterans Only: </label> <input
			type="checkbox"
			name="veterans_only"
			id="veterans_only"
			value="1"
			<?php print $veterans_only;?> />
	</p>
		<p>
		<label for="non_veterans">Show Non-Veterans Only: </label> <input
			type="checkbox"
			name="non_veterans"
			id="non_veterans"
			value="1"
			<?php print $non_veterans;?> />
	</p>
	<p>
		<label for="email_only">Show only people with email addresses</label>
		<input
			type="checkbox"
			name="email_only"
			id="email_only"
			value="1"
			<?php print $email_only;?> />
	</p>
	<p>
		<label for="show_disabled">Include people who've been removed from the
			active list:</label> <input
			type="checkbox"
			name="show_disabled"
			id="show_disabled"
			value="1"
			<?php print $show_disabled;?> />
	</p>
	<p>
		<label for "order_by">Order by</label>
		<?php print form_dropdown('order_by',$order_by_options, $order_by); ?>
	</p>
	<p>
		<input
			type="submit"
			class="button"
			value="Filter People" />
	</p>
</form>
