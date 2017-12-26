<?php
include('config.php');

$url="https://api.instagram.com/oauth/authorize/".
"?client_id=".$client_id.
"&redirect_uri=".$redirect_url.
"&response_type=code";

header("Location: ".$url);
	die();