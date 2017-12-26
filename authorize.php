<?php 

$config_str = file_get_contents("./config.json");
$config = json_decode($config_str);

$client_id=$config->client_id;
$client_secret=$config->client_secret;
$redirect_url=$config->redirect_url;

$url="https://api.instagram.com/oauth/authorize/".
"?client_id=".$client_id.
"&redirect_uri=".$redirect_url.
"&response_type=code";

header("Location: ".$url);
	die();