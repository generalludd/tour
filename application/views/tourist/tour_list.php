<?php

defined('BASEPATH') or exit('No direct script access allowed');

// tours_list.php Chris Dart Dec 25, 2013 8:07:04 PM chrisdart@cerebratorium.com
$tours['for_tourist'] = TRUE;
$buttons[] = [
	'text' => 'Person Details',
	'href' => site_url('person/view/$tourist->person_id'),
];
$buttons[] = [
	'text' => 'Join Tour',
	'class' => 'button new select-tour',
	'href' => base_url('tour/show_current/' . $tourist->person_id),
];
?>
<h3>Tour List
	for <?php print sprintf('%s %s', $tourist->first_name, $tourist->last_name); ?></h3>
<?php print create_button_bar($buttons); ?>
<?php $this->load->view('tour/list', $tours); ?>
