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
  <title>Contao Check</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div id="wrapper">
  <div class="row">
    <h1>Contao Check <small><?php echo _('Overview') ?></small></h1>
  </div>
  <?php if (version_compare(phpversion(), '5.3.2', '<')): ?>
    <div class="row">
      <p class="error"><?php printf(_('You need at least PHP 5.3.2 to run Contao (you have version %s).'), phpversion()) ?></p>
    </div>
  <?php else: ?>
    <div class="row">
      <h2><?php echo _('Requirements') ?></h2>
      <ul>
        <li><a href="repository.php"><?php echo _('Can I use the Extension Repository?') ?></a></li>
        <li><a href="live-update.php"><?php echo _('Can I use the Live Update Service?') ?></a></li>
        <li><a href="safe-mode-hack.php"><?php echo _('Do I need to use the Safe Mode Hack?') ?></a></li>
      </ul>
    </div>
    <div class="row">
      <h2><?php echo _('Installation') ?></h2>
      <ul>
        <li><a href="install.php"><?php echo _('Install the latest Contao version') ?></a></li>
        <li><a href="validate.php"><?php echo _('Validate an existing Contao installation') ?></a></li>
      </ul>
      <p class="explain mt"><?php echo _('If the automatic installation fails, the installer will give you an overview of the necessary steps to install Contao manually. If you have installed Contao already, you can validate your installation and have the script check for missing or corrupt files.') ?></p>
    </div>
  <?php endif; ?>
</div>
</body>
</html>