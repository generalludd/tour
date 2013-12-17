<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<input type="hidden" value="<?=get_value($tour, "id");?>" name="id" id="id" />
<div class="grouping block person-info" id="person">
<h2><?=get_value($tour, "tour_name");?></h2>
<? $buttons[] = array( "text" => "Edit Tour",  "type" => "span",  "class" => "button edit-tour", "id" => "et_$tour->id" );
print create_button_bar($buttons);
?>
<div class="grouping block tour-info" id="tour">
<?=create_edit_field("start_date", format_date(get_value($tour, "start_date")),"Start Date", array("envelope" => "div") );?>
<?=create_edit_field("end_date", format_date(get_value($tour, "end_date")),"End Date", array("envelope" => "div") );?>
<?=create_edit_field("due_date", format_date(get_value($tour, "due_date")),"Due Date", array("envelope" => "div") );?>
<?=create_edit_field("full_price", format_money(get_value($tour, "full_price")),"Full Price", array("envelope" => "div") );?>
<?=create_edit_field("banquet_price", format_money(get_value($tour, "banquet_price")),"Banquet Price", array("envelope" => "div") );?>
<?=create_edit_field("early_price", format_money(get_value($tour, "early_price")),"Early Price", array("envelope" => "div") );?>
<?=create_edit_field("regular_price", format_money(get_value($tour, "regular_price")),"Regular Price", array("envelope" => "div") );?>
<?=create_edit_field("single_room", format_money(get_value($tour, "single_room")),"Single Room Adjustment", array("envelope" => "div") );?>
<?=create_edit_field("triple_room", format_money(get_value($tour, "triple_room")),"Triple Room Adjustment", array("envelope" => "div") );?>
<?=create_edit_field("quad_room", format_money(get_value($tour, "quad_room")),"Quad Room Adjustment", array("envelope" => "div") );?>
</div>

</div>