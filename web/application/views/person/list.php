<?php

defined('BASEPATH') or exit ('No direct script access allowed');
$this->load->view("person/alphabet");

?>
<?php if (!empty($filters)) : ?>
	<details>
		<summary>Filters</summary>
		<ul>
			<?php

			$names = array_keys($filters);
			foreach ($names as $name) :
				?>
				<?php
				switch ($name) {
					case 'initial':
						$name = 'Limited to the Letter: ' . $filters [$name];
						break;
					case 'order_by':
						[$identifier, $direction] = explode('-', $filters[$name]);
						[$table, $field] = explode('.', $identifier);
						$field = str_replace('_', ' ', $field);
						$name = sprintf('Sorted by: %s, %sending', ucwords($field), ucfirst(strtolower($direction)));
						break;
					case 'has_shirtsize':
						$name = 'Has Shirt Size';
						break;
					case 'veterans':
						$veterans = veterans_choices();
						$name = $veterans[$filters[$name]];
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
	</details>
<?php endif; ?>
<p class="message">
	Total Person Count: <?php print count($people); ?>
	<br/>
	Total Address Count: <?php print $address_count; ?>
</p>
<?php
$buttons [] = [
	'text' => 'Add a New Person',
	'href' => base_url('person/create'),
	'class' => 'button new dialog',
];

$buttons[] = [
	'text' => 'Filter Results',
	'href' => base_url('person/show_filter'),
	'class' => 'button dialog view',
];

$buttons [] = [
	'text' => 'Export Records',
	'href' => site_url('/person/export'),
	'class' => 'button export export-people-records',
];; ?>

<?php print create_button_bar($buttons);
?>
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
			<td><a href="<?php print site_url('person/view/' . $person->id); ?>"
						 title="View <?php print $person->first_name; ?>'s details.">
					<?php echo $person->first_name . ' ' . $person->last_name; ?></a>
			</td>
			<td><?php echo $person->email; ?></td>
			<td><?php echo $person->shirt_size; ?></td>
			<td><?php echo $person->is_veteran ? '✅' : '🚫'; ?></td>
			<td><?php echo $person->status ? '✅' : '🚫'; ?></td>

			<td>
				<a href="<?php print site_url('person/view/' . $person->id); ?>"
					 title="View <?php print $person->first_name; ?>'s details."
					 class="button small">View</a>
			</td>
			<td>
				<?php

				$button = [
					'text' => 'Join Tour',
					'class' => 'button new small dialog',
					'href' => base_url('tours/join_tour/' . $person->id),
				];
				print create_button($button);
				?>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
