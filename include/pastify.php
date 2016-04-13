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

require_once("highlight.php");

function PastifyText($text, $language, $desc)
{
    $plaintext = $text;

    $text = preg_replace("(&)", "&amp;", $text);
    $text = preg_replace("(<)", "&lt;", $text);
    $text = preg_replace("/^[ \\t\\r]*$/ms", "&nbsp;", $text);

    $lines = explode("\n", $text);
    $nlines = count($lines);
    $lineout = "";

    # Do the actual syntax highlighting
    $output = SyntaxHighlight($text, $language);

    for ($i = 1; $i < $nlines; ++$i)
        $lineout .= "$i\n";
    $lineout .= "$i";

    $html = <<<EOH
<!DOCTYPE html>
<html lang="hu">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noarchive"/>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html">
<meta http-equiv="Cache-Control" content="no-cache">
<link rel="stylesheet" type="text/css" href="../paste.css"/>
<!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="../highlight.css">
<title>Pasted code - $desc</title>
</head>
<body  style="padding-top: 70px;">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img style="float: left; margin-top: 7px;" src="../paste/pin-icon.png" width="32px" heigth="32px">
          <a class="navbar-brand" href="/">PIN Pastebin</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
<div style="border-bottom: 2px solid gray;">
<table border="0" cellpadding="1" cellspacing="2" width="100%">
<tr>
  <td><small>Pasted as <b>$language</b> <span id="controls">[ <a href="remove-paste-url">Remove this paste</a> ]</span></small></td>
  <td><small><span id="controls">[ <a href="/">Add new paste</a> ]</span></small></td>
</tr>
<tr>
  <td><small>Description: $desc</small></td>
  <td><small>URL: <a href="this-paste-url">this-paste-url</a></small></td>
</tr>
</table>
</div>

<table border="0" cellpadding="1" cellspacing="2">
<tr>
  <td><pre class="code">$lineout</pre></td>
  <td width="100%"><pre class="code" id="codemain">$output</pre></td>
</tr>
<tr>
<td>&nbsp;</td>
<td width="100%">
<textarea cols="120" rows="5">
$text
</textarea>
</td>
</tr>
</table>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>

EOH;

    return $html;
}

