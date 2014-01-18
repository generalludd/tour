<?php

defined('BASEPATH') or exit('No direct script access allowed');

// list.php Chris Dart Dec 16, 2013 10:32:15 PM chrisdart@cerebratorium.com
$this->load->view("person/alphabet");
$buttons[] = array(
        "text" => "Add a New Person",
        "type" => "span",
        "class" => "button new create-person"
);
$buttons[] = array(
        "text" => "Filter Results",
        "type" => "span",
        "class" => "button show-person-filter"
);
?>
<? if(count($_GET)> 0):?>
<fieldset>
	<legend>Filters</legend>
	<ul>

<? if($this->input->get("veterans_only") == 1): ?>
<li>Veterans Only</li>
<? endif; ?>
<? if($this->input->get("email_only") == 1): ?>
<li>People with Email Addresses Only</li>
<? endif;?>
<? if($this->input->get("show_disabled") == 1):?>
<li>Showing Disabled Records</li>
<? endif; ?>
<? if($this->input->get("initial")):?>
<li>Limited to the letter "<?=$this->input->get("initial");?>"</li>
<? endif; ?>
</ul>
</fieldset>
<? endif;?>
<?=create_button_bar($buttons);?>
<table
	class="list"
	id="person-list">
<? foreach($people as $person): ?>
<? $disabled=$person->status == 0 ? "highlight":""; ?>
<tr class="<?=$disabled;?>">
		<td>
<?=$person->first_name;?>
</td>
		<td>
<?=$person->last_name;?>
</td>
		<td><a
			href="<?=site_url("person/view/$person->id");?>"
			class="button small">View</a></td>
		<td>
<?

$button = array(
            "text" => "Join Tour",
            "type" => "span",
            "class" => "button new small select-tour",
            "id" => sprintf("join-tour_%s", $person->id)
    );
    print create_button($button);
    ?>
</td>
	</tr>
<? endforeach; ?>
</table>
