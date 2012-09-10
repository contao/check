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


/**
 * Download a Contao .zip archive and extract it
 * 
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2012
 */
class Installer
{

	/**
	 * Shell function
	 * @var string
	 */
	protected $shell;

	/**
	 * Shell download command
	 * @var string
	 */
	protected $download;

	/**
	 * Shell unzip command
	 * @var string
	 */
	protected $unzip;

	/**
	 * Target version
	 * @var string
	 */
	protected $version;

	/**
	 * PHP requirements met
	 * @var boolean
	 */
	protected $php = false;

	/**
	 * Installer availability
	 * @var boolean
	 */
	protected $available = true;

	/**
	 * FTP required
	 * @var boolean
	 */
	protected $ftp = false;

	/**
	 * Existing installation
	 * @var boolean
	 */
	protected $existing = null;


	/**
	 * Check the requirements and start the installation
	 */
	public function run()
	{
		if ($this->hasInstallation()) {
			return;
		}	

		if (!$this->canInstall()) {
			$this->ftp = true;
		} elseif (!$this->canUseShell() && !$this->canUsePhp()) {
			$this->available = false;
		} else {
			$this->install();
		}
	}


	/**
	 * Make sure there is no existing installation
	 * 
	 * @return boolean True if there is no existing installation
	 */
	protected function canInstall()
	{
		$safe_mode = ini_get('safe_mode');

		// Safe mode enabled
		if ($safe_mode != '' && $safe_mode != 0 && $safe_mode != 'Off') {
			return false;
		}

		// Try to create a folder
		if (@mkdir('test') === false) {
			return false;
		} else {
			clearstatcache();
			$self = posix_getpwuid(@fileowner(__DIR__));
			$test = posix_getpwuid(@fileowner('test'));
			@rmdir('test');

			if ($self != $test) {
				return false;
			}
		}

		// Try to create a file
		if (@file_put_contents('test.txt', '') === false) {
			return false;
		} else {
			clearstatcache();
			$self = posix_getpwuid(@fileowner(__FILE__));
			$test = posix_getpwuid(@fileowner('test.txt'));
			@unlink('test.txt');

			if ($self != $test) {
				return false;
			}
		}

		return true;
	}


	/**
	 * Check whether the shell can be used to install Contao
	 * 
	 * @return boolean True if the shell can be used to install Contao
	 */
	protected function canUseShell()
	{
		// Check for shell_exec() or exec()
		if (function_exists('shell_exec')) {
			$this->shell = 'shell_exec';
		} elseif (function_exists('exec')) {
			$this->shell = 'exec';
		}

		// Return if we cannot access the shell
		if ($this->shell == '') {
			return false;
		}

		// Check for wget or curl
		if ($this->exec('which wget') != '') {
			$this->download = 'wget';
		} elseif ($this->exec('which curl') != '') {
			$this->download = 'curl';
		}

		// Return if we cannot download on the shell
		if ($this->download == '') {
			return false;
		}

		// Check for unzip
		if ($this->exec('which unzip') != '') {
			$this->unzip = 'unzip';
		}

		// Return if we cannot unzip on the shell
		if ($this->unzip == '') {
			return false;
		}

		return true;
	}


	/**
	 * Check whether PHP can be used to install Contao
	 * 
	 * @return boolean True if PHP can be used to install Contao
	 */
	protected function canUsePhp()
	{
		// Check whether cURL and Zip are available
		if (!extension_loaded('curl') || !extension_loaded('zip')) {
			return false;
		}

		// Try to write a file
		if (@file_put_contents('download', '') === false) {
			return false;
		}

		$this->php = true;
		@unlink('download');

		return true;
	}


	/**
	 * Execute a shell command and trim the result
	 * 
	 * @param string $command The shell command
	 * 
	 * @return string The trimmed output string
	 */
	protected function exec($command)
	{
		$function = $this->shell;
		return trim($function($command));
	}


	/**
	 * Retrieve information using cURL
	 * 
	 * @param string $url The URL
	 * 
	 * @return string The output string
	 */
	protected function curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		$return = curl_exec($ch);
		curl_close($ch);

