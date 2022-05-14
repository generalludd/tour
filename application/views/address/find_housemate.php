<?php defined('BASEPATH') or exit('No direct script access allowed');

// find_housemate.php Chris Dart Jan 6, 2014 8:29:37 PM chrisdart@cerebratorium.com
$search_data = [
		'field_name' => 'find-housemate',
		'placeholder' => 'Find Address by Person',
		'title' => 'Enter the name of someone who lives with this person',
		'data' => [
				'target' => '#search-list',
				'url' => base_url('person/find_for_address/' . $person_id),
		],
];
?>
<input type="hidden" name="person_id" id="person_id"
	   value="<?php print $person_id; ?>"/>
<label for="find-housemates">Or find an existing address by person</label><br/>
<?php $this->load->view('person/search-field', $search_data); ?>
<div id="housemate-list">
</div>
