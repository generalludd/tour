<?php

defined('BASEPATH') or exit('No direct script access allowed');

// edit.php Chris Dart Mar 15, 2014 2:26:22 PM chrisdart@cerebratorium.com
foreach ($payer->tourists as $tourist) {
	$tourist_list[] = sprintf("%s %s", $tourist->first_name, $tourist->last_name);
}
$tourists = implode(", ", $tourist_list);

$salutation = get_value($merge, "salutation", NULL);
if ($salutation == NULL) {
	$salutation = $tourists;
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

			<?php endif; ?>
		</div>
		<div class="row-1 row">
			<div class="tourist-block column">

				<label>Tourists: </label><?php print $tourists; ?>
			</div>
			<div class="payment-block column">
				<label>Amount Paid: </label><span
					style="min-width: 4em; display: inline-block"><?php print format_money($payer->amount); ?></span>
				<label>Amount Due: </label><?php print format_money($payer->amt_due); ?>
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
			<?php $shirt_list = []; ?>
			<?php foreach ($payer->tourists as $tourist): ?>

				<?php if (!empty($tourist->shirt_size)): ?>

					<?php $shirt_list[] = $tourist->shirt_size; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="shirt-size-block column">
				<label>Shirt Sizes: </label><?php print implode(", ", $shirt_list); ?>
			</div>
		</div>
	</div>
</div>
