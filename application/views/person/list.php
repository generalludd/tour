<?php defined('BASEPATH') OR exit('No direct script access allowed');

// list.php Chris Dart Dec 16, 2013 10:32:15 PM chrisdart@cerebratorium.com
$this->load->view("person/alphabet");
$buttons[] = array("text"=>"Add a New Person", "type"=>"span", "class" => "button new create-person");
print create_button_bar($buttons);
?>
<table class="list" id="person-list">
<? foreach($people as $person): ?>
<tr>
<td>
<?=$person->first_name;?>
</td>
<td>
<?=$person->last_name;?>
</td>
<td>
<a href="<?=site_url("person/view/$person->id");?>" class="button mini">View</a>
</td>
<td>
<? $button = array("text"=>"Join Tour", "type"=>"span","class"=>"button new mini select-tour", "id"=>sprintf("join-tour_%s",$person->id));
 print create_button($button);?>
</td>
</tr>
<? endforeach; ?>
</table>