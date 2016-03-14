<?php
  require_once '../config.php';

  function removePaste($pasteID) {
  global $Config;
    $return = 1;

    $resultDir = $_SERVER['DOCUMENT_ROOT'] . $Config['results_dir'];
    $resultDir = $_SERVER['DOCUMENT_ROOT'] . $Config['site_path'] ."/". $Config['results_dir'];

    $filenameHTML = $resultDir . $pasteID.".html";
    if (file_exists($filenameHTML)) {

      if (unlink($filenameHTML)) {


      } else {
        print "Failed to remove $filenameHTML<br/>\n";
        $return = 0;
      }
    }
    return $return;
  }

if ( isset($_GET["purgeitem"]) ) {
    $url = $_GET["purgeitem"];
    $urlparts = explode("/", $url);
    $pasteID = $urlparts['4'];
    $isremoved = removePaste($pasteID);
    if ($isremoved) {
	$query = sprintf('DELETE FROM pin where url = "%s"',$url);

	if (!db()->query($query) ) {
    	    echo "Failed: ".$query." <br>(" . db()->errno . ") " . db()->error."\n";
    	    exit;
            }
    header("Location: ./browser.php");
    }

}



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
"http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
<head>
<title>Pastebin access</title>
<link rel="stylesheet" type="text/css" href="../paste.css"/>
<meta name="robots" content="noarchive"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
</head>
<body>
<span id="controls"><H1><a href="<?php echo $Config['site_domain']; ?>/"><img src="pin-icon.png"><br />NEW</a></H1></span>
<?php

    $query = 'SELECT FROM_UNIXTIME(ts),url,description FROM pin ORDER BY ts DESC;';

    if ($result = db()->query($query) ) {
    echo "<table width=100% border=1 padding=1>\n";
    echo "<tr><td width=10%>ACTION</td><td width=15%>TIMESTAMP</td><td width=18%>URL</td><td width=57%>DESCRIPTION</td></tr>\n";
    while($row = $result->fetch_array()) {


	if (substr($row['2'], 0, 4) === 'URL ') {
		$desc = substr($row['2'], 0, 76) . " ...";
		echo "<tr><td width=10%><a href=\"?purgeitem=".$row[1]."\">Purge</a></td><td width=15%>".$row[0]."</td><td width=18%><a href=\"".$row[1]."\">".$row[1]."</a></td><td width=57%><a href=\"" .substr($row['2'],4) . "\">".$desc."</td></tr>\n";
	} else {
		echo "<tr><td width=10%><a href=\"?purgeitem=".$row[1]."\">Purge</a></td><td width=15%>".$row[0]."</td><td width=18%><a href=\"".$row[1]."\">".$row[1]."</a></td><td width=57%>".$row['2']."</td></tr>\n";
	}


}
    $result->close();
}
echo "</table>\n";
?>
</body>
