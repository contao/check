Contao Check
============

With this script you can check the Contao Open Source CMS system requirements,
validated existing Contao installations or set up new Contao installations.


## What's included

Here is a short overview of what you can do with the script:

### Requirements

 * Can I use the Extension Repository?
 * Can I use the Live Update Service?
 * Do I need to use the Safe Mode Hack?

### Installation

 * Install the latest Contao version
 * Validate an existing Contao installation


## How to use

Just upload the "check" folder to your server where you want to install Contao.
To validate an existing installation, upload the "check" folder to the root of
your Contao installation. Then open it in a web browser.


## Web installer

The web installer will try to download and extract the latest Contao release by
either using the command line or the built-in PHP functions.

### Command line requirements

 * `shell_exec()` or `exec()`
 * `wget`
 * `unzip`

### PHP built-in requirements

 * cURL
 * Zip
 * File write permissions
