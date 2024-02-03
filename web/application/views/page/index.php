<?php defined('BASEPATH') or exit('No direct script access allowed');
if (isset($print)) {
	$print = TRUE;
}
else {
	$print = FALSE;
}
$body_class = $this->uri->segment(1);
if ($this->uri->segment(1) == '') {
	$body_class = 'front';
}
if (empty($title)) {
	$title = NULL;
}
if(empty($target)){
	return FALSE;
}

?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('page/head'); ?>
</head>
<body class="browser <?php print $body_class; ?>">
<div id="page">
	<?php if (!$print): ?>
		<div id="header">
			<div id="super-header">
				<h1 id="page-title"><?php print get_page_title($title) ?></h1>
				<div id="utility"><?php $this->load->view('page/utility'); ?></div>
			</div>
			<div id="navigation">
				<?php $this->load->view('page/navigation'); ?>
			</div>
		</div>
	<?php endif; ?>
	<div id="main">
		<div id="content">
			<?php $this->load->view('page/messages'); ?>
			<?php $this->load->view($target); ?>
		</div>
	<div id='search-list'></div>
	<div id="footer"><?php $this->load->view('page/footer'); ?></div>
</div>
</body>
</html>
