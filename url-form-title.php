<?php

require_once 'config.php';

if (isset($_POST['url4title']) && "" != ($url = trim($_POST['url4title'])))
{
    echo file_get_contents_curl($url);
}
