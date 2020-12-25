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


require_once 'include/pastify.php';
require_once 'include/createpage.php';

$refererurl = $_SERVER['HTTP_REFERER'];
$refererurl = preg_replace("/\/[^\/]*$/", "", $refererurl);
$thisurl = "https://" . $_SERVER['HTTP_HOST'];
$thisurl .= $_SERVER['SCRIPT_NAME'];
$thisurl = preg_replace("/\/[^\/]*$/", "", $thisurl);


// Translate incoming request from URLy to fake YOURLS server
if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'shorturl') ) {
	//error_log('Yourls debug get_defined_vars: '.print_r(get_defined_vars(), true));
	//error_log('Yourls debug _SERVER: '.print_r($_SERVER, true));
	$_POST['text'] = $_REQUEST['url'];
	$_POST['desc'] = 'URL '.$_REQUEST['url'];
	$_POST['lang'] = 'URL';
}

if (isset($_POST['text']) && "" != ($ttemp = rtrim($_POST['text'])))
{
    $url = "";
    $baseurl = $_SERVER['SCRIPT_NAME'];
    $baseurl = preg_replace("/\/[^\/]*$/", "", $baseurl);

    $text = $ttemp;

    # Figure out if the post specified a syntax highlighting language
    if (isset($_POST['lang']) && "" != ($ltemp = rtrim($_POST['lang'])))
    {
        $language = $ltemp;
        if (strlen($language) > 20)
            $language = substr($language, 0, 20);
        $language = stripslashes($language);
        $language = strip_tags($language);
        $language = htmlspecialchars($language, ENT_QUOTES);
    }
    else
    {
        $language = "Plain Text";
    }


    # Add a description if available
    if (isset($_POST['desc']) && "" != ($dtemp = rtrim($_POST['desc'])))
    {
        $desc = stripslashes($dtemp);
        if (strlen($desc) > 254)
        {
            $desc = substr($desc, 0, 250);
            $desc .= " ...";
        }
        $desc = strip_tags($desc);
        $desc = htmlspecialchars($desc, ENT_QUOTES);
        if ("" == $desc)
            $desc = "No description";
    }
    else
        $desc = "No description";


    if (get_magic_quotes_gpc())
        $text = stripslashes($text);

    $finalText = PastifyText($text, $language, $desc);

    list($url,$removeurl,$storedText) = CreatePage($finalText);

    # Note: this function was pretty specific to my implementation. It stored
    # paste metadata about the language used, description, and URL, as well as
    # a timestamp (but the raw pastes were never preserved - they always expired
    # as promised after 24 hours)
    #add_to_db($desc, $language, $url);

    $query = sprintf('INSERT INTO pin (url,description,rawtext,finaltext,lang,ts) VALUES ("%s","%s","%s","%s","%s",UNIX_TIMESTAMP(NOW()))',$url,$desc,db()->real_escape_string($text),db()->real_escape_string($storedText),$language);

    if (!db()->query($query) ) {
        echo "Failed: ".$query." <br>(" . db()->errno . ") " . db()->error."\n";
        exit;
            }


// Respond to URLy or any URL shortener app with a JSON that resembles a YOURLS servers response

	$return['url']      = array('keyword' => '', 'url' => $text, 'title' => $desc, 'date' => date('Y-m-d H:i:s'), 'ip' => $ip );
	$return['status']   = 'success';
	$return['message']  = "URL ".$text." added";
	$return['title']    = $desc;
	$return['html']     = '';
	$return['shorturl'] = $url;
	$return['statusCode'] = 200;


	if( !headers_sent() ) {
		header( "Content-Type: application/json; charset=utf-8" );
		echo json_encode( $return );
		exit;
	}
}
else
{
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

?>
