<?php

defined('BASEPATH') or exit('No direct script access allowed');

// filter.php Chris Dart Jan 17, 2014 9:47:50 PM chrisdart@cerebratorium.com
$filters = unserialize(get_cookie("person_filters"));
$initial = "";
$veterans_only = "";
$email_only = "";
$show_disabled = "";
if ($filters) {
    $initial = array_key_exists("initial", $filters) ? $filters["initial"] : FALSE;
    $veterans_only = array_key_exists("veterans_only", $filters) ? "checked" : "";
    $email_only = array_key_exists("email_only", $filters) ? "checked" : "";
    $show_disabled = array_key_exists("show_disabled", $filters) ? "checked" : "";
}
?>
<form
	name="person-filter"
	id="person-filter"
	action="<?=site_url("person/view_all");?>"
	method="get">
	<p>
		<label for="initial">Filter on Last Name Initial</label>
<?=form_dropdown("initial",$initials,$initial);?>
</p>
	<p>
		<label for="veterans_only">Show Veterans Only: </label> <input
			type="checkbox"
			name="veterans_only"
			id="veterans_only"
			value="1"
			<?=$veterans_only;?> />
	</p>
	<p>
		<label for="email_only">Show only people with email addresses</label>
		<input
			type="checkbox"
			name="email_only"
			id="email_only"
			value="1"
			<?=$email_only;?> />
	</p>
	<p>
		<label for="show_disabled">Include people who've been removed from the
			active list:</label> <input
			type="checkbox"
			name="show_disabled"
			id="show_disabled"
			value="1"
			<?=$show_disabled;?> />
	</p>
	<p>
		<input
			type="submit"
			class="button"
			value="Filter People" />
	</p>
</form>