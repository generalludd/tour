<?php

defined('BASEPATH') or exit('No direct script access allowed');
if(empty($merge) || empty($tour) || empty($payer)){
	return;
}
if(!empty($merge->salutation)) {
	$salutation = get_value($merge, "salutation", $payer->salutation);
} else {
	$salutation = $payer->salutation;
}

?>

<section class="non-printing">
	<p>
		For <a
			href="<?php print site_url("tourist/view_all/$tour->id"); ?>"><?php print $tour->tour_name; ?></a><br/>
		<?php print format_date($tour->start_date, "standard"); ?>
		to <?php print format_date($tour->end_date); ?><br/>
		Payment Deadline: <?php print format_date($tour->due_date); ?>
	</p>
	<?php print create_button_bar([
		[
			"text" => "Edit Source Letter",
			"title" => "Click here to edit the main section and the cancellation policy",
			"class" => "button edit",
			"href" => site_url("letter/edit/$letter->id"),
		],
	]); ?>
		<p>Key formatting will happen during printing.</p>
		<p>Hover over the date and salutation to edit them.</p>
	<div class="letter-details">
		<div class="tour-details">
		For <a
			href="<?php print site_url("tourist/view_all/$tour->id"); ?>"><?php print $tour->tour_name; ?></a><br/>
		<?php print format_date($tour->start_date, "standard"); ?>
		to <?php print format_date($tour->end_date); ?><br/>
		Payment Deadline: <?php print format_date($tour->due_date); ?></div>
		<div class="payer-details">
			<a title="Edit payment" class="button edit small"
				 href="/payer/edit?payer_id=<?php print $payer->payer_id; ?>&tour_id=<?php print $payer->tour_id; ?>">Edit payment for <?php print $payer->first_name . ' ' . $payer->last_name;?> </a>

		</div>

	</div>
	<?php print create_button_bar([
		[
			"text" => "Edit Source Letter",
			"title" => "Click here to edit the main section and the cancellation policy",
			"class" => "button edit",
			"href" => site_url("letter/edit/$letter->id"),
		],
	]); ?>
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
	<!-- <div class="non-printing pagebreak-notice">Page Break Here</div> -->
	<div class="block return-slip">
		<div class="block">
			<?php if (get_value($letter, "cancellation", FALSE)): ?>
				<p>
					<strong><?php print $tour->tour_name; ?> Cancellation Policy</strong>
				</p>
				<?php print $letter->cancellation; ?>
	<div class="letterhead">
		<div class="letterhead--text">
		<div class="letterhead--name">Ballpark Tours, Inc.</div>
		<div class="letterhead--tag-line">Purveyors of Outdoor Baseball</div>
			<div class="letterhead--slogan">Serving Up Unique Outdoor Baseball
				Treks Since 1982
			</div>
			<div class="letterhead--address">
				<div class="letterhead--postal-address">1141 Portland Avenue • Saint
					Paul, Minnesota, 55104 • 612-328-1145
				</div>
				<div class="letterhead--electronic-address">https://ballparktours.net
					• julian@ballparktours.net
				</div>
			</div>
		</div>
		<img src="<?php print base_url("/images/bpt-logo.png"); ?>" alt="logo"
				 class="logo"/>
	</div>

	<div class="grouping block merge-date" id="merge">
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
	<div class="letter--body">
		<?php print $letter->body; ?>
	</div>
	<form
		id="merge-editor"
		name="merge-editor"
		action="<?php print site_url("merge/$action"); ?>"
		method="post">
		<input
			type="hidden"
			id="id"
			name="id"
			value="<?php print get_value($merge, "id"); ?>"/> <input
			type="hidden"
			id="payer_id"
			name="payer_id"
			value="<?php print $payer->payer_id; ?>"/>
		<div id="merge-note">
			<?php print get_value($merge, "note"); ?>
		</div>
		<div id="note-button-block">
			<?php if (get_value($merge, "note", FALSE)): ?>
				<span class="button edit small edit-merge-note">Edit Custom Note</span>
			<?php else: ?>
				<span
					class="button new small insert-merge-note">Insert Custom Note</span>
			<?php endif; ?>
		</div>
	</form>

<?php $this->load->view('merge/remittance', ['letter' => $letter, 'tour' => $tour, 'payer' => $payer]); ?>

</div>
