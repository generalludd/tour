<?php defined('BASEPATH') or exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<input type="hidden" value="<?php print get_value($tour, "id"); ?>" name="id"
	   id="id"/>
<h2><?php print get_value($tour, "tour_name"); ?></h2>
<?php $buttons["edit_tour"] = [
		"text" => "Edit",
		"type" => "span",
		"class" => "button edit edit-tour",
		"id" => "et_$tour->id",
];

$buttons["tourists"] = [
		"text" => "Tourists",
		"class" => "button view-tourists mini",
		"href" => site_url("tourist/view_all/$tour->id"),
];
$buttons["hotels"] = [
		"text" => "Hotels",
		"href" => site_url("hotel/view_all/$tour->id"),
		"class" => "button view-hotels",
];

?>
<?php print create_button_bar($buttons);
?>
<div class="grouping block tour-info" id="tour">
	<?php print create_field("start_date", format_date(get_value($tour, "start_date")), "Start Date", ["envelope" => "p"]); ?>
	<?php print create_field("end_date", format_date(get_value($tour, "end_date")), "End Date", ["envelope" => "p"]); ?>
	<?php print create_field("due_date", format_date(get_value($tour, "due_date")), "Due Date", ["envelope" => "p"]); ?>
	<?php print create_field("full_price", get_value($tour, "full_price"), "Full Price $", ["envelope" => "p"]); ?>
	<?php print create_field("banquet_price", get_value($tour, "banquet_price"), "Banquet Price $", ["envelope" => "p"]); ?>
	<?php print create_field("early_price", get_value($tour, "early_price"), "Early Price $", ["envelope" => "p"]); ?>
	<?php print create_field("regular_price", get_value($tour, "regular_price"), "Regular Price $", ["envelope" => "p"]); ?>
	<?php print create_field("single_room", get_value($tour, "single_room"), "Single Room Adjustment $", ["envelope" => "p"]); ?>
	<?php print create_field("triple_room", get_value($tour, "triple_room"), "Triple Room Adjustment (include a '-') $", ["envelope" => "p"]); ?>
	<?php print create_field("quad_room", get_value($tour, "quad_room"), "Quad Room Adjustment (include a '-')$", ["envelope" => "p"]); ?>
</div>
<div class="letter-list-block" id="tour-letters">
	<?php $data["letters"] = $letters;
	$data["tour_id"] = $tour->id; ?>
	<?php $this->load->view("letter/list", $data); ?>
</div>
