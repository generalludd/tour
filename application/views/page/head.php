<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<meta http-equiv="cache-control" content="no-store" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="-1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php print $title;?></title>
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/main.css') . "?" . date("u");?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/color.css'). "?" . date("u")?>"/>
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/popup.css'). "?" . date("u")?>" />
<link type="text/css" rel="stylesheet" media="print" href="<?php print base_url('css/print.css')?>" />
<!-- jquery scripts -->
<script type="text/javascript">
var base_url = '<?php print base_url("index.php") . "/";?>';
var root_url = '<?php print base_url();?>';
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<!-- Custom Scripts  -->
<script type="text/javascript" src="<?php print base_url("js/general.js");?>"></script>
<script type="text/javascript" src="<?php print base_url('js/tour.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/payer.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/person.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/address.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/hotel.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/contact.js');?>"></script>

<script type="text/javascript" src="<?php print base_url('js/phone.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/receipt.js');?>"></script>

<script type="text/javascript" src="<?php print base_url('js/roommate.js');?>"></script>
<script type="text/javascript" src="<?php print base_url('js/payment.js');?>"></script>

<!-- admin scripts -->
<script type="text/javascript" src="<?php print base_url('js/password.js');?>"></script>
