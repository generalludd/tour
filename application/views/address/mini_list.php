<?php defined('BASEPATH') OR exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 16, 2013 7:44:01 PM chrisdart@cerebratorium.com

?>
<table>
<? foreach($people as $person): ?>
<tr>
<td><?=sprintf("%s %s", $person->first_name, $person->last_name);?></td>
<td><? $button = array("text"=>"Select Housemate", "type"=>"span","class"=>"button new small select-housemate", "id"=>sprintf("select-housemate_%s",$person->address_id));?>
<?=create_button($button);?>
</td>
</tr>
<? endforeach;?>
</table>

