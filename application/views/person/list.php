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

$buttons[] = array(
        "text" => "Export Records",
        "href" => site_url("/person/export"),
        "class" => "button export export-people-records"
);

$options = get_cookie("person_filter");
?>
<? if($options):?>
<? $options = unserialize($options);?>
<fieldset>
	<legend>Filters</legend>
	<ul>
<?

$names = array_keys($options);
    foreach ($names as $name) :
        ?>
<?


if ($name == "initial") :
            $name = "Limited to the Letter: " . $options[$name];
         else :
            $name = str_replace("_", " ", $name);
            $name = ucwords($name);
        endif;
        ?>
<li><?=$name;?></li>
<? endforeach;?>
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
