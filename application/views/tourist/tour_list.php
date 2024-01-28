<?php

defined('BASEPATH') or exit('No direct script access allowed');

// tours_list.php Chris Dart Dec 25, 2013 8:07:04 PM chrisdart@cerebratorium.com
$tours['for_tourist'] = TRUE;
?>
<?php $this->load->view('tour/list', $tours); ?>
