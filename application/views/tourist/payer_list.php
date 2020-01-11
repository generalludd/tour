<?php
defined('BASEPATH') or exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 14, 2013 9:29:34 PM chrisdart@cerebratorium.com

?>
<table class="list" id="payer-tourist-list">
	<tbody>
 <?php

foreach ($tourists as $tourist) :
    $button = "";
    if ($tourist->payer_id != $tourist->person_id) :
        $button = create_button(
                array(
                        "text" => "Delete",
                        "type" => "span",
                        "class" => "button delete delete-tourist",
                        "id" => sprintf("delete-tourist_%s_%s", $tourist->person_id, $tourist->tour_id)
                ));

 endif;
    ?>
<tr>
			<td><?php print sprintf("%s %s", $tourist->first_name, $tourist->last_name);?>
</td>
			<td><?php print $button;?></td>
		</tr>


<?php endforeach; ?>
</tbody>
</table>
