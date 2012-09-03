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

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contao Check <?php echo CONTAO_CHECK_VERSION ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div id="wrapper">
  <h1>Contao Check</h1>
  <div class="row">
	<h2><?php echo _('Overview') ?></h2>
  </div>
  <?php if (version_compare(phpversion(), '5.3.2', '<')): ?>
    <div class="row">
      <p class="error"><?php printf(_('You need at least PHP 5.3.2 to run Contao (you have version %s).'), phpversion()) ?></p>
    </div>
  <?php else: ?>
    <div class="row">
      <h3><?php echo _('Requirements') ?></h3>
      <ul>
        <li><a href="repository.php"><?php echo _('Can I use the Extension Repository?') ?></a></li>
        <li><a href="live-update.php"><?php echo _('Can I use the Live Update Service?') ?></a></li>
        <li><a href="safe-mode-hack.php"><?php echo _('Do I need to use the Safe Mode Hack?') ?></a></li>
      </ul>
    </div>
    <div class="row">
      <h3><?php echo _('Installation') ?></h3>
      <ul>
        <li><a href="install.php"><?php echo _('Install the latest Contao version') ?></a></li>
        <li><a href="validate.php"><?php echo _('Validate an existing Contao installation') ?></a></li>
      </ul>
      <p class="explain mt"><?php printf(_('Contao Check %s | Contao Open Source CMS system requirements | More information at %s'), CONTAO_CHECK_VERSION, '<a href="http://contao.org">contao.org</a>') ?></p>
    </div>
  <?php endif; ?>
</div>
</body>
</html>