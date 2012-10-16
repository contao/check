Contao Check Changelog
======================

Version 3.0 (2012-XX-XX)
------------------------

### New
Added localization support via transifex.com.


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