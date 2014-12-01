<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Mar 9, 2014 7:27:03 PM chrisdart@cerebratorium.com
$total_paid=0;
$buttons["add_payment"] = array("text"=>"Add Payment", "class"=>"button add-payment new", "id"=>sprintf("add-payment_%s_%s",$tour_id, $payer_id),"type"=>"span");
//$done[] = array("text"=>"Done", "class"=>"button", "href"=>site_url("payer/edit?payer_id=$payer_id&tour_id=$tour_id"));
?>
<div id="payment-list-box">
<table id="payment-list">
<thead>
<tr>
<th>
Date
</th>
<th>
Amount
</th>
<th>
</th>
</tr>
</thead>
<tbody>
<? foreach($payments as $payment): ?>
<tr id="<? printf("payment-row_%s",$payment->id);?>">
<td>
<?=format_date($payment->receipt_date,"standard");?>
</td>
<td>
<?=format_money($payment->amount,"standard");?>
<? $total_paid += $payment->amount;?>
</td>
<td>
<?=create_button_bar(array(array("text"=>"Delete","class"=>"delete-payment delete button small",
         "id"=>sprintf("delete-payment_%s",$payment->id), "type"=>"span")));?>
</td>
</tr>
<? endforeach; ?>
</tbody>
<tfoot>
<tr>
<td>
Total Paid:
</td>
<td >
<?=format_money($total_paid,"standard");?>
</td>
</tr>
</tfoot>
</table>
<input type="hidden" id="total-paid" value="<?=$total_paid;?>"/>
<div style="padding-top: 1em;">
<?=create_button_bar($buttons);?>
</div>
<div style="padding-top: 1em;">
<? //=create_button_bar($done);?>
</div>
</div>