<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<input type="hidden" value="<?=get_value($tour, "id");?>" name="id" id="id" />
<div class="grouping block person-info" id="person">
<h2><?=get_value($tour, "tour_name");?></h2>
<? $buttons["edit_tour"] = array( "text" => "Edit",  "type" => "span",  "class" => "button edit edit-tour", "id" => "et_$tour->id" );

$buttons["tourists"] = array("text" => "Tourists", "class" => "button view-tourists mini", "href" => site_url("tourist/view_all/$tour->id"));
$buttons["hotels"] = array("text"=> "Hotels","href"=>site_url("hotel/view_all/$tour->id"), "class"=>"button view-hotels");

?>
<? print create_button_bar($buttons);
?>
<div class="grouping block tour-info" id="tour">
<?=create_edit_field("start_date", format_date(get_value($tour, "start_date")),"Start Date", array("envelope" => "p") );?>
<?=create_edit_field("end_date", format_date(get_value($tour, "end_date")),"End Date", array("envelope" => "p") );?>
<?=create_edit_field("due_date", format_date(get_value($tour, "due_date")),"Due Date", array("envelope" => "p") );?>
<?=create_edit_field("full_price", get_value($tour, "full_price"),"Full Price $", array("envelope" => "p") );?>
<?=create_edit_field("banquet_price", get_value($tour, "banquet_price"),"Banquet Price $", array("envelope" => "p") );?>
<?=create_edit_field("early_price", get_value($tour, "early_price"),"Early Price $", array("envelope" => "p") );?>
<?=create_edit_field("regular_price", get_value($tour, "regular_price"),"Regular Price $", array("envelope" => "p") );?>
<?=create_edit_field("single_room", get_value($tour, "single_room"),"Single Room Adjustment $", array("envelope" => "p") );?>
<?=create_edit_field("triple_room", get_value($tour, "triple_room"),"Triple Room Adjustment (include a '-') $", array("envelope" => "p") );?>
<?=create_edit_field("quad_room", get_value($tour, "quad_room"),"Quad Room Adjustment (include a '-')$", array("envelope" => "p") );?>
</div>

</div>