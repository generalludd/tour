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
if (empty($target)) {
	return FALSE;
}

?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('page/head'); ?>
<body class="browser <?php print $body_class; ?>">
<div id="page">
	<?php if (!$print): ?>
		<div id="header">
			<div class="site-name">
				<h1><?php print $this->config->item('site_name'); ?></h1>
				<div id="super-header">
					<h2 id="page-title"><?php print get_page_title($title) ?></h2>
					<div id="utility"><?php $this->load->view('page/utility'); ?></div>
				</div>
				<div id="navigation">
					<?php $this->load->view('page/navigation'); ?>
					<div id="search-list" class="hidden"></div>
				</div>
			</div>
			<?php $this->load->view('page/messages'); ?>
		</div>
	<?php endif; ?>
	<div id="main">

		<div id="content">
			<?php $this->load->view($target); ?>
		</div>
		<div id="footer"><?php $this->load->view('page/footer'); ?></div>
	</div>
</body>
</html>
