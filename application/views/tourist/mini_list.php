<?php defined('BASEPATH') OR exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 16, 2013 7:44:01 PM chrisdart@cerebratorium.com

?>
<table>
<? foreach($people as $person): ?>
<tr>
<td><?=sprintf("%s %s", $person->first_name, $person->last_name);?></td>
<td><span class="button mini select_for_tour" id="<?=sprintf("select-for-tour_%s_%s_%s",$person->id, $payer_id, $tour_id);?>">Select</span>
</td>
</tr>
<? endforeach;?>
</table>

