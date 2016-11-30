<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Mar 9, 2014 7:27:03 PM chrisdart@cerebratorium.com
$total_paid=0;
$buttons["add_reimbursement"] = array("text"=>"Add Reimbursement", "class"=>"button add-reimbursement new", "id"=>sprintf("add-reimbursement_%s_%s",$tour_id, $payer_id),"type"=>"span");
?>
<div id="reimbursements">
<table id="reimbursement-list">
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
<?php if($payment->amount < 0):?>
<tr id="<? printf("payment-row_%s",$payment->id);?>">
<td>
<?=format_date($payment->receipt_date,"standard");?>
</td>
<td>
<?=format_money($payment->amount,"standard");?>
<? $total_paid += $payment->amount;?>
</td>
<td>
<?=create_button_bar(array(array("text"=>"Delete","class"=>"delete-reimbursement delete button small",
         "id"=>sprintf("delete-reimbursement_%s",$payment->id), "type"=>"span")));?>
</td>
</tr>
<?php endif;?>
<? endforeach; ?>
</tbody>
<tfoot>
<tr>
<td>
Total Reimbursed:
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
</div>