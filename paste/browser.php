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
<!DOCTYPE html>
<html lang="en">
<head>
<title>Pastebin access</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noarchive"/>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html">
<meta http-equiv="Cache-Control" content="No-Cache">

<link rel="stylesheet" type="text/css" href="../paste.css"/>
<!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<body style="padding-top: 70px;">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img style="float: left; margin-top: 7px;" src="pin-icon.png" width="32px" heigth="32px">
          <a class="navbar-brand" href="<?php echo $Config['site_domain']; ?>">PIN Pastebin</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
                <li><a href="<?php echo $Config['site_domain']; ?>">NEW</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">

<?php

    $query = 'SELECT FROM_UNIXTIME(ts),url,description FROM pin ORDER BY ts DESC;';

    if ($result = db()->query($query) ) {
    echo "<div class='table-responsive'>\n";
    echo "<table id='browse-pin' class='table table-striped table-bordered'>\n";
    echo "<thead><tr><th width=10%>ACTION</th><th width=15%>TIMESTAMP</th><th width=18%>URL</th><th width=57%>DESCRIPTION</th></tr></thead>\n";
    echo "<tbody>\n";
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
echo "</table></div>\n";
?>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script  type="text/javascript" class="init">
    $(document).ready(function() {
        $('#browse-pin').DataTable( {
            "order": [[ 1, "desc" ]]
        } );
    } );
    </script>
</body>
