<?php
defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 28, 2013 4:47:05 PM chrisdart@cerebratorium.com

$this->load->helper("tour_helper");
$total_due = 0;
$total_paid = 0;
$total_payers = 0;
$total_cancels = 0;
$total_tourists = 0;
$shirt_count = [];
$buttons[] = [
	'text' => 'Edit Tour Details',
	'href' => site_url('tour/edit/' . $tour->id),
	'class' => 'button edit dialog',
];
$buttons[] = [
	'text' => 'Hotels and Roommates',
	'href' => site_url('hotel/view_for_tour/' . $tour->id),
	'class' => 'button show-hotels',
];
$buttons[] = [
	'text' => 'Letter Templates',
	'href' => site_url('tour/letters/' . $tour->id),
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
                <th class="no-wrap">Payment Type<br />Price
                </th>
                <th>Paid</th>
                <th>Discount</th>
                <th class="no-wrap">Room Size<br />Rate
                </th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($payers as $payer) : ?>
            <?php $total_payers++; ?>
            <tr class="row row-break<?php print $payer->is_cancelled == 1 ? " cancelled" : ""; ?>">
                <td>
                    <?php foreach ($payer->tourists as $tourist) : ?>
                    <?php if ($payer->is_cancelled == 0) : ?>
                    <?php $total_tourists++; ?>
                    <?php $shirt_count = update_shirt_count($shirt_count, $tourist->shirt_size); ?>
                    <?php endif; ?>

                    <?php $tourist_name = sprintf("%s %s", $tourist->first_name, $tourist->last_name); ?>
                    <?php printf("<a href='%s' title='View %s&rsquo;s address book entry'>%s</a>", site_url("person/view/$tourist->person_id"), $tourist_name, $tourist_name); ?>
                    <?php if ($tourist->person_id == $payer->payer_id) : ?>
                    <?php print "*"; ?>
                    <?php endif; ?>
                    <?php if (get_value($tourist, "shirt_size", FALSE)) : ?>
                    &nbsp;(<?php print $tourist->shirt_size; ?>)
                    <?php endif; ?>
                    <br />
                    <?php endforeach; ?>
                    <br />
                    <?php if ($payer->merge_id) : ?>
                    <a href="<?php print base_url('letter/select/' . $payer->payer_id . '/' . $payer->tour_id); ?>"
                        class="button edit select-letter" data-payer_id="<?php print $payer->payer_id; ?>"
                        data-tour_id="<?php print $payer->tour_id; ?>">Edit
                        Letter</a>
                    <?php else : ?>
                    <a <a href="<?php print base_url('letter/select/' . $payer->payer_id . '/' . $payer->tour_id); ?>"
                        class="button new select-letter" data-payer_id="<?php print $payer->payer_id; ?>"
                        data-tour_id="<?php print $payer->tour_id; ?>">Send
                        Letter</a>
                    <?php endif; ?>
                <td>
                    <a href="<?php print site_url("payer/edit?payer_id=$payer->payer_id&tour_id=$payer->tour_id"); ?>"
                        class="button edit">Edit
                        Payment</a>
                </td>
                <td>
                    <?php if ($payer->phones || $payer->email) : ?>
                    <?php if (get_value($payer, "email", TRUE)) : ?>
                    <?php print format_email($payer->email); ?><br />
                    <?php endif; ?>
                    <?php foreach ($payer->phones as $phone) : ?>
                    <?php print sprintf("%s: %s", $phone->phone_type, $phone->phone); ?>
                    <br />
                    <?php endforeach; ?>
                    <?php endif; ?>
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
                <td><?php print format_money($payer->amt_paid); ?>
                </td>
                <td><?php print format_money($payer->discount); ?></td>
                <td><?php print sprintf("%s<br/>%s", format_field_name($payer->room_size), format_money($payer->room_rate)); ?>
                </td>
                <td><?php echo $payer->is_cancelled == 1 ? 0 : format_money($payer->amt_due); ?>
                </td>
            </tr>
            <?php if (get_value($payer, "note", FALSE)) : ?>
            <tr>
                <td></td>
                <td colspan="10"><?php print get_value($payer, "note"); ?></td>
            </tr>
            <?php endif; ?>
            <?php
				if ($payer->is_cancelled != 1) {
					$total_due += $payer->amt_due;
					$total_paid += $payer->amt_paid;
				}
				?>

            <?php endforeach; ?>
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
                <td colspan='2'>
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
    <p><strong>Shirt Totals</strong><br />
        <?php print sort_shirts($shirt_count); ?>
    </p>
    <?php endif; ?>

    <p>
        <?php echo date("m-d-Y"); ?>
    </p>

</div>