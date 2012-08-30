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


class Install
{
	/**
	 * The function to execute shell commands
	 * @var string
	 */
	protected $strShellFunction;

	/**
	 * The tool to fetch data on the system shell
	 * @var string
	 */
	protected $strShellFetchCommand;

	/**
	 * The tool to unzip tata on the system shell
	 * @var string
	 */
	protected $strShellUnzipCommand;

	/**
	 * Use php instead of the shell commands
	 * @var bool
	 */
	protected $blnUsePHPOverShell=false;

	/**
	 * Extract the contao installation in witch directory
	 * @var string
	 */
	protected $strExtractTo;

	/**
	 * The version witch shpould be installed
	 * @var string
	 */
	protected $strVersion;


	/**
	 * Initialize the class and so some kickstart work
	 *
	 * @param	void
	 * @return	void
	 */
	public function init()
	{
		// do some environment checks
		$this->checkShellCommands();
		$this->checkShellTools();
		$this->checkPhpExtensions();

		// set some runtime params
		$this->strExtractTo = filter_var($_GET['path'], FILTER_SANITIZE_STRING);
		$this->strVersion = filter_var($_GET['version'], FILTER_SANITIZE_STRING);

		// start the download
		$this->startDownload();
	}


	/**
	 * Check if the download is possible
	 *
	 * @param	void
	 * @return	bool	Return true if the download is possible, otherwize false
	 */
	public function installPossible()
	{
		if ($this->strShellFetchCommand != '' || $this->blnUsePHPOverShell === true)
		{
			// download possible because we have the shell or we have php
			return true;
		}

		return false;
	}


	/**
	 * Return an array with all available contao versions.
	 * The informations are fetched from the github tags
	 *
	 * Example:
	 * Array
	 * (
	 *    [3.0.beta1] => https://github.com/contao/core/zipball/3.0.beta1
	 *    [3.0.RC1] => https://github.com/contao/core/zipball/3.0.RC1
	 * )
	 *
	 * @param	void
	 * @return	array	Return an array witch all tags
	 */
	public function getTags()
	{
		$arrTags = array();
		$arrResponse = array();
		$strJson = '';
		$strCommand = '';

		// get the tags via the php internal functions
		if ($this->blnUsePHPOverShell === true)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/contao/core/tags');
			$strJson =  curl_exec($ch);
			curl_close($ch);
		}


		// get the tags with the server shell
		if ($this->blnUsePHPOverShell === false)
		{
			// use curl for the download
			if ($this->strShellFetchCommand === 'curl')
			{
				$strCommand = 'curl https://api.github.com/repos/contao/core/tags';
			}

			// use wget for the download
			if ($this->strShellFetchCommand === 'wget')
			{
				$strCommand = 'wget -qO- https://api.github.com/repos/contao/core/tags';
			}

			$strJson = $this->executeShellCommand($stCommand);
		}


		// prepare the json string and build the array
		$arrResponse = json_decode($strJson);

		// get every tag name
		foreach ($arrResponse as $v)
		{
			$arrTags[$v->name] = $v->zipball_url;
		}

		// put all new tags on the top of the list
		arsort($arrTags);

