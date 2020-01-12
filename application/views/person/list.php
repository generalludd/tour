<?php
defined('BASEPATH') or exit ('No direct script access allowed');

// list.php Chris Dart Dec 16, 2013 10:32:15 PM chrisdart@cerebratorium.com
$this->load->view("person/alphabet");
$buttons [] = [
	'text' => 'Add a New Person',
	'href' => base_url('person/create'),
	'class' => 'button new create-person',
];
$buttons [] = [
	"text" => "Filter Results",
	"type" => "span",
	"class" => "button show-person-filter",
];

$buttons [] = [
	"text" => "Export Records",
	"href" => site_url("/person/export"),
	"class" => "button export export-people-records",
];

// $filters = get_cookie("person_filter");
// if($filters): $filters = unserialize($options); ?>
<?php if (!empty($filters)): ?>
	<fieldset>
		<legend>Filters</legend>
		<ul>
			<?php

			$names = array_keys($filters);
			foreach ($names as $name) :
				?>
				<?php

				if ($name == "initial") :
					$name = "Limited to the Letter: " . $filters [$name];

				else :
					$name = str_replace("_", " ", $name);
					$name = ucwords($name);
				endif;
				?>
				<li><?php print $name; ?></li>
			<?php endforeach; ?>
		</ul>
	</fieldset>
<?php endif; ?>
<p class="message">
	Total Person Count: <?php print count($people); ?>
	<br/>
	Total Address Count: <?php print $address_count; ?>
</p>
<?php print create_button_bar($buttons); ?>
<table class="list" id="person-list">
	<?php foreach ($people as $person): ?>
		<?php $disabled = $person->status == 0 ? "highlight" : ""; ?>
		<tr class="<?php print $disabled; ?>">
			<td>
				<?php print $person->first_name; ?>
			</td>
			<td>
				<?php print $person->last_name; ?>
			</td>
			<td>
				<a href="<?php print site_url("person/view/$person->id"); ?>"
					 class="button small">View</a>
			</td>
			<td>
				<?php

				$button = [
					'text' => 'Join Tour',
					'class' => 'button new small select-tour',
					'href' => base_url('tour/show_current/' . $person->id),
				];
				print create_button($button);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
