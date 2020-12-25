<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>PIN pastebin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="noarchive"/>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html">
<meta http-equiv="Cache-Control" content="No-Cache">
<meta property="og:type" content="website" />
<meta property="og:image:width" content="1203">
<meta property="og:image:height" content="630">
<meta property="og:title" content="PIN Pastebin">
<meta property="og:description" content="Pinned things, all sort if source code, notes and URL-s">
<meta property="og:url" content="<?php echo $Config['site_domain'] . $Config['site_path'] .'/'; ?>">
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
          <a class="navbar-brand" href="/">PIN Pastebin</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container">

  <form action="paste.php" method="post">
    <div class="input-group" style="margin-bottom: 10px;" id="lang-container">
    <span class="input-group-addon" id="lang1">Language:</span>
    <select name="lang" id="lang" class="form-control"  aria-describedby="lang1">
        <option value="Plain Text" selected>Plain Text</option>
        <option value="URL">URL</option>
        <option value="Bash">Bash *</option>
        <option value="Batch">Batch *</option>
        <option value="SQL">SQL</option>
        <option value="PHP">PHP</option>
        <option value="Perl">Perl</option>
        <option value="Python">Python</option>
        <option value="Ruby">Ruby</option>
        <option value="Java">Java</option>
        <option value="C++">C++</option>
        <option value="C#">C#</option>
        <option value="C">C (C99)</option>
        <option value="C89">C (C89)</option>
        <option value="VB">Visual Basic</option>
        <option value="Pascal">Pascal</option>
        <option value="PL/I">PL/I</option>
    </select>
    </div>
    <div class="input-group" style="margin-bottom: 10px;">
        <span id="desc1" class="input-group-addon">Description:</span>
        <input type="text" size="50" maxlength="254" name="desc" id="desc" class="form-control" aria-describedby="desc1" />
    </div>
    <div class="form-group">
    <textarea name="text" id="text" cols="80" rows="20" wrap="off" style="width: 85%;"></textarea>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Paste</button>
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

        setTimeout(async () => {
            const cliptext = await navigator.clipboard.readText();
            if ( clipurlRegex.test(cliptext) ) {
                $('#desc').val(cliptext);
            }
        }, 2000);

        $('#lang-container').on('change', "#lang", function(){
            var thelang = $('#lang').val();
            if ( thelang == 'URL' ) {
                var thedesc = $('#desc').val();
                var urlRegex = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/igm;
                if ( urlRegex.test(thedesc) ) {
                    $('#desc').val('URL '+thedesc);
                    $('#text').val(thedesc);
                } else {
                    $('#desc').val('URL ');
                }
            }
        });
    });
</script>



</body>

</html>
