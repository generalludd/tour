<?php defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($print) && $print == TRUE){
	$print = TRUE;
}else{
	$print = FALSE;
}
$body_class = $this->uri->segment(1);
if($this->uri->segment(1) == ""){
	$body_class = "front";
}
?>
<!DOCTYPE html>
<html>
<head>
<? $this->load->view('page/head');?>
</head>
<body class="browser <?=$body_class;?>">
<div id="page">
<?php if(!$print): ?>
<div id='header'>

<div id='page-title'>Ball Park Tours</div>
<div id='navigation'>
<?  $this->load->view('page/navigation'); ?>
<div id='utility'><? $this->load->view('page/utility');?></div>

</div>
</div>
<?php endif; ?>


<!-- main -->

<div id="main"><!-- content -->
<div id="content">
<?php if($this->session->flashdata("notice")):?>
<div id="notice"><?=$this->session->flashdata("notice");
?></div>
<?php endif;?>
<?php if($this->session->flashdata("alert") || BACKUP_STATUS > 1209599):?>
<div id="alert"><?=$this->session->flashdata("alert");
?>
<?php if(BACKUP_STATUS > 1209599):?>
<p>
<marquee>
 It has been over <?php echo round(BACKUP_STATUS/60/60/24,0); ?> days since the last backup. You should <a href="<?php echo site_url("backup"); ?>" class="hide-backup-warning">click here to backup now</a>. 
</marquee>
</p>
<?php endif;?>

</div>
<?php endif; ?>
<?
$this->load->view($target);
?></div>
<!-- end content -->
<div id="sidebar"></div>
<!-- end sidebar --></div>
<div id='search_list'></div>
<div id="footer"><?$this->load->view('page/footer');?></div>
</div>
</body>
</html>
