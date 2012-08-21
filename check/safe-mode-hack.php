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

// Workaround for missing posix_getpwuid function
if (!function_exists('posix_getpwuid')) {
	function posix_getpwuid($int) {
		return array('name'=>$int);
	}
}

// Initialize some variables
$windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

// Get the php.ini settings
$safe_mode = ini_get('safe_mode');
$safe_mode_ok = ($safe_mode == '' || $safe_mode == 0 || $safe_mode == 'Off');

// Try to create a directory
if ($safe_mode_ok) {
	@mkdir('test');
	$create_dir = @is_dir('test');
	$create_dir_ok = ($create_dir === true);

	// Check the directory permissions
	if ($create_dir_ok) {
		$permissions = $windows ? array(777) : array(775, 755, 750);

		clearstatcache();
		$create_dir_chmod = decoct(@fileperms('test') & 511);
		$create_dir_chmod_ok = in_array($create_dir_chmod, $permissions);

		// Check the directory owner
		$curowner = posix_getpwuid(@fileowner(__DIR__));
		$dirowner = posix_getpwuid(@fileowner('test'));
		$create_dir_owner_ok = $curowner['name'] == $dirowner['name'];

		$create_dir_ok = ($create_dir_chmod_ok && $create_dir_owner_ok);
	}

	@rmdir('test');
}

// Try to create a file
if ($safe_mode_ok) {
	$fh = @fopen('test.txt', 'wb');
	@fputs($fh, 'This is a test file that can be deleted.');
	$create_file = is_resource($fh);
	@fclose($fh);
	$create_file_ok = ($create_file === true);

	// Check the file permissions
	if ($create_file_ok) {
		$permissions = $windows ? array(666) : array(664, 644, 660, 640);

		clearstatcache();
		$create_file_chmod = decoct(@fileperms('test.txt') & 511);
		$create_file_chmod_ok = in_array($create_file_chmod, $permissions);

		// Check the file owner
		$curowner = posix_getpwuid(@fileowner(__FILE__));
		$fileowner = posix_getpwuid(@fileowner('test.txt'));
		$create_file_owner_ok = $curowner['name'] == $fileowner['name'];

		$create_file_ok = ($create_file_chmod_ok && $create_file_owner_ok);
	}

	@unlink('test.txt');
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
    <h1>Contao Check <small><?php echo _('Safe Mode Hack') ?></small></h1>
  </div>
  <div class="row">
    <h2><?php echo _('php.ini settings') ?></h2>
    <?php if ($safe_mode_ok): ?>
      <p class="confirm"><?php echo _('The PHP safe_mode is not enabled.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The PHP safe_mode is enabled.') ?></p>
      <p class="explain"><?php echo _('It is recommended to disable the safe_mode in your php.ini or server control panel, otherwise you will have to use the Contao Safe Mode Hack to create or manipulate files. Using the Safe Mode Hack will have a negative impact on the performance of your website.') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('Creating a test folder') ?></h2>
    <?php if ($create_dir_ok): ?>
      <p class="confirm"><?php printf(_('The test folder could be created (owner: %s, chmod: %s).'), $dirowner['name'], $create_dir_chmod) ?></p>
    <?php elseif ($create_dir === false): ?>
      <p class="error"><?php echo _('The test folder could not be created.') ?></p>
      <p class="explain"><?php echo _('It seems that the PHP process does not have enough permissions to create folders on your server.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The test folder does not have the correct owner or chmod settings.') ?></p>
      <p class="explain"><?php printf(_('The test folder is owned by %s (should be %s) and has the chmod settings %s (should be %s).'), $dirowner['name'], $curowner['name'], $create_dir_chmod, ($windows ? '777' : _('775, 755 or 750'))) ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <h2><?php echo _('Creating a test file') ?></h2>
    <?php if ($create_file_ok): ?>
      <p class="confirm"><?php printf(_('The test file could be created (owner: %s, chmod: %s).'), $fileowner['name'], $create_file_chmod) ?></p>
    <?php elseif ($create_file === false): ?>
      <p class="error"><?php echo _('The test file could not be created.') ?></p>
      <p class="explain"><?php echo _('It seems that the PHP process does not have enough permissions to create files on your server.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The test file does not have the correct owner or chmod settings.') ?></p>
      <p class="explain"><?php printf(_('The test file is owned by %s (should be %s) and has the chmod settings %s (should be %s).'), $fileowner['name'], $curowner['name'], $create_file_chmod, ($windows ? '666' : _('664, 644, 660 or 640'))) ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($safe_mode_ok && $create_dir_ok && $create_file_ok): ?>
	  <p class="confirm large"><?php echo _('You do not need the Safe Mode Hack on this server.') ?></p>
	<?php else: ?>
	  <p class="error large"><?php echo _('You do need the Safe Mode Hack on this server.') ?></p>
	<?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>