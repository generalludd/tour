<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<input type="hidden" value="<?=get_value($tour, "id");?>" name="id" id="id" />
<fieldset>
<legend><?=get_value($tour, "tour_name");?></legend>
<div class="grouping block tour-info" id="tour">
<?=create_edit_field("start_date", format_date(get_value($tour, "start_date")),"Start Date", array("envelope", "div") );?>
<?=create_edit_field("end_date", format_date(get_value($tour, "end_date")),"End Date", array("envelope", "div") );?>
<?=create_edit_field("due_date", format_date(get_value($tour, "due_date")),"Due Date", array("envelope", "div") );?>
<?=create_edit_field("full_price", format_money(get_value($tour, "full_price")),"Full Price", array("envelope", "div") );?>
<? print_r($fields);?>
</div>
</fieldset>