<?php defined('BASEPATH') OR exit('No direct script access allowed');

// view.php Chris Dart Mar 15, 2014 1:31:18 PM chrisdart@cerebratorium.com

?>
<h4><?=$title;?></h4>
<p>
For <a href="<?=site_url("tour/view/$tour->id");?>"><?=$tour->tour_name;?></a><br/>
<?=format_date($tour->start_date, "standard");?> to <?=format_date($tour->end_date);?><br/>
Payment Deadline: <?=format_date($tour->due_date);?>
</p>
<div class="block"><?=format_date($letter->creation_date, "standard");?></div>
<div class="block">
<?=$letter->body;?>
</div>
<div class="block">
<?=$letter->cancellation;?>
</div>
<? $buttons[] = array("text"=>"Edit Letter", "href"=>site_url("letter/edit/$letter->id"), "class"=>"button edit");
print create_button_bar($buttons);