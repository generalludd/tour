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


	<!-- main -->

	<div id="main"><!-- content -->
		<div id="content">
			<?php if ($this->session->flashdata('notice')): ?>
				<div id="notice"><?php print $this->session->flashdata('notice');
					?></div>
			<?php endif; ?>
			<?php if ($this->session->flashdata('alert') || BACKUP_STATUS > 1209599): ?>
				<div id="alert"><?php print $this->session->flashdata('alert');
					?>
					<?php if (BACKUP_STATUS > 1209599): ?>
						<p>
							<marquee>
								It has been
								over <?php echo round(BACKUP_STATUS / 60 / 60 / 24, 0); ?> days
								since the last backup. You should
								<a href="<?php echo site_url('backup'); ?>"
									 class="hide-backup-warning">click here to backup now</a>.
							</marquee>
						</p>
					<?php endif; ?>

				</div>
			<?php endif; ?>
			<?php
			$this->load->view($target);
			?></div>
								 <!-- end content -->
		<div id="sidebar"></div>
								 <!-- end sidebar --></div>
	<div id='search-list'></div>
	<div id="footer"><?php $this->load->view('page/footer'); ?></div>
</div>
</body>
</html>
