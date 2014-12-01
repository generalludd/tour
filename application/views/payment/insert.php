<?php defined('BASEPATH') OR exit('No direct script access allowed');

// edit.php Chris Dart Mar 9, 2014 7:48:24 PM chrisdart@cerebratorium.com
$buttons["insert_payment"] = array("text"=>"Insert", "class"=>"button new insert-payment", "id"=>sprintf("insert-payment_%s_%s",$tour_id, $payer_id),"type"=>"span");
?>
<tr id="<?=sprintf("insert-row_%s_%s", $tour_id, $payer_id);?>">
<td><input type="date" class="datefield" name="receipt_date" id="receipt_date"/>
</td>
<td><input type="number" name="amount" id="amount" value=""/></td>
<td>
<?=create_button_bar($buttons);?>
</td>
</tr>