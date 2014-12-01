<?php defined('BASEPATH') OR exit('No direct script access allowed');

// summary.php Chris Dart Jan 14, 2014 8:29:13 PM chrisdart@cerebratorium.com

?>
<div class="tour-summary block" id="<?=sprintf("tour-summary_%s",$tour->id);?>">
<strong><?=$tour->tour_name;?></strong><br/>
<label>Start: </label><?=format_date($tour->start_date);?>,
<label>End: </label><?=format_date($tour->end_date);?>,
<label>Payment Due: </label><?=format_date($tour->due_date);?><br/>
<label>Pay-in-Full: </label><?=format_money($tour->full_price);?>, <label>Banquet Price: </label><?=format_money($tour->banquet_price);?>,
<label>Early Bird: </label><?=format_money($tour->early_price);?>, <label>Regular Price: </label><?=format_money($tour->regular_price);?><br/>
<label>Single Room: </label><?=format_money($tour->single_room);?>, <label>Triple Room: </label><?=format_money($tour->triple_room);?>,
<label>Quad Room: </label><?=format_money($tour->quad_room);?>
</div>