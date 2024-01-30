<?php

defined('BASEPATH') or exit('No direct script access allowed');
if (empty($contact)) {
	return FALSE;
}

?>
<form name="contact-editor" id="contact-editor" action="<?php print site_url('contact/' . $action); ?>" method="post">

<?php $field_list = [
		'contact_id' => [
				'attributes' => [
						'type' => 'hidden',
				],
		],
		'hotel_id' => [
				'attributes' => ['type' => 'hidden'],
		],
		'contact' => [
				'attributes' => ['required' => 'required'],
		],
		'position' => [
				'attributes' => [
						'required' => 'required',
				]
		],
		'phone' => [
				'attributes' => ['type' => 'tel'],
		],
		'fax' => [
				'attributes' => [
						'type' => 'tel',

				],
		],
		'email' => [
				'attributes' => [
						'type' => 'email',
				],

		],
];
?>
<?php foreach ($field_list as $key => $item): ?>
	<?php $item['id'] = $key;
	$item['attributes']['value'] = get_value($contact, $key);
	$item['wrapper_classes'] = ['vertical'];
	$item['wrapper'] = 'div';
	$this->load->view('elements/input-field', $item);
	?>

<?php endforeach; ?>
<input type="submit" class="button add" value="<?php print ucfirst($action); ?>"/>
</form>
