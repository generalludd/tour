<?php defined('BASEPATH') OR exit('No direct script access allowed');

// payer.php Chris Dart Dec 14, 2013 6:32:48 PM chrisdart@cerebratorium.com
$tourist_count = count($tourists);
$total_cost = ($tour_price - $payer->discount + $room_rate) * $tourist_count;
$amt_due = $total_cost -  $payer->amt_paid;
?>
<form name="payer-editor" id="payer-editor" method="post" action="<?=base_url("index.php/payer/$action");?>">
<input type="hidden" id="payer_id" name="payer_id" value="<?=$payer->payer_id;?>"/>
<input type="hidden" id="tour_id" name="tour_id" value="<?=$tour_id;?>"/>
<input type="hidden" id="tourist_count" name="tourist_count" value="<?=$tourist_count;?>"/>
<input type="hidden" id="room_rate" name="room_rate" value="<?=$room_rate;?>"/>
<input type="hidden" id="tour_price" name="tour_price" value="<?=$tour_price;?>"/>
<p>
<?=sprintf("%s %s", $payer->first_name, $payer->last_name);?>
<br/>
<?if($action == "update"):?>
<?=get_value($payer,"tour_name");?>
<? endif;?>
</p>
<p>
<?=form_dropdown("payment_type",$payment_types, get_value($payer,"payment_type"), "class='change_payment_type'");?>
&nbsp;$<span id="tour_price_display"><?=$tour_price;?></span>
</p>
<p>
<?=form_dropdown("room_size",$room_sizes,get_value($payer, "room_size"), "class='change_room_size'");?>
&nbsp;$<span id="room_rate_display"><?=$room_rate;?></span>
</p>
<p>
Discount: &nbsp;$<input type="number" class="edit_payer_amounts money" name="discount" id="discount" value="<?=get_value($payer, "discount");?>"/>
</p>
<p>
Total Cost:
&nbsp;$<span class="field" id="total_cost"><?=$total_cost;?></span>
</p>
<p>
$<input type="number" name="amt_paid" id="amt_paid" class="edit_payer_amounts money" value="<?=get_value($payer,"amt_paid");?>"/>
</p>
<p>
Amount Due:
<span class="field" id="amt_due"><?=format_money($amt_due);?></span>
</p>

<div>


<div id="payer-tourist-block block">
<h4>Tourists</h4>
<? $this->load->view("tourist/payer_list", $tourists);?>
<div id="mini-selector">
<form name="tourist-mini-selector" id="tourist-mini-selector" method="get" action="">
<input type="hidden" id="tour_id" name="tour_id" value="<?=$tour_id;?>"/>
<input type="hidden" id="payer_id" name="payer_id" value="<?=$payer_id;?>"/>
<p><label for="tourist-dropdown">Type the Name of the Tourist You Want to Add</label><br/>
<input type="text" id="tourist-dropdown" name="tourist-dropdown" value=""/></p>
</form>
</div>

</div>
<p>
<input type="submit" name="save" id="save" value="<?=ucfirst($action);?>"/>
</p>
</form>