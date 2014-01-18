<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Jan 15, 2014 8:14:44 PM chrisdart@cerebratorium.com
?>
<table class="list">
<thead>
<tr>
<th>
Name
</th>
<th>
Date Sent
</th>
<th>
</th>
<th>
</th>
</tr>
</thead>
<tbody>

<? foreach($receipts as $receipt):?>
<? $full_name = sprintf("%s %s", $receipt->first_name, $receipt->last_name); ?>
<tr>
<td>
<a href="<?=site_url("person/view/$receipt->person_id");?>" title="<?=sprintf("View %s's Information",$full_name);?>">
<?=$full_name;?>
</a>
</td>
<td>
<?=$receipt->status ? format_timestamp($receipt->receipt_date) : "Unsent";?>
</td>
<td>
<span class="button small edit edit-receipt-message" id="<?=sprintf("edit-receipt-message_%s",$receipt->id);?>">Edit</span>
</td>
<td>
<span class="button small resend-receipt-message" id="<?=sprintf("resend-receipt-message_%s", $receipt->id);?>">Resend</span>
</td>
</tr>

<? endforeach;?>
</tbody>
</table>