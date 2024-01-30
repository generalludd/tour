<?php defined('BASEPATH') OR exit('No direct script access allowed');

// summary.php Chris Dart Jan 14, 2014 8:29:13 PM chrisdart@cerebratorium.com

?>
<div class="tour-summary block" id="<?php print sprintf("tour-summary_%s",$tour->id);?>">
<strong><?php print $tour->tour_name;?></strong><br/>
<label>Start: </label><?php print format_date($tour->start_date);?>,
<label>End: </label><?php print format_date($tour->end_date);?>,
<label>Payment Due: </label><?php print format_date($tour->due_date);?><br/>
<label>Pay-in-Full: </label><?php print format_money($tour->full_price);?>, <label>Banquet Price: </label><?php print format_money($tour->banquet_price);?>,
<label>Early Bird: </label><?php print format_money($tour->early_price);?>, <label>Regular Price: </label><?php print format_money($tour->regular_price);?><br/>
<label>Single Room: </label><?php print format_money($tour->single_room);?>, <label>Triple Room: </label><?php print format_money($tour->triple_room);?>,
<label>Quad Room: </label><?php print format_money($tour->quad_room);?>
</div>