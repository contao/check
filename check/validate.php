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
define('TL_ROOT', dirname(__DIR__));

// Find the constants.php
if (file_exists("../system/constants.php")) {
	include "../system/constants.php";
} elseif (file_exists("../system/config/constants.php")) {
	include "../system/config/constants.php";
} else {
	$found = false;
}

$errors = array('missing'=>array(), 'corrupt'=>array());

// Load the file hashes
if ($found !== false) {
	$file = 'versions/' . VERSION . '.' . BUILD . '.json';

	if (!file_exists($file)) {
		$unknown = true;
	} else {
		$hashes = json_decode(file_get_contents($file));

		foreach ($hashes as $info) {
			list($path, $md5_file, $md5_code) = $info;

			if (!$md5_code) {
				$md5_code = $md5_file;
			}

			if (!file_exists('../' . $path)) {
				$errors['missing'][] = $path;
			} else {
				$buffer = str_replace("\r", '', file_get_contents('../' . $path));

				// Check the MD5 hash with and without comments
				if (strncmp(md5($buffer), $md5_file, 10) !== 0) {
					if (strncmp(md5(preg_replace('@/\*.*\*/@Us', '', $buffer)), $md5_code, 10) !== 0) {
						$errors['corrupt'][] = $path;
					}
				}

				$buffer = null;
			}
		}
	}
}

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
    <h1>Contao Check <small><?php echo _('Validate an installation') ?></small></h1>
  </div>
  <?php if ($found === false): ?>
    <div class="row">
      <h2><?php echo _('Installation') ?></h2>
      <p class="error"><?php echo _('Could not find a Contao installation.') ?></p>
      <p class="explain"><?php echo _('To validate an existing installation, please upload the "check" folder to your installation directory.') ?></p>
    </div>
  <?php elseif ($unknown === true): ?>
    <div class="row">
      <h2><?php echo _('Unknown version') ?></h2>
      <p class="error"><?php printf(_('The installed version %s is not (yet) supported.'), VERSION . '.' . BUILD) ?></p>
      <p class="explain"><?php echo _('There is no version file for your Contao installation. Are you using a stable Contao version and do you have the latest version of the Contao Check?') ?></p>
    </div>
  <?php else: ?>
  <?php if (!empty($errors['missing'])): ?>
    <div class="row">
      <h2><?php echo _('Missing files') ?></h2>
      <ul class="validate">
        <?php foreach ($errors['missing'] as $file): ?>
          <li><?php echo $file ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php if (!empty($errors['corrupt'])): ?>
    <div class="row">
      <h2><?php echo _('Corrupt files') ?></h2>
      <ul class="validate">
        <?php foreach ($errors['corrupt'] as $file): ?>
          <li><?php echo $file ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <div class="row">
    <?php if (empty($errors['missing']) && empty($errors['corrupt'])): ?>
	  <p class="confirm large"><?php echo _('Your installation is up to date.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('Your installation is not up to date.') ?></p>
	<?php endif; ?>
  </div>
  <?php endif; ?>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>