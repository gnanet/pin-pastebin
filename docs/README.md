pin-pastebin
========

Files and Folders
-------------------------
* docs - this folder **contains the MySQL create script** for the table, that contains a catalog of all pastes, and this README too
* results - this folder is needed to hold the paste content as generated HTML files, and has to be writeable by the webserver
* paste/htaccess.txt - you may want to protect this folder if multiple users are using the pastebin
* paste/browser.php - listing of all pasted items, that were collected into the MySQL table referred above, allows also removing a paste without knowing the remove-hash
* paste/browser.ajax.php - DataTables server-side processing script.
* paste/ssp.class.php - Helper functions for building a DataTables server-side processing SQL query
* paste/pview.php - diplay paste content if it was stored in MySQL
* config.php - as the name says, you have to set basic configuration here
* paste-api.php - This script has to be referred to within **Yourls URL**
* paste.php - the reworked paste script
* remove.php - you can remove a paste if you know the right URL using this script
* htaccess.txt - required Rewrite settings to allow cleanURL access to the results in Apache
* nginx.conf.txt - required Rewrite settings to allow cleanURL access to the results in NGINX
* index.php - simple form to paste content
* url.php - simple form to paste URLs with clipboard integration, and auto try title-fetch

Fastest way to add URL
-------------------------
* Copy URL you want to add to the clipboard
* Visit PIN main URL/url.php
* Allow clipboard-access for the browser if not already done
* Wait for clipboard reading, and auto-title
* Submit


Android client apps tested or assumed to work with paste-api.php
-------------------------
* [Yourls Shortener](https://play.google.com/store/apps/details?id=cc.lupine.yourlsshortener)
* ~~[URLy](https://play.google.com/store/apps/details?id=com.mndroid.apps.urly)~~ Abandoned, has issues with https urls


TODO
-------------------------
* unify paste-api.php and paste.php
* put authentication for paste/browser.php into the script, configured by config.php


Based on the original code of [rafb-nopaste](https://code.google.com/archive/p/rafb-nopaste)
