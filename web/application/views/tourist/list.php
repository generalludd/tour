<?php
if (empty($tour)) {
	return FALSE;
}

$this->load->helper("tour_helper");
$total_due = 0;
$total_paid = 0;
$total_payers = 0;
$total_cancels = 0;
$total_tourists = 0;
$shirt_count = [];
$buttons[] = [
	'text' => 'Edit Tour Details',
	'href' => site_url('tours/edit/' . $tour->id),
	'class' => 'button edit dialog',
];
$buttons[] = [
	'text' => 'Hotels and Roommates',
	'href' => site_url('hotel/view_for_tour/' . $tour->id),
	'class' => 'button show-hotels',
];
$buttons[] = [
	'text' => 'Letter Templates',
	'href' => site_url('tours/letters/' . $tour->id),
	'class' => 'button dialog edit',
];
$buttons[] = [
	"text" => "Export List for Mail Merge",
	"href" => site_url("tourist/view_all/$tour->id?export=TRUE"),
	"class" => "button export export-tourists",
];
$buttons['print'] = [
	'text' => 'Print',
	'href' => 'javascript:print();',
	'class' => ['button', 'export'],
];
?>
<h2><?php print $tour->tour_name; ?></h2>
<div class="block">
	<?php print create_button_bar($buttons); ?>
</div>

<div class="block">
	<table class="list">
		<thead>
		<tr>
			<th class="no-wrap">Payer (&#42;) &amp; Tourists</th>
			<th></th>
			<th>Contact Info</th>
			<th class="no-wrap">Payment Type<br/>Price
			</th>
			<th>Paid</th>
			<th>Discount</th>
			<th>Surcharge</th>
			<th class="no-wrap">Room Size<br/>Rate
			</th>
			<th>Due</th>
		</tr>
		</thead>
		<tbody>
		<?php if (!empty($payers)): ?>
			<?php foreach ($payers as $payer) : ?>
				<?php $total_payers++; ?>
				<tr
					class="row row-break<?php print $payer->is_cancelled == 1 ? " cancelled" : ""; ?>">
					<td id="payer-<?php print $payer->payer_id?>">
						<?php foreach ($payer->tourists as $tourist) : ?>
							<?php if ($payer->is_cancelled == 0) : ?>
								<?php $total_tourists++; ?>
								<?php update_shirt_count($shirt_count, $tourist->shirt_size); ?>
							<?php endif; ?>

							<?php $tourist_name = sprintf("%s %s", $tourist->first_name, $tourist->last_name); ?>
							<?php printf("<a href='%s' title='View %s&rsquo;s address book entry'>%s</a>", site_url("person/view/$tourist->person_id"), $tourist_name, $tourist_name); ?>
							<?php if ($tourist->person_id == $payer->payer_id) : ?>
								<?php print "*"; ?>
							<?php endif; ?>
							<?php if (get_value($tourist, "shirt_size", FALSE)) : ?>
								&nbsp;(<?php print $tourist->shirt_size; ?>)
							<?php endif; ?>
							<br/>
						<?php endforeach; ?>
						<br/>
						<?php if ($payer->merge_id) : ?>
							<a
								href="<?php print base_url('letter/select/' . $payer->payer_id . '/' . $payer->tour_id); ?>"
								class="button edit dialog"
								data-payer_id="<?php print $payer->payer_id; ?>"
								data-tour_id="<?php print $payer->tour_id; ?>">Edit
								Letter ✎</a>
						<?php else : ?>
							<a
								href="<?php print base_url('letter/select/' . $payer->payer_id . '/' . $payer->tour_id); ?>"
								class="button new dialog"
								data-payer_id="<?php print $payer->payer_id; ?>"
								data-tour_id="<?php print $payer->tour_id; ?>">➕Send
								Letter</a>
						<?php endif; ?>
					<td>
						<a
							href="<?php print site_url("payer/edit?payer_id=$payer->payer_id&tour_id=$payer->tour_id"); ?>"
							class="button edit">Edit
							Payment ✎</a><p>
				<?php if (get_value($payer, "note", FALSE)) : ?>
					<?php print get_value($payer, "note"); ?>
				<?php endif; ?></p>
					</td>
					<td>
						<?php $this->load->view('person/contact_card', ['person' => $payer->person]); ?>
					</td>
					<?php
					if ($payer->is_comp == 1) :
						?>
						<td>Complementary</td>
					<?php elseif ($payer->is_cancelled == 1) : ?>
						<td class='cancelled'>Cancelled</td>
						<?php $total_cancels++; ?>

					<?php else : ?>
						<td><?php print sprintf("%s<br/>%s", format_field_name($payer->payment_type), format_money($payer->price)); ?>
						</td>
					<?php endif; ?>
					<td><?php print format_money($payer->amount_paid); ?>
					</td>
					<td><?php print format_money($payer->discount); ?></td>
					<td><?php print format_money($payer->surcharge); ?></td>

					<td><?php print sprintf("%s<br/>%s", format_field_name($payer->room_size), format_money($payer->room_rate)); ?>
					</td>
					<td><?php echo $payer->is_cancelled == 1 ? 0 : format_money($payer->amount_due); ?>
					</td>
				</tr>

				<?php
				if ($payer->is_cancelled != 1) {
					$total_due += $payer->amount_due;
					$total_paid += $payer->amount_paid;
				}
				?>

			<?php endforeach; ?>
		<?php endif; ?>

		</tbody>
		<tfoot>
		<tr>
			<td>
				Tourists: <?php print $total_tourists; ?>
			</td>
			<td>
				Payers: <?php print $total_payers - $total_cancels; ?>
			</td>
			<td>
				Cancels: <?php print $total_cancels; ?>
			</td>
			<td style="text-align: right;">
				Total Paid
			</td>
			<td colspan='3'>
				<?php print format_money($total_paid); ?>
			</td>
			<td style="text-align: right;">
				Total Due
			</td>
			<td>
				<?php print format_money($total_due); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	<?php if ($shirt_count) : ?>
		<p><strong>Shirt Totals</strong><br/>
			<?php print sort_shirts($shirt_count); ?>
		</p>
	<?php endif; ?>

	<p>
		<?php echo date("m-d-Y"); ?>
	</p>

</div>
