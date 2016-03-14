pin-pastebin
========

Files and Folders
-------------------------
* docs - **contains the MySQL create script for the table, that functions as a catalog of all pastes**
* results - this folder is needed to hold the paste content as generated HTML files, and has to be writeable by the webserver
* paste/htaccess.txt - you may want to protect this folder if multiple users are using the pastebin
* paste/browser.php - listing of all pasted items, that were collected into the MySQL table referred above, allows also removing a paste without knowing the remove-hash
* config.php - as the name says, you have to set basic configuration here
* paste-api.php - This script has to be referred to within **Yourls URL**
* paste.php - the original rafb-nopaste script
* remove.php - you can remove a paste if you know the right URL using this script
* htaccess.txt - required Rewrite settings to allow cleanURL access to the results
* index.html - simple form to paste content
* url.html - simple form to paste URLs

TODO
-------------------------
* unify paste-api.php and paste.php
* put authentication for paste/browser.php into the script, configured by config.php


Based on the original code of [rafb-nopaste](https://code.google.com/archive/p/rafb-nopaste)
