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
		'text' => 'Filter Results',
		'href' => base_url('person/show_filter'),
		'class' => 'button dialog view',
];

$buttons [] = [
		'text' => 'Export Records',
		'href' => site_url('/person/export'),
		'class' => 'button export export-people-records',
];

// $filters = get_cookie("person_filter");
// if($fiolters): $filters = unserialize($options); ?>
<?php if (!empty($filters)): ?>
	<fieldset>
		<legend>Filters</legend>
		<ul>
			<?php

			$names = array_keys($filters);
			foreach ($names as $name) :
				?>
				<?php
				switch($name){
					case 'initial':
					$name = 'Limited to the Letter: ' . $filters [$name];
					break;
					case 'order_by':
						[$identifier,$direction] = explode('-',$filters[$name]);
						[$table, $field] = explode('.', $identifier);
						$name = sprintf('Sorted by: %s, %sending', ucfirst(str_replace('.',' ', $field)) , ucfirst(strtolower($direction)) );
						break;
					default:
					$name = str_replace('_', ' ', $name);
					$name = ucwords($name);
					break;

				}
			
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
	<thead>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Shirt Size</th>
		<th>Is Vet?</th>
		<th>Is Active?</th>
		<th colspan="2">Actions</th>
	</tr>
	</thead>
	<?php foreach ($people as $person): ?>
		<?php $disabled = $person->status == 0 ? 'highlight' : ''; ?>
		<tr class="<?php print $disabled; ?>">
			<td><a href="<?php print site_url('person/view/' . $person->id); ?>" title="View <?php print $person->first_name; ?>'s details.">
				<?php print $person->first_name . ' ' . $person->last_name; ?></a>
			</td>
			<td><?php print $person->email; ?></td>
			<td><?php print $person->shirt_size; ?></td>
			<td><?php print $person->is_veteran ? 'Yes' : 'No'; ?></td>
			<td><?php print $person->status ? 'Yes' : 'No'; ?></td>

			<td>
				<a href="<?php print site_url('person/view/' . $person->id); ?>"  title="View <?php print $person->first_name; ?>'s details."
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
