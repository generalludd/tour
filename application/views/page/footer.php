<?php defined('BASEPATH') OR exit('No direct script access allowed');
if(!isset($print)):
?>
<div id="ci-version">
<?php print "CI Version: " . CI_VERSION;?>
</div>
<div id="app-version">
<?php print "App Version: " . APP_VERSION; ?>
</div>

<?php endif;

