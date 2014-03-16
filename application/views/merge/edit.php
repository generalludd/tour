<?php

defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Mar 15, 2014 2:26:22 PM chrisdart@cerebratorium.com
 foreach ($payer->tourists as $tourist){
 $tourist_list[] = sprintf("%s %s", $tourist->first_name, $tourist->last_name);
}
$tourists = implode(", ", $tourist_list);

$salutation = get_value($merge, "salutation", NULL);
if ($salutation == NULL){
$salutation = $tourists;
}

?>
<section class="non-printing">
	<div class="alert" style="padding:1em; text-align: center;">
	<p>Key formatting will happen during printing.</p>
	<p>Hover over the date and salutation to edit them.</p>
	</div>
	<p>
		For <a href="<?=site_url("tourist/view_all/$tour->id");?>"><?=$tour->tour_name;?></a><br />
<?=format_date($tour->start_date, "standard");?> to <?=format_date($tour->end_date);?><br />
Payment Deadline: <?=format_date($tour->due_date);?>
</p>
<?=create_button_bar(array(array("text"=>"Edit Source Letter","title"=>"Click here to edit the main section and the cancellation policy","class"=>"button edit", "href"=>site_url("letter/edit/$letter->id"))));?>
</section>
<div class="printed-content">
<div
	class="grouping block merge-date"
	id="merge">
<?=create_field("sent_date", format_date(get_value($merge, "sent_date",date("m-d-Y"))), FALSE, array("format"=>"date", "editable"=>TRUE, "title"=>"Click to edit the date"));?>
<?=create_field("salutation", $salutation, FALSE, array("format"=>"text","editable"=>TRUE, "title"=>"Click to edit the salutation"));?>
</div>
<div class="block">
<?=$letter->body;?>
</div>
<form
	id="merge-editor"
	name="merge-editor"
	action="<?=site_url("merge/$action");?>"
	method="post">
	<input
		type="hidden"
		id="id"
		name="id"
		value="<?=get_value($merge, "id");?>" /> <input
		type="hidden"
		id="payer_id"
		name="payer_id"
		value="<?=$payer->payer_id;?>" />
	<div id="merge-note">
<?=get_value($merge,"note");?>
</div>
	<div id="note-button-block">
<? if(get_value($merge, "note", FALSE)):?>
<span class="button edit small edit-merge-note">Edit Custom Note</span>
<? else: ?>
<span class="button new small insert-merge-note">Insert Custom Note</span>
<? endif;?>
</div>
</form>
<div class="block">
<? if(get_value($letter, "cancellation",FALSE)):?>
<p>
		<strong>Cancellation Rules</strong>
	</p>
<?=$letter->cancellation;?>

<? endif; ?>
</div>
<div class="non-printing pagebreak-notice">Page Break Here</div>
<div class="block return-slip pagebreak">
	<div class="block">
<? if(get_value($letter, "cancellation",FALSE)):?>
<p>
			<strong>Cancellation Rules</strong>
		</p>
<?=$letter->cancellation;?>

<? endif; ?>
</div>
	<div class="row-1 row">
		<div class="tourist-block column">

<label>Tourists: </label><?=$tourists;?>
</div>
		<div class="payment-block column">
			<label>Amount Paid: </label><span
				style="min-width: 4em; display: inline-block"><?=format_money($payer->amount);?></span>
			<label>Amount Due: </label><?=format_money($payer->amt_due);?>
</div>
	</div>
	<div class="row row-2">
		<div class="block roommate-line">
			<label>Roommate(s):</label>
		</div>
	</div>
	<div class="row row-2 row-last">
		<div class="special-request-block column">
			<label>Special Requests: </label>
		</div>
<? foreach ($payer->tourists as $tourist):?>
<? $shirt_list = array();?>
<? if(!empty($tourist->shirt_size)):?>

<? $shirt_list[] = $tourist->shirt_size;?>
<?endif;?>
<? endforeach; ?>
<div class="shirt-size-block column">
			<label>Shirt Sizes: </label><?=implode(", ", $shirt_list);?></div>
	</div>
</div>
</div>
