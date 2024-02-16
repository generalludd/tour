<?php

if (empty($merge) || empty($tour) || empty($payer) || empty($letter)) {
	return;
}
if (!empty($merge->salutation)) {
	$salutation = get_value($merge, "salutation", $payer->salutation);
}
else {
	$salutation = $payer->salutation;
}

?>
<label for="print" class="non-printing">Show Letterhead</label>
<input type="checkbox" id="print" class="non-printing" value="1" checked/>
<article id="letter">
<div class="letterhead">
	<div class="letterhead--text">
		<div class="letterhead--name">Ballpark Tours, Inc.</div>
		<div class="letterhead--tag-line">Purveyors of Outdoor Baseball</div>
		<div class="letterhead--slogan">Serving Up Unique Outdoor Baseball
			Treks Since 1982
		</div>
		<div class="letterhead--address">
			<div class="letterhead--postal-address">1141 Portland Avenue • Saint
				Paul, Minnesota, 55104
			</div>
			<div class="letterhead--electronic-address">
				612-328-1145 • julian@ballparktours.net
			</div>
		</div>
	</div>
	<img src="<?php print base_url("/images/bpt-logo.png"); ?>" alt="logo"
			 class="logo"/>
</div>
<section class="non-printing">
	<?php print create_button_bar([
		[
			"text" => "Edit Source Letter",
			"title" => "Click here to edit the main section and the cancellation policy",
			"class" => "button edit",
			"href" => site_url("letter/edit/$letter->id"),
		],
	]); ?>


	<div class="letter-details">
		<div class="tour-details">
			For <a
				href="<?php print site_url("tourist/view_all/$tour->id"); ?>"><?php print $tour->tour_name; ?></a><br/>
			<?php print format_date($tour->start_date, "standard"); ?>
			to <?php print format_date($tour->end_date); ?><br/>
			Payment Deadline: <?php print format_date($tour->due_date); ?></div>


	</div>

</section>

<div class="printed-content">
	<div
		class="grouping block merge-date"
		id="merge">
		<?php print edit_field("sent_date", date('F j, Y', strtotime(get_value($merge, "sent_date"))), NULL, "merge", get_value($merge, "id"), [
			"class" => "date",
			"editable" => TRUE,
			"size" => "26ex",
			"title" => "Click to edit the date",
		]); ?>
		<?php print edit_field("salutation", $salutation, NULL, "merge", get_value($merge, "id"), [
			"format" => "text",
			"editable" => TRUE,
			"title" => "Click to edit the salutation",
		]); ?>
	</div>
	<div class="block">
		<?php print $letter->body; ?>
	</div>
	<div id="merge-note">
		<div class="note"><?php print get_value($merge, "note"); ?></div>
		<div id="note-button-block">
			<a class="button edit small inline"
				 href="<?php print site_url('merge/edit_note?id=' . $merge->id); ?>"
				 data-target="merge-note">Custom Note</a>
		</div>
	</div>
	<?php $this->load->view('/merge/remittance', [
		'payer' => $payer,
		'letter' => $letter,
		'tour' => $tour,
	]); ?>
</div>

</article>
