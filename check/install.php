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

// Check for shell_exec() or exec()
if (function_exists('shell_exec')) {
	$exec = 'shell_exec';
} elseif (function_exists('exec')) {
	$exec = 'exec';
}

// Check for wget and unzip
if ($exec) {
	function do_exec($str) {
		global $exec;
		return trim($exec($str));
	}
	if (do_exec('which wget') != '' && do_exec('which unzip') != '') {
		$cli = true;
	}
}

// Check for cURL and Zip
if ($cli !== true) {
	$curl = extension_loaded('curl');
	$zip = extension_loaded('zip');

	// Try to write a file
	@file_put_contents('download', '');
	$create = (@file_exists('download') && @is_writable('download'));
	@unlink('download');

	$php = ($curl && $zip && $create);
}

// Start the installation
if (isset($_GET['download'])) {
	if ($cli) {
		$version = cli_installation();
	} elseif ($php) {
		$version = php_installation();
	} 	
}

// CLI installation
function cli_installation() {
	do_exec("wget http://sourceforge.net/projects/contao/files/latest/download");
	do_exec("unzip download");
	do_exec("rm download");
	return do_exec('ls -d contao-*');
}

// PHP installation
function php_installation() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, 'http://sourceforge.net/projects/contao/files/latest/download');
	file_put_contents('download', curl_exec($ch));
	curl_close($ch);

	$zip = new ZipArchive;
	$zip->open('download');
	$zip->extractTo('.');
	$zip->close();

	unlink('download');
	$matches = array_values(preg_grep('/^contao-/', scandir('.')));
	return $matches[0];
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
    <h1>Contao Check <small><?php echo _('Installation') ?></small></h1>
  </div>
  <div class="row">
    <h2><?php echo _('Web installer') ?></h2>
    <?php if ($cli): ?>
      <p class="confirm"><?php printf(_('Will be using %s(), wget and unzip for the installation.'), $exec) ?></p>
    <?php elseif ($php): ?>
      <p class="confirm"><?php echo _('Will be using cURL and Zip for the installation.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The automatic installation is not possible on your server.') ?></p>
      <p class="explain"><?php echo _('Your PHP installation does not meet the requirements to use the command line, does not have enough permissions to create files and folders or does not have the required PHP extensions "cURL" and "Zip".') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if (!$cli && !$php): ?>
      <h2><?php echo _('Manual installation') ?></h2>
      <ul>
        <li><?php printf('Go to %s and download the latest Contao version.', '<a href="http://sourceforge.net/projects/contao/files/">sourceforge.net</a>') ?></li>
        <li><?php echo _('Extract the download archive and upload the files to your server using an (S)FTP client.') ?></li>
        <li><?php echo _('Open the Contao install tool by adding "/contao" to the URL of your installation.') ?></li>
      </ul>
    <?php elseif (!isset($_GET['download'])): ?>
      <p><a href="install.php?download" class="btn"><?php echo _('Start the installation') ?></a></p>
    <?php else: ?>
      <h2><?php echo _('Installation complete') ?></h2>
      <p class="confirm"><?php printf(_('Contao has been installed in %s/%s.'), __DIR__, $version) ?></p>
      <p class="mt"><a href="<?php echo $version ?>/contao/install.php" class="btn"><?php echo _('Open the Contao install tool') ?></a></p>
    <?php endif; ?>
  </div>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
</body>
</html>