<?php
/**
 * Created by PhpStorm.
 * User= farhad-mbp
 * Date= 12/26/17
 * Time= 2=22 PM
 */

$client_id = "INSTA_CLIENT_ID";
$client_secret = "INSTA_CLIENT_SECRET";
$redirect_url = "INSTA_REDIRECT_URL | access_token.php";

$db_host = "DB_HOST";
$db_username = "DB_USER";
$db_password = "DB_PASSWORD";
$db_name = "DB_NAME";

//  method 1 :  run an activity
$intent_action = "INTENT_ACTION";
$intent_key = "INTENT_DATA_KEY";
$intent = "intent:#Intent;action=$intent_action;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.$intent_key=%s;end";

//  method 2 :  check url
$launcher_key = "INTENT_DATA_KEY";
$launcher_intent = "feriinsight://login/?$launcher_key=%s";
