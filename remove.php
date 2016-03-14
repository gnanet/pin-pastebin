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

  require_once 'config.php';

  function removePaste($pasteID) {
    global $Config;
    $errOld = error_reporting(0);
    $return = 1;

    $resultDir = $_SERVER['DOCUMENT_ROOT'] . $Config['site_path'] ."/". $Config['results_dir'];

    $filenameHTML = "$resultDir/$pasteID.html";

    if (file_exists($filenameHTML)) {

      if (unlink($filenameHTML)) {


      } else {
        print "Failed to remove $filenameHTML<br/>\n";
        $return = 0;
      }
    }
    error_reporting($errOld);
    return $return;
  }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
<title>PIN Remove a paste</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="no-cache">
</head>
<body>
<?php
//  print "<title>Admin - Remove a paste</title>\n";

  $ipAddr = $_SERVER["REMOTE_ADDR"];
  if (isset($_REQUEST["p"]) && isset($_REQUEST["t"])) {
    $pasteID = $_REQUEST["p"];
    $token = $_REQUEST["t"];
    $tokenMatch = sha1($pasteID . $ipAddr . $Config['token_salt']);
    if ($token == $tokenMatch) {
      $result = removePaste($pasteID);
      if ($result) {

        $query = sprintf('DELETE FROM pin where url like "%%%s%%"',$pasteID);

        if ( !db()->query($query) ) {
            echo "Failed: ".$query." <br>(" . db()->errno . ") " . db()->error."\n";
            exit;
            }
        print "Your paste has been removed. <a href=\"./\">back to PIN</a>";
      } else {
        print "Sorry, we encountered a problem trying to remove this paste.";
      }
    } else {
      print "Sorry, you are not authorized to remove this paste.";
    }
  }


?>
</body>
<html>