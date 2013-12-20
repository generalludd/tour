<?php defined('BASEPATH') OR exit('No direct script access allowed');
// view.php Chris Dart Dec 13, 2013 8:55:00 PM chrisdart@cerebratorium.com

?>
<form name="tour-editor" action="<?=site_url("tour/$action");?>" method="post">
<input type="hidden" value="<?=get_value($tour, "id");?>" name="id" id="id" />
<div class="grouping block person-info" id="person">
<h2><?=get_value($tour, "tour_name");?></h2>

<div class="grouping block tour-info" id="tour">
<?=create_input($tour, "tour_name", "Tour Name");?>
<br/>
<?=create_input($tour, "start_date","Start Date", array("format"=>"date", "class"=>"date" ));?>
<br/>
<?=create_input($tour, "end_date", "End Date", array("format"=> "date", "class"=>"date") );?>
<br/>
<?=create_input($tour, "due_date","Due Date", array("format" => "date", "class"=>"date") );?>
<br/>
<?=create_input($tour, "full_price","Full Price", array("format" => "money", "class"=>"money") );?>
<br/>
<?=create_input($tour, "banquet_price","Banquet Price", array("format" => "money", "class"=>"money") );?>
<br/>
<?=create_input($tour, "early_price","Early Price", array("format" => "money", "class"=>"money") );?>
<br/>
<?=create_input($tour, "regular_price","Regular Price", array("format" => "money", "class"=>"money") );?>
<br/>
<?=create_input($tour, "single_room", "Single Room Adjustment", array("format" => "money", "class"=>"money") );?>
<br/>
<?=create_input($tour, "triple_room","Triple Room Adjustment", array("format" => "money", "class"=>"money") );?>
<br/>
<?=create_input($tour, "quad_room","Quad Room Adjustment", array("format" => "money", "class"=>"money") );?>
</div>
<div class='button-box'>
<ul class='button-list'>
<li><input type="submit" name="save" id="save" value="<?=$action;?>"/>
</li>
</ul>
</div>
</div>
</form>