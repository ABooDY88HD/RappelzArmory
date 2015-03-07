Project name: Rappelz Armory
Description: Armory for Rappelz privat server
Link: http://freakzlike.github.io/RappelzArmory/
Author: Freakzlike

Version: 1.0

-----------------------------------------------------------------------------------------------
Information:

First of all, this is an Open Source Project. If you have fixed any issues, developed some new 
functions or created a new design then please share them on https://github.com/freakzlike/RappelzArmory
to keep this project up. 

I developed this based on a Rappelz Epic 6 database with MS SQL. It could be possible that some things
have to be changed for other game versions. The game database has not been tested with MySQL yet. 
Of course you have to change some queries in the queries folder, because of the different sql syntax. 
I will add them if someone or myself tested this with an MySQL database.

-----------------------------------------------------------------------------------------------
Installation:

- Copy src folder to your web server directory
- Add your icon subset from game client to images/gameicons
- Edit files in settings folder
- Edit index.php file (make sure you switch to public mode)
- Execute webdb_install.sql on your web database

 -> Currently the web database is only required for logging.
    If you don't want to use it, then you have to remove it from function 'write_errorlog()' in
    file func/globalfunc.inc.php

 -> In case you want to use another language then copy file en.php in folder language and translate
    the phrases to your new language. Then change the include in index.php to your copied translation file.
    Item names or other texts will be determined from game database.

-----------------------------------------------------------------------------------------------
Advice

- For game database, use a database user which has only read access.
- Use another database on a different server for this armory and clone it from your game database on your
  game server with a specific interval to avoid security and performance issues on your game server.
- Protect all files/folders, instead of index.php, on your web server (Apache .htaccess)