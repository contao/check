<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package Check
 * @link    http://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

require 'bootstrap.php';
$soap = extension_loaded('soap');

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contao Check</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div id="wrapper">
  <div class="row">
    <h1>Contao Check <small><?php echo _('Extension Repository') ?></small></h1>
  </div>
  <div class="row">
    <h2><?php echo _('PHP SOAP extension') ?></h2>
    <?php if ($soap): ?>
      <p class="confirm"><?php echo _('The PHP SOAP extension is available.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The PHP SOAP extension is not available.') ?></p>
      <p class="explain"><?php printf(_('The PHP SOAP extension is required to communicate with the Extension Repository server. You can enable it by compiling PHP with the --enable-soap flag. Depending on your Contao version, you might also be able to use the %s extension.'), '<a href="http://contao.org/extension-list/view/nusoap.html">Nusoap</a>') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($soap): ?>
	  <p class="confirm large"><?php echo _('You can use the Extension Repository on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You cannot use the Extension Repository on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>