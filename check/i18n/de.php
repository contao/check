<?php

/*
 * This file is part of the Contao Check.
 *
 * (c) Fritz Michael Gschwantner
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

return array(
	'Composer package manager' => 'Composer-Paketverwaltung',
	'PHP %s or greater' => 'PHP %s oder größer',
	'You have PHP version %s.' => 'Sie haben die PHP-Version %s.',
	'Composer requires at least PHP version %s.' => 'Composer benötigt mindestens PHP %s.',
	'PHP Phar extension' => 'PHP Phar-Erweiterung',
	'The PHP Phar extension is enabled.' => 'Die PHP Phar-Erweiterung ist aktiv.',
	'The PHP Phar extension is not enabled.' => 'Die PHP Phar-Erweiterung ist nicht aktiv.',
	'The PHP Phar extension is part of the PHP core since PHP 5.3 and has to be explicitly disabled using the --disable-phar flag. Recompile PHP without the flag.' => 'Die PHP Phar-Erweiterung ist seit PHP 5.3 Teil des PHP-Kerns und muss explizit mit dem Flag --disable--phar deaktiviert werden. Kompilieren Sie PHP erneut ohne das Flag.',
	'PHP cURL extension' => 'PHP cURL-Erweiterung',
	'The PHP cURL extension is available.' => 'Die PHP cURL-Erweiterung ist verfügbar.',
	'The PHP cURL extension is not available.' => 'Die PHP cURL-Erweiterung ist nicht verfügbar.',
	'The PHP cURL extension is required to communicate with the package server. You can enable it by compiling PHP with the --enable-curl flag.' => 'Die PHP cURL-Erweiterung wird für die Kommunikation mit dem Paketserver benötigt. Sie können sie bei der Kompilierung von PHP mit dem Flag --enable-curl aktivieren.',
	'PHP APC extension' => 'PHP APC-Erweiterung',
	'The PHP APC extension is not installed.' => 'Die PHP APC-Erweiterung ist nicht installiert.',
	'The PHP APC extension is installed.' => 'Die PHP APC-Erweiterung ist installiert.',
	'Using the PHP APC extension with Composer can lead to unexpected "cannot redeclare class" errors.' => 'Die Verwendung der PHP APC-Erweiterung mit Composer kann zu unerwarteten "cannot redeclare class"-Fehlern führen.',
	'Suhosin extension' => 'Suhosin-Erweiterung',
	'The Suhosin extension is not installed or correctly configured for .phar files.' => 'Die Suhosin-Erweiterung ist nicht installiert oder korrekt für die Nutzung von .phar-Dateien konfiguriert.',
	'The Suhosin extension does not allow to run .phar files.' => 'Die Suhosin-Erweiterung verhindert die Ausführung von .phar-Dateien.',
	'You have to add "phar" to the list of allowed streams in your php.ini: <code>suhosin.executor.include.whitelist = phar</code>' => 'Sie müssen "phar" in Ihrer php.ini zur Liste der erlaubten Streams hinzufügen: <code>suhosin.executor.include.whitelist = phar</code>',
	'allow_url_fopen' => 'allow_url_fopen',
	'The PHP flag "allow_url_fopen" is set.' => 'Das PHP-Flag "allow_url_fopen" ist gesetzt.',
	'The PHP flag "allow_url_fopen" is not set.' => 'Das PHP-Flag "allow_url_fopen" ist nicht gesetzt.',
	'To download the installation files, Composer requires the "allow_url_fopen" flag to be set in the php.ini.' => 'Das PHP-Flag "allow_url_fopen" muss in der php.ini gesetzt sein, damit Composer die Installationsdateien herunterladen kann.',
	'File permissions' => 'Dateirechte',
	'The "posix_getpwuid" function is not available.' => 'Die PHP-Funktion "posix_getpwuid" ist nicht verfügbar.',
	'File permissions cannot be tested, because the <code>posix_getpwuid</code> function has been disabled. Please contact your server administrator.' => 'Die Dateirechte können nicht überprüft werden, weil die Funktion <code>posix_getpwuid</code> deaktiviert wurde. Bitte wenden Sie sich an Ihren Server-Administrator.',
	'The PHP process is allowed to create files.' => 'Der PHP-Prozess darf Dateien erstellen.',
	'The PHP process is not allowed to create files.' => 'Der PHP-Prozess darf keine Dateien erstellen.',
	'The PHP process is not allowed to create or manipulate files. Please adjust your server configuration accordingly.' => 'Der PHP-Prozess darf keine Dateien erstellen oder verändern. Bitte korrigieren Sie die Serverkonfiguration entsprechend.',
	'shell_exec (optional)' => 'shell_exec (optional)',
	'The PHP function "shell_exec" is available.' => 'Die PHP-Funktion "shell_exec" ist verfügbar.',
	'The PHP function "shell_exec" is not available.' => 'Die PHP-Funktion "shell_exec" ist nicht verfügbar.',
	'The "shell_exec" function is optionally required to run the Composer process in the background.' => 'Die Funktion "shell_exec" wird optional benötigt, um den Composer-Prozess im Hintergrund laufen zu lassen.',
	'proc_open (optional)' => 'proc_open (optional)',
	'The PHP function "proc_open" is available.' => 'Die PHP-Funktion "proc_open" ist verfügbar.',
	'The PHP function "proc_open" is not available.' => 'Die PHP-Funktion "proc_open" ist nicht verfügbar.',
	'The "proc_open" function is optionally required to run the package manager in dev mode.' => 'Die Funktion "proc_open" wird optional benötigt, um die Paketverwaltung im Entwicklermodus zu betreiben.',
	'You can use the Composer package manager on this server.' => 'Sie können die Composer-Paketverwaltung auf diesem Server verwenden.',
	'You cannot use the Composer package manager on this server.' => 'Sie können die Composer-Paketverwaltung auf diesem Server nicht verwenden.',
	'Go back' => 'Zurück',
	'php.ini settings' => 'php.ini-Einstellungen',
	'The PHP safe_mode is not enabled.' => 'Der PHP safe_mode ist nicht aktiv.',
	'The PHP safe_mode is enabled.' => 'Der PHP safe_mode ist aktiv.',
	'It is recommended to disable the safe_mode in your php.ini or server control panel, otherwise the PHP process it not allowed to create or manipulate files and Contao cannot work properly.' => 'Es wird empfohlen, den safe_mode in Ihrer php.ini oder Ihrem Server-Control-Panel zu deaktivieren, da der PHP-Prozess andernfalls keine Dateien erstellen oder verändern darf und Contao nicht richtig funktionieren kann.',
	'Creating a test folder' => 'Erstelle einen Testordner',
	'The test folder could be created (owner: %s, chmod: %s).' => 'Der Testordner wurde erstellt (Besitzer: %s, Zugriffsrechte: %s).',
	'The test folder could not be created.' => 'Der Testordner konnte nicht erstellt werden.',
	'It seems that the PHP process does not have enough permissions to create folders on your server.' => 'Der PHP-Prozess hat scheinbar nicht genug Rechte, um Ordner auf Ihrem Server zu erstellen.',
	'The test folder does not have the correct owner or chmod settings.' => 'Der Testordner hat nicht den richtigen Besitzer und die richtigen Zugriffsrechte.',
	'The test folder is owned by %s (should be %s) and has the chmod settings %s (should be %s).' => 'Der Testordner gehört %s (sollte %s gehören) und hat die Zugriffsrechte %s (sollte %s sein).',
	'775, 755, 770, 750 or 705' => '775, 755, 770, 750 oder 705',
	'Creating a test file' => 'Erstelle eine Testdatei',
	'The test file could be created (owner: %s, chmod: %s).' => 'Die Testdatei wurde erstellt (Besitzer: %s, Zugriffsrechte: %s).',
	'The test file could not be created.' => 'Die Testdatei konnte nicht erstellt werden.',
	'It seems that the PHP process does not have enough permissions to create files on your server.' => 'Der PHP-Prozess hat scheinbar nicht genug Rechte, um Dateien auf Ihrem Server zu erstellen.',
	'The test file does not have the correct owner or chmod settings.' => 'Die Testdatei hat nicht den richtigen Besitzer und die richtigen Zugriffsrechte.',
	'The test file is owned by %s (should be %s) and has the chmod settings %s (should be %s).' => 'Die Testdatei gehört %s (sollte %s gehören) und hat die Zugriffsrechte %s (sollte %s sein).',
	'664, 644, 660, 640 or 604' => '664, 644, 660, 640 oder 604',
	'The PHP process is allowed to create files on this server.' => 'Der PHP-Prozess darf Dateien auf Ihrem Server erstellen.',
	'The PHP process is not allowed to create files on this server.' => 'Der PHP-Prozess darf keine Dateien auf Ihrem Server erstellen.',
	'Overview' => 'Übersicht',
	'You can install Contao 2.x' => 'Sie können Contao 2.x installieren',
	'You cannot install Contao 2.x' => 'Sie können Contao 2.x nicht installieren',
	'You can install Contao 3.x' => 'Sie können Contao 3.x installieren',
	'You cannot install Contao 3.x' => 'Sie können Contao 3.x nicht installieren',
	'You can install Contao 4.x' => 'Sie können Contao 4.x installieren',
	'You cannot install Contao 4.x' => 'Sie können Contao 4.x nicht installieren',
	'You can install Contao 4.5' => 'Sie können Contao 4.5 installieren',
	'You cannot install Contao 4.5' => 'Sie können Contao 4.5 nicht installieren',
	'The file permissions cannot be checked.' => 'Die Dateirechte können nicht überprüft werden.',
	'More information …' => 'Weitere Informationen …',
	'Additional services' => 'Zusätzliche Dienste',
	'You can use the Live Update.' => 'Sie können das Live Update verwenden.',
	'You cannot use the Live Update.' => 'Sie können das Live Update nicht verwenden.',
	'The Composer package manager requirements cannot be checked.' => 'Die Systemvoraussetzungen der Composer-Paketverwaltung können nicht überprüft werden.',
	'You can use the Composer package manager.' => 'Sie können die Composer-Paketverwaltung verwenden.',
	'You cannot use the Composer package manager.' => 'Sie können die Composer-Paketverwaltung nicht verwenden.',
	'You can use the Extension Repository.' => 'Sie können das Extension Repository verwenden.',
	'You cannot use the Extension Repository.' => 'Sie können das Extension Repository nicht verwenden.',
	'Install Contao' => 'Contao installieren',
	'Validate an existing installation' => 'Eine bestehende Installation prüfen',
	'Installation' => 'Installation',
	'Web installer' => 'Web-Installer',
	'There is a Contao installation already. Are you looking for the %s?' => 'Contao ist bereits installiert. Suchen Sie das %s?',
	'The automatic installation is not possible on your server due to safe_mode or file permission restrictions.' => 'Die automatische Installation ist auf Ihrem Server aufgrund fehlender Dateirechte oder des safe_mode nicht möglich.',
	'The automatic installation is possible on your server.' => 'Die automatische Installation ist auf Ihrem Server möglich.',
	'The automatic installation is not possible on your server.' => 'Die automatische Installation ist auf Ihrem Server nicht möglich.',
	'Your PHP installation either cannot connect to download.contao.org or is missing the PHP extension cURL or Zip.' => 'Ihre PHP-Installation kann entweder keine Verbindung zu download.contao.org aufbauen oder es fehlt die PHP-Extension cURL oder Zip.',
	'Manual installation' => 'Manuelle Installation',
	'Go to %s and download the latest Contao version.' => 'Gehen Sie zu %s und laden Sie die neueste Contao-Version herunter.',
	'Extract the download archive and upload the files to your server using an (S)FTP client.' => 'Entpacken Sie das Download-Archiv und übertragen Sie die Dateien mit einem (S)FTP-Programm auf Ihren Server.',
	'Open the Contao install tool by adding "/contao/install.php" to the URL of your installation.' => 'Öffnen Sie das Contao-Installtool, indem Sie "/contao/install.php" der URL Ihrer Installation anhängen.',
	'Target version' => 'Zielversion',
	'Start the installation' => 'Installation starten',
	'Installation failed' => 'Installation fehlgeschlagen',
	'Installation complete' => 'Installation abgeschlossen',
	'Contao %s has been installed in %s.' => 'Contao %s wurde im Ordner %s installiert.',
	'Open the Contao install tool' => 'Zum Contao-Installtool',
	'Live Update' => 'Live Update',
	'Phar has been added to the PHP core in version 5.3, so you require at least PHP version %s to use .phar files. If you have PHP 5.2, you might be able to use .phar files by installing the PECL phar extension (see %s).' => 'Phar ist seit Version 5.3 Teil des PHP-Kerns, daher benötigen Sie mindestens PHP %s, um .phar-Dateien verwenden zu können. Wenn Sie noch PHP 5.2 einsetzen, können Sie die Unterstützung von .phar-Dateien eventuell mit Hilfe der PECL Phar-Erweiterung nachrüsten (siehe %s).',
	'PHP OpenSSL extension' => 'PHP OpenSSL-Erweiterung',
	'The PHP OpenSSL extension is enabled.' => 'Die PHP OpenSSL-Erweiterung ist aktiv.',
	'The PHP OpenSSL extension is not enabled.' => 'Die PHP OpenSSL-Erweiterung ist nicht aktiv.',
	'The PHP OpenSSL extension is required to set up a secure connection to the Live Update server. Enable it in your php.ini.' => 'Die PHP OpenSSL-Erweiterung wird benötigt, um eine sichere Verbindung zum Live Update-Server herzustellen. Aktivieren Sie sie in Ihrer php.ini.',
	'The ionCube Loader is not enabled or at least at version 4.0.9.' => 'Der ionCube-Loader ist nicht aktiv oder liegt mindestens in der Version 4.0.9 vor.',
	'An old version of the ionCube Loader prior to version 4.0.9 is installed.' => 'Eine alte Version des ionCube Loader kleiner der Version 4.0.9 ist installiert.',
	'Before version 4.0.9, the ionCube Loader was incompatible with Phar archives. Either upgrade to the latest version or disable the module. More information is available here: %s' => 'Vor Version 4.0.9 konnte der ionCube Loader keine Phar-Archive verarbeiten. Installieren Sie entweder die aktuelle Version oder deaktivieren Sie das Modul. Weitere Informationen finden Sie hier: %s',
	'Detect Unicode' => 'Detect Unicode',
	'The --enable-zend-multibyte flag is not set or detect_unicode is disabled.' => 'Das Flag --enable-zend-multibyte ist nicht gesetzt oder detect_unicode ist deaktiviert.',
	'The detect_unicode settings on your server are not correct.' => 'Die detect_unicode-Einstellungen auf Ihrem Server sind nicht korrekt.',
	'If PHP is compiled with the --enable-zend-multibyte flag, you have to explicitly disable detect_unicode in your php.ini: <code>detect_unicode = Off</code>. This is a PHP bug which might be fixed in the future. More information is available here: %s' => 'Wird PHP mit dem Flag --enable-zend-multibyte kompiliert, müssen Sie detect_unicode explizit in Ihrer php.ini deaktivieren: <code>detect_unicode = Off</code>. Dies ist ein PHP-Bug, der in zukünftigen Versionen eventuell behoben wird. Weitere Informationen dazu finden Sie hier: %s',
	'You are not using FastCGI and eAccelerator.' => 'Sie verwenden kein FastCGI mit dem eAccelerator.',
	'You are using FastCGI and eAccelerator.' => 'Sie verwenden FastCGI mit dem eAccelerator.',
	'It seems that FastCGI in combination with the eAccelerator extension is buggy when it comes to Phar archives. You can either disable the eAccelerator extension or use a different PHP cache instead (e.g. FastCGI+APC).' => 'FastCGI in Kombination mit dem eAccelerator kann Phar-Archive offenbar nicht korrekt verarbeiten. Sie können den eAccelerator entweder deaktivieren oder durch einen anderen PHP-Cache (z.B. FastCGI-APC) ersetzen.',
	'Connection test' => 'Verbindungstest',
	'A connection to update.contao.org could be established.' => 'Die Verbindung zu update.contao.org konnte hergestellt werden.',
	'A connection to update.contao.org could not be established.' => 'Die Verbindung zu update.contao.org konnte nicht hergestellt werden.',
	'Maybe the request has been blocked by a firewall or your OpenSSL version is too old. Please contact your server administrator.' => 'Eventuell wurde die Anfrage von einer Firewall blockiert oder die OpenSSL Version ist zu alt. Bitte kontaktieren Sie Ihren Serveradministrator.',
	'You can use the Live Update on this server.' => 'Sie können das Live Update auf diesem Server verwenden.',
	'You cannot use the Live Update on this server.' => 'Sie können das Live Update auf diesem Server nicht verwenden.',
	'Extension Repository' => 'Extension Repository',
	'PHP SOAP extension' => 'PHP SOAP-Erweiterung',
	'The PHP SOAP extension is available.' => 'Die PHP SOAP-Erweiterung ist verfügbar.',
	'The PHP SOAP extension is not available.' => 'Die PHP SOAP-Erweiterung ist nicht verfügbar.',
	'The PHP SOAP extension is required to communicate with the Extension Repository server. You can enable it by compiling PHP with the --enable-soap flag. Depending on your Contao version, you might also be able to use the %s extension.' => 'Die PHP SOAP-Erweiterung wird für die Kommunikation mit dem Extension Repository benötigt. Sie können sie bei der Kompilierung von PHP mit dem Flag --enable-soap aktivieren. Je nach Contao-Version können Sie eventuell auch die %s-Erweiterung verwenden.',
	'A connection to contao.org could be established.' => 'Die Verbindung zu contao.org konnte hergestellt werden.',
	'A connection to contao.org could not be established.' => 'Die Verbindung zu contao.org konnte nicht hergestellt werden.',
	'You can use the Extension Repository on this server.' => 'Sie können das Extension Repository auf diesem Server verwenden.',
	'You cannot use the Extension Repository on this server.' => 'Sie können das Extension Repository auf diesem Server nicht verwenden.',
	'Validate an installation' => 'Eine Installation prüfen',
	'Could not find a Contao installation.' => 'Keine Contao-Installation gefunden.',
	'To validate an existing installation, please upload the "check" folder to your installation directory.' => 'Um eine bestehende Installation zu prüfen, laden Sie bitte den Ordner "check" in Ihr Installationsverzeichnis.',
	'Unknown version' => 'Unbekannte Version',
	'The installed version %s is not (yet) supported.' => 'Die installierte Version %s wird (noch) nicht unterstützt.',
	'There is no version file for your Contao installation. Are you using a stable Contao version and do you have the latest version of the Contao Check?' => 'Es gibt keine Versionsdatei für Ihre Contao-Installation. Verwenden Sie eine stabile Contao-Version und haben Sie die neueste Version des Contao-Check?',
	'Version' => 'Version',
	'Found a Contao %s installation.' => 'Eine Contao %s-Installation wurde gefunden.',
	'Missing files' => 'Fehlende Dateien',
	'Corrupt files' => 'Beschädigte Dateien',
	'Missing optional files' => 'Fehlende optionale Dateien',
	'Your installation is up to date.' => 'Ihre Installation ist aktuell.',
	'Your installation is not up to date.' => 'Ihre Installation ist nicht aktuell.',
	'Contao 2.x' => 'Contao 2.x',
	'You need at least PHP %s.' => 'Sie benötigen mindestens PHP %s.',
	'PHP 7.x' => 'PHP 7.x',
	'The PHP version is lower than 7.0.0.' => 'Die PHP Version ist niedriger als 7.0.0.',
	'Contao 2.x is incompatible with PHP 7.' => 'Contao 2.x ist inkompatibel mit PHP 7.',
	'PHP GDlib extension' => 'PHP GDlib-Erweiterung',
	'The PHP GDlib extension is enabled.' => 'Die PHP GDlib-Erweiterung ist aktiv.',
	'The PHP GDlib extension is not enabled.' => 'Die PHP GDlib-Erweiterung ist nicht aktiv.',
	'The PHP GDlib extension is not part of the PHP core and has to be explicitly enabled using the --with-gd flag. Recompile PHP with the flag.' => 'Die PHP GDlib-Erweiterung ist nicht Teil des PHP-Kerns und muss mit dem Flag --with-gd explizit aktiviert werden. Kompilieren Sie PHP erneut mit diesem Flag.',
	'PHP DOM extension' => 'PHP DOM-Erweiterung',
	'The PHP DOM extension is enabled.' =>  'Die PHP DOM-Erweiterung ist aktiv.',
	'The PHP DOM extension is not enabled.' => 'Die PHP DOM-Erweiterung ist nicht aktiv.',
	'The PHP DOM extension is part of the PHP core and has to be explicitly disabled using the --disable-dom flag. Recompile PHP without the flag.' => 'Die PHP DOM-Erweiterung ist Teil des PHP-Kerns und muss explizit mit dem Flag --disable-dom deaktiviert werden. Kompilieren Sie PHP erneut ohne das Flag.',
	'You can install Contao 2.x on this server.' => 'Sie können Contao 2.x auf diesem Server installieren.',
	'You cannot install Contao 2.x on this server.' => 'Sie können Contao 2.x auf diesem Server nicht installieren.',
	'Contao 3.x' => 'Contao 3.x',
	'As of Contao 3.4 you need at least PHP %s.' => 'Seit Contao 3.4 wird mindestens PHP %s benötigt.',
	'You can install Contao 3.x on this server.' => 'Sie können Contao 3.x auf diesem Server installieren.',
	'You cannot install Contao 3.x on this server.' => 'Sie können Contao 3.x auf diesem Server nicht installieren.',
	'Contao 4.x' => 'Contao 4.x',
	'Contao 4.5' => 'Contao 4.5',
	'As of Contao 4.5 you need at least PHP %s.' => 'Seit Contao 4.5 wird mindestens PHP %s benötigt.',
	'PHP intl extension' => 'PHP intl-Erweiterung',
	'The PHP intl extension is enabled.' => 'Die PHP intl-Erweiterung ist aktiv.',
	'The PHP intl extension is not enabled.' => 'Die PHP intl-Erweiterung ist nicht aktiv.',
	'The PHP intl extension is bundled with the PHP core since PHP 5.3 and has to be explicitly enabled using the --enable-intl flag. Recompile PHP with the flag.' => 'Die PHP Phar-Erweiterung ist seit PHP 5.3 mit dem PHP-Kern gebündelt und muss explizit mit dem Flag --enable-intl aktiviert werden. Kompilieren Sie PHP erneut mit diesem Flag.',
	'The PHP GDlib extension is not enabled.' => 'Die PHP GDlib-Erweiterung ist nicht aktiv.',
	'The PHP GDlib extension is not part of the PHP core and has to be explicitly enabled using the --with-gd flag. Recompile PHP with the flag.' => 'Die PHP GDlib-Erweiterung ist nicht Teil des PHP-Kerns und muss mit dem Flag --with-gd explizit aktiviert werden. Kompilieren Sie PHP erneut mit diesem Flag.',
	'You can install Contao 4.x on this server.' => 'Sie können Contao 4.x auf diesem Server installieren.',
	'You cannot install Contao 4.x on this server.' => 'Sie können Contao 4.x auf diesem Server nicht installieren.',
	'You can install Contao 4.5 on this server.' => 'Sie können Contao 4.5 auf diesem Server installieren.',
	'You cannot install Contao 4.5 on this server.' => 'Sie können Contao 4.5 auf diesem Server nicht installieren.',
	'PHP image processing' => 'PHP Bildverarbeitung',
	'At least one of the supported image processing libraries is available.' => 'Zumindest eine der unterstützten Bildverarbeitungs-Bibliotheken ist verfügbar.',
	'None of the supported image processing libraries are available.' => 'Keine der unterstützten Bildverarbeitungs-Bibliotheken ist verfügbar.',
	'As of Contao 4.3 one of the supported image processing libraries must be available (GD, Imagick or Gmagick).' => 'Seit Contao 4.3 muss mindestens eine der unterstützten Bildverarbeitungs-Bibliotheken verfügbar sein (GD, Imagick oder Gmagick).',
	'An unknown error occurred while getting the newest LTS version.' => 'Ein unbekannter Fehler ist beim Ermitteln der neuesten LTS Version aufgetreten.',
	'Version file error' => 'Versions-Datei Fehler',
	'Error while retrieving version file: %s.' => 'Fehler beim Laden der Versions-Datei: %s.',
	'There was an error retrieving the version file from contao.org for your Contao version. You can download the file manually from %s and put it into the "versions" directory of the Contao Check.' => 'Ein Fehler ist beim Laden der Versions-Datei von contao.org für Ihre Contao Version aufgetreten. Sie können die Datei manuell von %s herunterladen und in das "versions" Verzeichnis des Contao Check kopieren.',
	'System temp directory' => 'System Temp Verzeichnis',
	'The system temp directory is writable.' => 'Das System Temp Verzeichnis ist beschreibbar.',
	'The system temp directory is not writable.' => 'Das System Temp Verzeichnis ist nich beschreibbar.',
	'Make sure the correct directory is configured via the TMP, TMPDIR or TEMP environment variable or the sys_temp_dir PHP variable.' => 'Stelle sicher, dass ein gültiges Verzeichnis über die Umgebungsvariablen TMP, TMPDIR oder TEMP oder über die sys_temp_dir PHP variable gesetzt ist.',
	'Symlinks' => 'Symlinks',
	'Symlinks can successfully be created.' => 'Symlinks können erfolgreich erzeugt werden.',
	'Symlinks could not be created.' => 'Symlinks konnten nicht erzeugt werden.',
	'Working symlinks are required to run Contao 4. Please contact your server administrator.' => 'Funktionierende symlinks sind für den Betrieb von Contao 4 erforderlich. Bitte kontaktieren Sie Ihren System Administrator.',
	'The PHP symlink() function is disabled.' => 'Die PHP-Funktion "symlink" ist nicht verfügbar.',
	'The PHP symlink() function is required to run Contao 4. Please contact your server administrator.' => 'Die PHP "symlink" Funktion ist für den Betrieb von Contao 4 erforderlich. Bitte kontaktieren Sie Ihren System Administrator.',
	'PHP XCache extension' => 'PHP XCache-Erweiterung',
	'The PHP XCache extension is not enabled.' => 'Die PHP XCache-Erweiterung ist nicht aktiv.',
	'The PHP XCache extension is enabled.' => 'Die PHP XCache-Erweiterung ist aktiv.',
	'The PHP XCache extension prevents the execution of .phar files. Disable this extension in your php.ini.' => 'Die PHP XCache-Erweiterung verhindert die Ausführung von .phar Dateien. Deaktivieren Sie diese Erweiterung in der php.ini.',
	'The ICU library version is %s.' => 'Die ICU-Bibliothek Version ist %s.',
	'The ICU library version on your system needs to be at least %s. Please contact your server administrator.' => 'Die Version der ICU-Bibliothek muss mindestens %s sein. Bitte kontaktieren Sie Ihren System Administrator.',
	'ICU library' => 'ICU-Bibliothek',
	'Contao system requirements' => 'Contao System Anforderungen',
	'The PHP xmlreader extension is enabled.' => 'Die PHP XMLReader-Erweiterung ist aktiv.',
	'The PHP xmlreader extension is not enabled.' => 'Die PHP XMLReader-Erweiterung ist nicht aktiv.',
	'The PHP xmlreader extension is bundled with the PHP core since PHP 5.1 and has to be explicitly disabled using the --disable-xmlreader flag. Recompile PHP without the flag.' => 'Die PHP XMLReader-Erweiterung ist seit PHP 5.1 Teil des PHP-Kerns und muss explizit mit dem Flag --disable--xmlreader deaktiviert werden. Kompilieren Sie PHP erneut ohne das Flag.',
	'File locks' => 'File Locks',
	'A file lock could be established.' => 'Ein "File Lock" konnte erzeugt werden.',
	'A file lock could not be established.' => 'Ein "File Lock" konnte nicht erzeugt werden.',
	'PHP was unable to get a file lock on an existing file. Please contact your server administrator.' => 'PHP konnte keinen "File Lock" auf bereits existierende Dateien erzeugen. Bitte kontaktieren Sie Ihren System Administrator.',
	'The installer requires the "allow_url_fopen" flag to be set in the php.ini.' => 'Das PHP-Flag "allow_url_fopen" muss in der php.ini gesetzt sein, damit die Installation funktioniert.',
);
