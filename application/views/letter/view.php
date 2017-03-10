<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Mar 15, 2014 1:31:18 PM chrisdart@cerebratorium.com

?>
<h4><?php print $title;?></h4>
<p>
For <a href="<?php print site_url("tourist/view_all/$tour->id");?>"><?php print $tour->tour_name;?></a><br/>
<?php print format_date($tour->start_date, "standard");?> to <?php print format_date($tour->end_date);?><br/>
Payment Deadline: <?php print format_date($tour->due_date);?>
</p>
<div class="block"><?php print format_date($letter->creation_date, "standard");?></div>
<div class="block">
<?php print $letter->body;?>
</div>
<div class="block">
<?php print $letter->cancellation;?>
</div>
<?php $buttons[] = array("text"=>"Edit Letter", "href"=>site_url("letter/edit/$letter->id"), "class"=>"button edit");
$buttons[] = array("text"=>"Delete","class"=>"button delete no-float delete-template","id"=>sprintf("delete-template_%s_%s",$letter->id,$tour->id));
print create_button_bar($buttons);