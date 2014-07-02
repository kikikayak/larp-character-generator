<?php

// defines database connection data and other settings that aren't stored in the DB. 

define('SALT_LENGTH', 9); // Do not change. Used for password hashing. 

date_default_timezone_set('America/New_York'); // Set default timezone
// See list of acceptable timezones here: http://www.php.net/manual/en/timezones.america.php

// Core settings. These are used when we don't have access to the DB settings
// (e.g. on the non-authenticated pages). 
define('CAMPAIGN_NAME', 'Lione');
define('CONTACT_NAME', 'Allison Corbett');
define('CONTACT_EMAIL', 'acorbett@willowtree.net');
define('WEBMASTER_EMAIL', 'albanister@comcast.net');
define('WEBMASTER_NAME', 'Allison Corbett');
define('COPYRIGHT_DATE', '2014');
define('GENERATOR_LOCATION', 'http://www.larpcharactergenerator.com/lione');
define('DEBUG', 'off'); // Values: 'off' or 'on'

// LOCAL
define('DB_HOST', 'localhost');
define('DB_USER', 'cgAdmin');
define('DB_PASSWORD', 'admin');
define('DB_DATABASE', 'charGen');
define('LOCATION', $_SERVER['DOCUMENT_ROOT'] . '/larp-character-generator/'); // Include leading and trailing slashes to build directory paths correctly

/*
// REMOTE
define('DB_HOST', 'peacock.lunarservers.com');
define('DB_USER', 'larpc0_charGen');
define('DB_PASSWORD', '266kC@QT5uyr');
define('DB_DATABASE', 'larpc0_endgame');
define('LOCATION', $_SERVER['DOCUMENT_ROOT'] . '/demo/'); // Include trailing slash to build directory paths correctly
// echo LOCATION;
*/

?>