		return $return;
	}


	/**
	 * Check whether the automatic installation is possible
	 * 
	 * @return boolean True if the automatic installation is possible
	 */
	public function isAvailable()
	{
		return $this->available;
	}


	/**
	 * Check whether FTP is required to install Contao
	 * 
	 * @return boolean True if FTP is required to install Contao
	 */
	public function requiresFtp()
	{
		return $this->ftp;
	}


	/**
	 * Check whether there is an existing installation already
	 * 
	 * @return boolean True if there is an existing installation
	 */
	public function hasInstallation()
	{
		if ($this->existing === null) {
			if (file_exists("../system/constants.php")) {
				$this->existing = true;
			} elseif (file_exists("../system/config/constants.php")) {
				$this->existing = true;
			} else {
				$this->existing = false;
			}
		}

		return $this->existing;
	}


	/**
	 * Return the available version numbers
	 * 
	 * @return array The versions array
	 */
	public function getVersions()
	{
		$versions = array();

		$files = scandir('versions');
		natsort($files);
		$files = array_reverse($files);

		// Get the version numbers
		foreach ($files as $file) {
			list($maj, $min, $bfx, $ext) = explode('.', $file);

			if ($ext == 'json') {
				$versions["$maj.$min"][] = "$maj.$min.$bfx";
			}
		}

		return $versions;
	}


	/**
	 * Start the installation
	 * 
	 * @throws Exception In case the version number is invalid
	 */
	protected function install()
	{
		if (!isset($_POST['version']) || $this->ftp) {
			return;
		}

		$version = filter_var($_POST['version'], FILTER_SANITIZE_STRING);

		// Validate the version number
		if (!file_exists('versions/' . $version . '.json')) {
			throw new Exception("Invalid version number $version");
		}

		list($maj, $min, $bfx) = explode('.', $version);
		$url = "http://sourceforge.net/projects/contao/files/$maj.$min/contao-$maj.$min.$bfx.zip/download";

		if ($this->php === false) {
			if ($this->download == 'wget') {
				$this->exec("wget $url");
			} elseif ($this->download == 'curl') {
				$this->exec("curl -s -L $url > download");
			}

			// Extract
			if (file_exists('download')) {
				$this->exec($this->unzip . ' download');			
				$this->exec('rm download');
				$folder = $this->exec('ls -d contao-*');
				$this->exec("mv $folder/* ../");
				$this->exec("rm -rf $folder");
			}
		} else {
			file_put_contents('download', $this->curl($url));

			// Extract
			if (file_exists('download')) {
				$zip = new ZipArchive;
				$zip->open('download');
				$zip->extractTo(dirname(__DIR__));
				$zip->close();
				unlink('download');
			}
		}
	}
}


$installer = new Installer;
$installer->run();

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
    <h2><?php echo _('Installation') ?></h2>
  </div>
  <div class="row">
    <h3><?php echo _('Web installer') ?></h3>
    <?php if ($installer->hasInstallation()): ?>
      <p class="error"><?php printf(_('There is a Contao installation already. Are you looking for the %s?'), '<a href="http://luid.inetrobots.com" target="_blank">Live Update</a>') ?></p>
    <?php elseif ($installer->requiresFtp()): ?>
      <p class="error"><?php echo _('The automatic installation is not possible on your server due to safe_mode or file permission restrictions.') ?></p>
    <?php elseif ($installer->isAvailable()): ?>
      <p class="confirm"><?php echo _('The automatic installation is possible on your server.') ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The automatic installation is not possible on your server.') ?></p>
      <p class="explain"><?php echo _('Your PHP installation does not meet the requirements to use the command line, does not have enough permissions to create files and folders or does not have the required PHP extensions "cURL" and "Zip".') ?></p>
    <?php endif; ?>
  </div>
  <?php if (!$installer->hasInstallation() && !$installer->requiresFtp()): ?>
    <div class="row">
      <?php if (!$installer->isAvailable()): ?>
        <h3><?php echo _('Manual installation') ?></h3>
        <ul>
          <li><?php printf(_('Go to %s and download the latest Contao version.'), '<a href="http://sourceforge.net/projects/contao/files/">sourceforge.net</a>') ?></li>
          <li><?php echo _('Extract the download archive and upload the files to your server using an (S)FTP client.') ?></li>
          <li><?php echo _('Open the Contao install tool by adding "/contao" to the URL of your installation.') ?></li>
        </ul>
      <?php elseif (!isset($_POST['version'])): ?>
        <h3><?php echo _('Target version') ?></h3>
        <form method="post">
          <div class="versions">
            <select name="version">
            <?php
              foreach ($installer->getVersions() as $group=>$versions) {
                echo '<optgroup label="' . $group . '">';
                foreach ($versions as $version) {
                  echo '<option value="' . $version . '">Contao ' . $version . '</option>';
		  	    }
                echo '</optgroup>';
              }
            ?>
            </select>
          </div>
          <p class="explain"><?php echo _('Attention: Deprecated versions might contain security vulnerabilities! Please install the latest stable version or the latest LTS version (long term support version).') ?></p>
          <p class="mt"><input class="btn" type="submit" value="<?php echo _('Start the installation') ?>"></p>
        </form>
      <?php else: ?>
        <h2><?php echo _('Installation complete') ?></h2>
        <p class="confirm"><?php printf(_('Contao %s has been installed in %s.'), filter_var($_POST['version'], FILTER_SANITIZE_STRING), dirname(__DIR__)) ?></p>
        <p class="mt"><a href="../contao/install.php" class="btn"><?php echo _('Open the Contao install tool') ?></a></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <p class="back"><a href="."><?php echo _('Go back') ?></a></p>
</div>
<script src="assets/script.js"></script>
</body>
</html>