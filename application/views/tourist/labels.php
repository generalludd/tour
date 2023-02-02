<?php
defined('BASEPATH') or exit('No direct script access allowed');
$output = [];
if (empty($payers)) {
	return FALSE;
}
$page_break = 0;
$row_break = 0; ?>
<html>
<head>
	<link type="text/css" rel="stylesheet" media="all" href="<?php print site_url('css/labels.css'); ?>">
</head>
<body>
	<?php foreach ($payers as $payer): ?>
	<div class="label">
		<div class="payer"><?php print $payer->first_name . ' ' . $payer->last_name; ?></div>
		<?php $tourists = []; ?>
		<?php if (!empty($payer->tourists)): ?>
			<?php foreach ($payer->tourists as $tourist): ?>
				<?php if ($tourist->person_id != $payer->payer_id): ?>
					<?php $tourists[] = $tourist->first_name . ' ' . $tourist->last_name; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<div class="tourists"><?php print implode(', ', $tourists); ?></div>
		<?php endif; ?>
		<div class="street-address"><?php print $payer->address; ?></div>
		<div class="locality"><?php print $payer->city . ', ' . $payer->state . ' ' . $payer->zip; ?></div>
	</div>
	<?php $row_break++; ?>

	<?php $page_break++; ?>
	<?php if ($page_break % 30 === 0): ?>
</div>
			<div class="page-break"></div>
	<?php endif; ?>
	<?php endforeach; ?></body>
</html>

