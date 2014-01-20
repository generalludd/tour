<?php defined('BASEPATH') OR exit('No direct script access allowed');

// mini_list.php Chris Dart Dec 16, 2013 7:44:01 PM chrisdart@cerebratorium.com

?>
<table>
<? foreach($people as $person): ?>
<tr>
<td><?=sprintf("%s %s", $person->first_name, $person->last_name);?></td>
<td>Vet: <input type="checkbox" name="is_veteran" id="is_veteran" value="1" <?=get_value($person, "is_veteran","FALSE")? "checked":"";?>/>
</td>
<td><?=get_value($person, "email");?></td>
<td>
<a href="<?=site_url("person/view/$person->id");?>" class="button mini">Show</a>
</td>
<td>
<?=create_button(array("text"=>"Join Tour", "type"=>"span","class"=>"button new mini select-tour", "id"=>sprintf("join-tour_%s",$person->id)));?>
</td>
</tr>
<? endforeach;?>
</table>

