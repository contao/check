<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * @package Check
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Download a Contao .zip archive and extract it
 *
 * @package   Check
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @copyright Leo Feyer 2013-2014
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
	 * Execute the command
	 */
	public function run()
	{
		if (!$this->hasInstallation()) {
			if (!$this->canInstall()) {
				$this->ftp = true;
			} elseif (!$this->canUsePhp() && !$this->canUseShell()) {
				$this->available = false;
			} else {
				$this->install();
			}
		}

		include 'views/installer.phtml';
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
			$self = posix_getpwuid(@fileowner(dirname(__FILE__)));
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
		// Check for exec() or shell_exec()
		if (function_exists('exec')) {
			$this->shell = 'exec';
		} elseif (function_exists('shell_exec')) {
			$this->shell = 'shell_exec';
		}

		// Return if we cannot access the shell
		if ($this->shell == '') {
			return false;
		}

		// Check for wget or curl
		if ($this->exec('command -v wget') != '') {
			$this->download = 'wget';
		} elseif ($this->exec('command -v curl') != '') {
			$this->download = 'curl';
		}

		// Return if we cannot download on the shell
		if ($this->download == '') {
			return false;
		}

		// Check for unzip
		if ($this->exec('command -v unzip') != '') {
			$this->unzip = 'unzip';
		}

		// Return if we cannot unzip on the shell
		if ($this->unzip == '') {
			return false;
		}

		// Check for mv and rm in case the shell is limited (see #23)
		if ($this->exec('command -v mv') == '' || $this->exec('command -v rm') == '') {
			$this->shell = '';
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
			if (file_exists(TL_ROOT . '/system/constants.php')) {
				$this->existing = true;
			} elseif (file_exists(TL_ROOT . '/system/config/constants.php')) {
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

		$url = "http://download.contao.org/$version/zip";
		$prefix = version_compare($version, '3.3.0', '>=') ? 'contao' : 'core';

		if ($this->php) {
			file_put_contents('download', $this->curl($url));

			// Extract
			if (file_exists('download') && filesize('download') > 0) {
				$zip = new ZipArchive;
				$zip->open('download');
				$zip->extractTo(TL_ROOT);
				$zip->close();

				unlink('download');

				// Remove the wrapper folder (see #23)
				foreach (scandir(TL_ROOT . "/$prefix-$version")  as $file) {
					if ($file != '.' && $file != '..') {
						rename(TL_ROOT . "/$prefix-$version/$file", TL_ROOT . "/$file");
					}
				}

				rmdir(TL_ROOT . "/$prefix-$version");
			}
		} else {
			if ($this->download == 'wget') {
				$this->exec("wget -O download $url");
			} elseif ($this->download == 'curl') {
				$this->exec("curl -s -L $url > download");
			}

			// Extract
			if (file_exists('download') && filesize('download') > 0) {
				$this->exec($this->unzip . ' download');
				$this->exec('rm download');

				// Remove the wrapper folder (see #23)
				$this->exec("mv $prefix-$version/* " . TL_ROOT . '/');
				$this->exec("mv $prefix-$version/.[a-z]* " . TL_ROOT . '/'); // see #22
				$this->exec("rm -rf $prefix-$version");
			}
		}
	}
}
