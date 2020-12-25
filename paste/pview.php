<?php

require_once '../config.php';

// UNSAFE:
// $refererurl = $_SERVER['HTTP_REFERER'];
// $refererurl = preg_replace("/\/[^\/]*$/", "", $refererurl);

$refererurl = $Config['site_domain'];

if ( isset($_GET['p']) ) {
    $pin_id = trim($_GET['p']);
    $pin_content_qry = "SELECT finaltext FROM pin WHERE url = '" . $Config['site_domain'] . "/p/" . $pin_id . "';";
    if ($pin_content_result = db()->query($pin_content_qry) ) {
        if ( $pin_content_result->num_rows > 0 ) {
            while ($row = $pin_content_result->fetch_assoc()) {
                $pin_content = $row["finaltext"];
            }
            if( !headers_sent() ) {
                @header( "Content-Type: text/html; charset=utf-8" );
                @header( "X-Content-Loaded: database" );
                echo $pin_content;
                exit;
            }
        } else if ( @file_exists("../results/".$pin_id.".html") ) {
            if( !headers_sent() ) {
                @header( "Content-Type: text/html; charset=utf-8" );
                @header( "X-Content-Loaded: resultsfile" );
                readfile("../results/".$pin_id.".html");
                exit;
            }
        }
    }
    if( !headers_sent() ) {
        header("Location: " . $refererurl);
        exit;
    }
}

    if( !headers_sent() ) {
        header("Location: " . $refererurl);
        exit;
    }

