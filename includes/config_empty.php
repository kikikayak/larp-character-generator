<?php

// defines database connection data and other settings that aren't stored in the DB. 

define('SALT_LENGTH', 9); // Do not change. Used for password hashing. 

date_default_timezone_set('America/New_York'); // Set default timezone
// See list of acceptable timezones here: http://www.php.net/manual/en/timezones.america.php

// Core settings. These are used when we don't have access to the DB settings
// (e.g. on the non-authenticated pages). 
// Correct format looks like this:
// define('CAMPAIGN_NAME', 'Radiant Clockwork'); 
define('CAMPAIGN_NAME', 'Game name here'); // 
define('CONTACT_NAME', 'Contact name here');
define('CONTACT_EMAIL', 'test@email.com');
define('WEBMASTER_EMAIL', 'test@email.com');
define('WEBMASTER_NAME', 'Webmaster name here');
define('COPYRIGHT_DATE', '2014');
define('GENERATOR_LOCATION', 'URL goes here'); // Complete URL, including http://
define('DEBUG', 'off'); // Values: 'off' or 'on.' Recommend leaving off. 

define('DB_HOST', 'localhost'); // Replace with server name at your hosting provider
define('DB_USER', 'root'); // Admin user for your new MySQL database
define('DB_PASSWORD', 'password'); // Password for MySQL admin user
define('DB_DATABASE', 'cg'); // MySQL database name

// Location of Character Generator, used mostly for links in emails. 
// Include trailing slash to build directory paths correctly
define('LOCATION', $_SERVER['DOCUMENT_ROOT'] . '/path/to/cg/'); 

?>