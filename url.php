<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>PIN urlbin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noarchive"/>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html">
<meta http-equiv="Cache-Control" content="No-Cache">
<meta property="og:type" content="website" />
<meta property="og:image:width" content="1203">
<meta property="og:image:height" content="630">
<meta property="og:title" content="PIN urlbin">
<meta property="og:description" content="Pinned things, all sort of URL-s">
<meta property="og:url" content="<?php echo $Config['site_domain'] . $Config['site_path'] .'/url.php'; ?>">
<meta property="og:image" content="<?php echo $Config['site_domain'] . $Config['site_path'] .'/assets/og-image.jpg'; ?>">

<!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

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
          <img style="float: left; margin-top: 7px;" src="paste/pin-icon.png" width="32px" heigth="32px">
          <a class="navbar-brand" href="/url-form.html">PIN urlbin</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">

  <form action="paste.php" method="post">
    <input type="hidden" name="lang" id="lang" value="URL">
    <div class="input-group">
       <span class="input-group-addon" id="name1" >URL:</span>
       <input type="text" size="128" maxlength="300" name="text" id="text"  class="form-control" aria-describedby="name1" />
    </div>
    <div class="input-group" style="margin-bottom: 10px;">
        <span class="input-group-addon" id="desc1" >Description:</span>
        <input type="text" size="50" maxlength="300" name="desc" id="desc"  class="form-control" aria-describedby="desc1" />
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Paste</button>
        <a onclick="getTitle()" class="btn btn-info">Try2get Title</a>
    </div>
  </form>

    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        var clipurlRegex = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/igm;
        var noautotitleRegex = /.*filesmonster.*|.*pornhub.*|.*unusualporn.net.*/i;
        var thedesc;

        setTimeout(async () => {
            const cliptext = await navigator.clipboard.readText();
            if ( clipurlRegex.test(cliptext) ) {
                $('#desc').val(cliptext);
                var thedesc = $('#desc').val();
                var urlRegex = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/igm;
                if ( urlRegex.test(thedesc) ) {
                    $('#desc').val('URL '+thedesc);
                    $('#text').val(thedesc);
                } else {
                    $('#desc').val('URL ');
                }
                if ( !( noautotitleRegex.test(thedesc)) ) {
                    setTimeout(async () => { getTitle(); },2000);
                }
            }
        }, 2000);
    });
    function getTitle() {
        var url = document.getElementById('text').value;
        $.ajax({
            method: "POST",
            url: 'url-form-title.php',
            data: {
                url4title: url
            },
            success: function (responseHtml) {
                var newTitle = $($.parseHTML(responseHtml)).filter('title').text();
                document.getElementById('desc').value = 'URL ' + newTitle;
            },
            error: function () {
                console.log('could not load ' + url);
            }
        });
    }
</script>
</body>
</html>
