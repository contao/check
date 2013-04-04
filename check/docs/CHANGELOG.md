Contao Check Changelog
======================

Version 6.4 (2013-04-04)
------------------------

### New
Added the Contao 2.11.11 file hashes.


Version 6.3 (2013-03-21)
------------------------

### New
Added the Contao 2.11.10 and 3.0.6 file hashes.


Version 6.2 (2013-02-19)
------------------------

### New
Added the Contao 3.0.5 file hashes.


Version 6.1 (2013-02-14)
------------------------

### Fixed
Fix the version 3.0.4 file hashes (see #42).


Version 6.0 (2013-02-14)
------------------------

### New
Added the Contao 3.0.4 file hashes.


Version 5.5 (2013-02-06)
------------------------

### New
Added the Contao 2.11.9 file hashes.


Version 5.4 (2013-01-12)
------------------------

### Changed
Removed the "md5 without comments" check.


Version 5.3 (2013-01-08)
------------------------

### New
Added the Contao 2.11.8, 3.0.2 and 3.0.3 file hashes.


Version 5.2 (2012-12-20)
------------------------

### Fixed
Correctly move the Contao files to their destination in the installer (see #39).


Version 5.1 (2012-11-29)
------------------------

### New
Added the Contao 2.11.7 and 3.0.1 file hashes.


Version 5.0 (2012-11-19)
------------------------

### New
Refactored the application so the overview can show the test results (see #30).

### New
Added a connection check to the Live Update check (see #37).


Version 4.2 (2012-11-03)
------------------------

### Fixed
Ignore the `system/cron/cron.txt` file (see #36).

### Fixed
Replace `__DIR__` with `dirname(__FILE__)` for PHP 5.2 (see #34).


Version 4.1 (2012-11-01)
------------------------

### Fixed
Correctly check for the `assets/images/*` subfolders (see #4976).


Version 4.0 (2012-10-30)
------------------------

### Fixed
Show the validated Contao version (see #31).

### New
Added the Contao 3.0.0 file hashes.

### Fixed
Also check the file size of the downloaded archive (see #32).


Version 3.0 (2012-10-26)
------------------------

### Fixed
If a user accepts the `fr_FR` locale, also check for `fr` (see #29).

### New
Added the French, Italian, Russian, Swedish and Japanese translation.

### New
Added localization support via transifex.com.


Version 2.8 (2012-10-16)
------------------------

### Fixed
Also check `zend.multibyte` under PHP 5.4 (see #28).

### Fixed
Correctly check for `detect_unicode` based on the PHP version (see #28).


Version 2.7 (2012-10-15)
------------------------

### Fixed
Added the OpenSSL check to the Live Update requirements (see #4892).


Version 2.6 (2012-10-06)
------------------------

### Fixed
Use `zend.detect_unicode` instead of just `detect_unicode`.


Version 2.5 (2012-09-27)
------------------------

### Fixed
Correctly remove the wrapper folder during installation (see #23).


Version 2.4 (2012-09-26)
------------------------

### New
Added the Contao 2.11.6 file hashes.


Version 2.3 (2012-09-16)
------------------------

### Fixed
Fixed the `wget` file download (see #21).

### Fixed
Also move hidden files when installing Contao (see #22).

### Fixed
Better web installer requirements check (see #15 and #18).

### Fixed
Check the Contao 2 and 3 PHP requirements separately (see #16).

### Fixed
Correctly determin the ionCube Loader version (see #17).


Version 2.2 (2012-09-04)
------------------------

### Fixed
Make the script work even if the PHP gettext extension is missing (see #13).

### Fixed
Only FastCGI AND eAccelerator is a problem (see #14).


Version 2.1 (2012-09-03)
------------------------

### Changed
Changed the appearance to match the Contao back end (see #10).


Version 2.0 (2012-09-02)
------------------------

### Changed
Rewrote the PHP code based on #8.

### Fixed
Set `TL_ROOT` so the `constants.php` file can be included in Contao 2 (see #3).


Version 1.0 (2012-08-21)
------------------------

### New
Initial commit.