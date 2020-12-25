<?php
  require_once '../config.php';

  function removePaste($pasteID) {
  global $Config;
    $return = 1;

    $resultDir = $_SERVER['DOCUMENT_ROOT'] . $Config['site_path'] ."/". $Config['results_dir'];

    $filenameHTML = $resultDir . $pasteID.".html";
    if (file_exists($filenameHTML)) {

      if (unlink($filenameHTML)) {
        $return = 1;
      } else {
        print "Failed to remove ".$filenameHTML."<br/>\n";
        $return = 0;
      }
    } else {
        error_log("File does not exist: ".$filenameHTML."\n");
        $return = 0;
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
<title>PIN Browser</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noarchive"/>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html">
<meta http-equiv="Cache-Control" content="No-Cache">
<!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="browser.css"/>
    <link href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="overlay"><h2>Loading .. Please wait<br><img  src="./pin-loading.gif"></h2></div>
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
          <a class="navbar-brand" href="/">PIN Pastebin</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
		<li><a href="https://pin.r-us.hu/">NEW</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">
<?php
    echo "<table id='private-pin' class='table table-striped table-bordered'>\n<thead>\n";
    echo "<tr><th id=\"hdr_act\">Action</th><th id=\"hdr_date\">Date</th><th id=\"hdr_url\">URL</th><th id=\"hdr_desc\">Desc.</th></tr>\n</thead>\n<tbody class=\"dont-break-out\">\n";
    echo "</tbody>\n</table>\n";
?>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script  type="text/javascript" class="init">
    $(document).ready(function() {
        $("#overlay").show();
        $('#private-pin').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": "browser.ajax.php",
            "order": [[ 1, "desc" ]],
            "pageLength": 50,
            "lengthMenu": [10, 25, 50, 100],
            "columns": [
                { "data": "d_act" },
                { "data": "d_date" },
                { "data": "d_url" },
                { "data": "d_desc" }
            ],
            fnInitComplete : function() {
                $("#overlay").hide();
            }
        } );
    } );
    </script>
</body>
