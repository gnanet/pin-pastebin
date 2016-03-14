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



function CreatePage($input)
{
    global $Config;
    $text = $input;

    $result_dir = $Config['results_dir'];

    $tmpfname = tempnam ("$result_dir/", "");

    # I can't remember why I tacked on 2 random digits.
    $outbase = $tmpfname . rand(10, 99);
    $outfile = $outbase . ".html";

    $ipAddr = $_SERVER["REMOTE_ADDR"];
    $pasteID = basename($outbase);

    $token = sha1($pasteID . $ipAddr . $Config['token_salt']);

    # There is probably a better way to do this. tempnam() creates the
    # temporary file in the results directory, but we then need to strip
    # this off the front of the filename to make a shorter url.
    $rawout = $outfile;

    $rawout = preg_replace('/$result_dir\//', "", $rawout);
    $rawout = basename($rawout);

    if ($Config['short_results_path']) {
      $urlbase = $Config['short_results_path'];
    $pasteUrl = $urlbase . preg_replace('/\.html$/',"",$rawout);
    } else {
      $urlbase = $Config['site_domain'] . $Config['site_path'] . '/' . $Config['results_dir'];
    $pasteUrl = $urlbase . $rawout;
    }


    $text = preg_replace('/this-paste-url/', $pasteUrl, $text);
    $text = preg_replace('/remove-paste-url/', $Config['site_domain'] . $Config['site_path'] . "/remove.php?p=$pasteID&t=$token", $text);

    $fp = fopen($outfile, "w");
    fwrite($fp, $text);
    fclose($fp);

    unlink($tmpfname);

    return $pasteUrl;
}

?>
