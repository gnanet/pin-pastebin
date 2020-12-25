<?php
/*
This software is licensed through a BSD-style License.
http://www.opensource.org/licenses/bsd-license.php

Copyright (c) 2002 - 2009 Jacob D. Cohen
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:

Redistributions of source code must retain the above copyright notice,
this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright
notice, this list of conditions and the following disclaimer in the
documentation and/or other materials provided with the distribution.
Neither the name of Jacob D. Cohen nor the names of his contributors
may be used to endorse or promote products derived from this software
without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

  $Config = array(
    # This is the domain where this pastebin is running
    'site_domain'   => 'https://your-domain-here.domain',

    # This is the part after the domain where this pastebin will be found.
    'site_path'     => '',

    # This is the directory where pastes will be stored
    'results_dir'   => 'results/',

    # This is the path of the URL that will be generated for sharing (allows
    # shorter URLs to be used if you've set something up on your web server
    # to support this.
    'short_results_path' => $Config['site_domain'].'/p/',

    # Change this to a unique value
    'token_salt'    => 'YOUR-SALT-HERE',

    # database
    'DB_HOST' => 'DATABASE-HOST',
    'DB_DB' => 'DATABASE-NAME',
    'DB_USER' => 'YOUR-DB-USER',
    'DB_PASS' => 'YOUR-DB-USER-PASSWORD',
  );

function cfg( $p  ) {
    global $Config;
    return $Config[$p];
}

function db() {
    static $db;

    if ( is_null($db)  ) {
        $db = new mysqli(cfg('DB_HOST'), cfg('DB_USER'), cfg('DB_PASS'), cfg('DB_DB'));
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error."\n";
            exit;
            }
        $db->set_charset("utf8mb4");
    }

    return $db;
}

function file_get_contents_curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

