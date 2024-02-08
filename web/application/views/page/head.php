<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php print $title;?></title>
<!-- Latest compiled and minified CSS -->

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/main.css') . "?" . date("u");?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/popup.css'). "?" . date("u")?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/messages.css'). "?" . date("u")?>" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/buttons.css'). "?" . date("u")?>" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/elements.css'). "?" . date("u")?>" />


<?php if(!empty($styles)):?>
<?php foreach($styles as $style):?>
	<link type="text/css" rel="stylesheet" media="all" href="<?php print base_url('css/' . $style . '.css'). "?" . date("u")?>" />

<?php endforeach; ?>
<?php endif;?>
<link type="text/css" rel="stylesheet" media="print" href="<?php print base_url('css/print.css')?>" />
<!-- jquery scripts -->
<script type="text/javascript">
var base_url = '<?php print base_url("index.php") . "/";?>';
var root_url = '<?php print base_url();?>';
</script>
<script type="text/javascript" src="<?php print base_url('js/utility.js');?>"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- Custom Scripts  -->
<script type="text/javascript" src="<?php print base_url("js/general.js");?>"></script>
<script type="text/javascript" src="<?php print base_url('js/search.js');?>"></script>
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
<?php if(!empty($scripts)):?>
<?php foreach($scripts as $script):?>
	<script type="text/javascript" src="<?php print $script;?>"></script>
<?php endforeach;?>
<?php endif;
