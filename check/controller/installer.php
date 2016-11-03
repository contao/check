<?php

/*
 * Contao check
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */

/**
 * Download a Contao .zip archive and extract it
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class Installer
{
	/**
	 * @var boolean
	 */
	protected $available = true;

	/**
	 * @var boolean
	 */
	protected $ftp = false;

	/**
	 * @var boolean
	 */
	protected $existing = null;

	/**
	 * @var string
	 */
	protected $message = '';

	/**
	 * Execute the command
	 */
	public function run()
	{
		if (!$this->hasInstallation()) {
			if (!$this->canInstall()) {
				$this->ftp = true;
			} elseif (!$this->canConnect() || !$this->canUsePhp()) {
				$this->available = false;
			} else {
				$this->install();
			}
		}

		include __DIR__ . '/../views/installer.phtml';
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
	 * Check whether a connection can be established
	 */
	public function canConnect()
	{
		$connection = @fsockopen('download.contao.org', 80, $errno, $errstr, 10);
		$connected = ($connection !== false);
		fclose($connection);

		if ($connected) {
			return true;
		}

		return false;
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

		@unlink('download');

		return true;
	}

	/**
	 * Retrieve information using cURL
	 *
	 * @param string $url The URL
	 *
	 * @return string The output string
     *
     * @throws RuntimeException If the download fails
	 */
	protected function curl($url)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0');

		if (($met = ini_get('max_execution_time')) > 0) {
			curl_setopt($ch, CURLOPT_TIMEOUT, round($met * 0.9));
		}

		// cURL will follow redirects if open_basedir is not set
		if (ini_get('open_basedir') == '') {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
			$return = curl_exec($ch);

			if (curl_errno($ch)) {
				$error = curl_error($ch);
				curl_close($ch);

				throw new RuntimeException($error);
			}

			curl_close($ch);

			return $return;
		}

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		$new = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$rch = curl_copy_handle($ch);

		curl_setopt($rch, CURLOPT_HEADER, true);
		curl_setopt($rch, CURLOPT_NOBODY, true);
		curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
		curl_setopt($rch, CURLOPT_RETURNTRANSFER, true);

		$max = 10;

		do {
			curl_setopt($rch, CURLOPT_URL, $new);
			$header = curl_exec($rch);

			if (curl_errno($rch)) {
				$error = curl_error($rch);
				curl_close($rch);

				throw new RuntimeException($error);
			}

			$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);

			if ($code == 301 || $code == 302) {
				$matches = array();
				preg_match('/Location:(.*?)\n/', $header, $matches);
				$new = trim(array_pop($matches));
			} else {
				$code = 0;
			}
		} while ($code && --$max);

		curl_close($rch);

		if ($max > 0) {
			curl_setopt($ch, CURLOPT_URL, $new);
			$return = curl_exec($ch);

			if (curl_errno($ch)) {
				$error = curl_error($ch);
				curl_close($ch);

				throw new RuntimeException($error);
			}
		} else {
			curl_close($ch);

			throw new RuntimeException('Too many redirects');
		}

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
			if (file_exists(__DIR__ . '/../../system/constants.php')) {
				$this->existing = true;
			} elseif (file_exists(__DIR__ . '/../../system/config/constants.php')) {
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
		$stable = CURRENT_VERSION;

		if (file_exists(__DIR__ . "/../versions/$stable.json")) {
			$versions[$stable] = $stable;
		}

		$lts = CURRENT_LTS_VERSION;

		if (file_exists(__DIR__ . "/../versions/$lts.json")) {
			$versions[$lts] = "$lts (LTS)";
		}

		return $versions;
	}

	/**
	 * Start the installation
	 *
	 * @throws RuntimeException In case the version number is invalid
	 */
	protected function install()
	{
		if (!isset($_POST['version']) || $this->ftp) {
			return;
		}

		$version = filter_var($_POST['version'], FILTER_SANITIZE_STRING);

		// Validate the version number
		if (!preg_match('/^[23](\.[0-9]{1,2}){2}$/', $version) || !file_exists("versions/$version.json")) {
			throw new RuntimeException("Invalid version number $version");
		}

		$url = "http://download.contao.org/$version/zip";
		$prefix = version_compare($version, '3.3.0', '>=') ? 'contao' : 'core';

		try {
			file_put_contents('download', $this->curl($url));

			// Extract
			if (file_exists('download') && filesize('download') > 0) {
				$zip = new ZipArchive;
				$zip->open('download');
				$zip->extractTo(__DIR__ . '/../../');
				$zip->close();

				unlink('download');

				// Remove the wrapper folder (see #23)
				foreach (scandir(__DIR__ . "/../../$prefix-$version") as $file) {
					if ($file != '.' && $file != '..') {
						rename(__DIR__ . "/../../$prefix-$version/$file", __DIR__ . "/../../$file");
					}
				}

				rmdir(__DIR__ . "/../../$prefix-$version");
			} else {
				$this->message = 'The installation archive could not be downloaded.';
			}
		} catch (RuntimeException $e) {
			$this->message = $e->getMessage();
		}
	}
}
