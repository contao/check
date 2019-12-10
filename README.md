Contao check
============

With this script you can check the Contao Open Source CMS system requirements,
validate an existing Contao installation or set up a new Contao installation.


## What's included

Here is a short overview of what you can do with the script:

 * Check which Contao versions can be installed;
 * check if PHP is allowed to write files on the server;
 * check if the Live Update service can be used;
 * check if the Composer package manager can be used;
 * check if the extension repository can be used;
 * install the latest Contao version or Contao LTS version;
 * validate an existing Contao installation.


## How to use

Upload the `/check` folder to your server where you want to install Contao.
To validate an existing installation, upload the `/check` folder to the root of
your Contao installation. Then open it in a web browser.

Be aware that validation of an existing installation is mainly designed for 
installations created by using this tool or officially released archives. Therefore, 
validation just checks the presence and contents of files against a known list of
those being part of an official release. This way this tool can help to check if 
everything needed by an official release has been successfully deployed.


## Web installer

The web installer requires the PHP extensions "curl" and "Zip" to be installed
in order to download and extract the latest Contao release.
