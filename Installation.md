Welcome to the Generator! This page will walk you through the basic steps required to install and configure the Generator.

IMPORTANT SAFETY TIP: The current installation and configuration process requires knowledge of PHP, MySQL (including executing SQL scripts) and hosting site administration. If the steps and terminology in the steps below aren’t familiar to you, you may wish to recruit a knowledgeable PHP/MySQL developer and/or wait until the Generator is more mature (a more wizard-like installation process is in the works).

## Installation Steps ##
  1. Download the source code to a local directory and unzip if necessary.
  1. On your hosting site, create a new blank MySQL database and a user with read, write and delete permissions to the database. This is the user that the PHP scripts will use to connect to the MySQL database. For security reasons, avoid giving the user any more permissions than necessary.
  1. Open the includes/config.php script in a text editor and fill in the appropriate settings based on your hosting provider and the database and user you just created. See the inline comments for more information.
  1. Upload the Character Generator files to an appropriate location on your server (e.g. http://mylarp.server.com/cg).
> > NOTE: It is not necessary to copy the install or PSDs directories to the server.
  1. Use a database administration utility (e.g. phpMySQLadmin) to log into the new database using the same database user you specified in the config file.
  1. Execute the install/cg\_setup.sql script (either execute the script file directly, or copy and paste the contents into a SQL window and run it). The script will create the database structure and populate basic settings.
  1. In a browser, go to the location of your new generator (e.g. http://mylarp.server.com/cg) and log in using the following default user name and password:
> > user: admin@larpcharactergenerator.com
> > password: chocolate

## Important Security Steps ##
  1. Go to the admin area and create a new admin user using your own email address and password.
  1. Log out, log back in using your new admin user, and delete the default system administrator user. Because the default credentials are available on this site, leaving the admin user in place creates a security hole. If for some reason you don’t want to delete the default administrator user, change the password to something secure.
  1. If you uploaded the “install” directory to your hosting server, log in and delete it.
  1. In the admin area, go to the Settings page and customize the settings for your game.

That’s it! Enjoy!