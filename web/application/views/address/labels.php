<?php
defined('BASEPATH') or exit ('No direct script access allowed'); ?>
<html>
<head>
	<link type="text/css" rel="stylesheet" media="all" href="<?php print site_url('css/labels.css'); ?>">
</head>
<body>
<?php if (!empty($addresses)): ?>
	<?php $page_break = 0; ?>
	<?php foreach ($addresses as $address): ?>
		<div class="label">
			<div class="salutation"><?php print $address->formal_salutation; ?></div>
			<div class="street-address"><?php print $address->address; ?></div>
			<div class="locality">
				<span class="city"><?php print $address->city; ?></span>,
				<span class="state"><?php print $address->state; ?></span>
				<span class="zip"><?php print $address->zip; ?></span>
			</div>
		</div>

		<?php $page_break++; ?>
		<?php if ($page_break % 30 === 0): ?>
			<div class="page-break"></div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
</body>
</html>