		return $arrTags;
	}


	/**
	 * Start the installation if the url para "download" is set
	 *
	 * @param	void
	 * @return	void
	 */
	protected function startDownload()
	{
		if ($_GET['download'] == 'do')
		{
			// get all tags and the zibball_url from it
			$arrTagsLookup = $this->getTags();


			// start the download with php
			if ($this->blnUsePHPOverShell === true)
			{
				$this->downloadWithPhp($arrTagsLookup[$this->strVersion]);
			}
			// start the download with the shell tools
			else
			{
				$this->downloadWithShell($arrTagsLookup[$this->strVersion]);
			}
		}
	}


	/**
	 * Download the contao package with the internal PHP functions
	 *
	 * @param	string	$strUrl
	 * @return	void
	 */
	protected function downloadWithPhp($strUrl)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $strUrl);
		file_put_contents('download', curl_exec($ch));
		curl_close($ch);

		$zip = new ZipArchive;
		$zip->open('download');
		$zip->extractTo($this->strExtractTo);
		$zip->close();

		unlink('download');
	}


	/**
	 * Download the contao package with the server shell
	 *
	 * @param	string	$strUrl
	 * @return	void
	 */
	protected function downloadWithShell($strUrl)
	{
		// build the download command witch wget
		if ($this->strShellFetchCommand == 'wget')
		{
			$strCommand = 'wget ' . $strUrl;
		}

		// vuild the download command witch curl
		if ($this->strShellFetchCommand == 'curl')
		{
			$strCommand = 'curl -s -L ' . $strUrl . ' > download';
		}

		// download, unzip and cleanup
		$this->executeShellCommand($strCommand);
		$this->executeShellCommand($this->strShellUnzipCommand . ' download');
		$this->executeShellCommand('rm download');
	}


	/**
	 * Execute the given command on the system shell
	 *
	 * @param	string	$strCommand		The command witch should be executed
	 * @return	string					The output from the shell command
	 */
	protected function executeShellCommand($strCommand)
	{
		$strFunction = $this->strShellFunction;
		return $strFunction($strCommand);
	}


	/**
	 * Check witch method should be used to run commands on the system shell
	 *
	 * @param	void
	 * @return	void
	 */
	private function checkShellCommands()
	{
		// check if shell_exec is available
		if (function_exists('shell_exec'))
		{
			$this->strShellFunction = 'shell_exec';
			return;
		}

		// check if exec is available
		if (function_exists('exec'))
		{
			$this->strShellFunction = 'exec';
			return;
		}
	}


	/**
	 * Check witch tools are available on the system shell
	 *
	 * @param	void
	 * @return	void
	 */
	private function checkShellTools()
	{
		// check if wget is available
		if ($this->executeShellCommand('which wget') != '')
		{
			$this->strShellFetchCommand = 'wget';
		}

		// check if curl is available and override wget, because curl is better
		if ($this->executeShellCommand('which curl') != '')
		{
			$this->strShellFetchCommand = 'curl';
		}


		// check if unzip is available
		if ($this->executeShellCommand('which unzip') != '')
		{
			$this->strShellUnzipCommand = 'unzip';
		}

		//TODO: add support for "tar -xf"
	}


	/**
	 * Check if all needed PHP functions are available
	 *
	 * @param	void
	 * @return	void
	 */
	private function checkPhpExtensions()
	{
		// check if curl and zip are available
		if (extension_loaded('curl') === true && extension_loaded('zip') === true)
		{
			// extensions are available, now try the writing to the disk
			if (file_put_contents('write-test', 'Chuck Norris can always write to the disk, can your PHP to?') != false)
			{
				$this->blnUsePHPOverShell = true;
				@unlink('write-test');
			}
		}
	}
}


$objInstall = new Install();
$objInstall->init();


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
    <?php if ($objInstall->installPossible()): ?>
      <p class="confirm"><?php printf(_('Will using the fastest method to download and install contao on your server'), $exec) ?></p>
    <?php else: ?>
      <p class="error"><?php echo _('The automatic installation is not possible on your server.') ?></p>
      <p class="explain"><?php echo _('Your PHP installation does not meet the requirements to use the command line, does not have enough permissions to create files and folders or does not have the required PHP extensions "cURL" and "Zip".') ?></p>
    <?php endif; ?>
  </div>
  <div class="row">
    <?php if ($objInstall->installPossible() === false): ?>
      <h2><?php echo _('Manual installation') ?></h2>
      <ul>
        <li><?php printf('Go to %s and download the latest Contao version.', '<a href="http://sourceforge.net/projects/contao/files/">sourceforge.net</a>') ?></li>
        <li><?php echo _('Extract the download archive and upload the files to your server using an (S)FTP client.') ?></li>
        <li><?php echo _('Open the Contao install tool by adding "/contao" to the URL of your installation.') ?></li>
      </ul>
    <?php elseif (!isset($_GET['download'])): ?>
    	<form action="install.php" method="get">
    		<input type="text" name="path" value="." />
    		<select name="version">

    			<?php

				foreach ($objInstall->getTags() as $k=>$v)
				{
					echo '<option value="' . $k . '">' . $k . '</option>';
				}

				?>

    		</select>
    		<input type="hidden" name="download" value="do" />
    		<input class="btn" type="submit" title="<?php echo _('Start the installation') ?>" value="<?php echo _('Start the installation') ?>" />
    	</form>
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