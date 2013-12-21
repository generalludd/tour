<?php

defined('BASEPATH') or exit('No direct script access allowed');
// select_list.php Chris Dart Dec 20, 2013 7:43:44 PM
// chrisdart@cerebratorium.com

?>
<div>
Select the payer below as the host for <?=$tourist_name;?> on the tour <?=$tour_name;?>
<br/>
If the payer is not in this list, you must add them first before adding tourists
</div>
<table class="list">
<? foreach($payers as $payer): ?>
<tr>
		<td>
<?=sprintf("%s %s",$payer->first_name, $payer->last_name);?>
</td>
		<td>
<?=create_button(array("text"=>"Select", "class"=>"button mini select-payer", "type"=>"span","id"=>sprintf("select-payer_%s_%s_%s", $tourist_id, $payer->payer_id, $tour_id)));?>
</td>
	</tr>
<? endforeach;?>
</table>