<?php

include('config.php');

date_default_timezone_set("Asia/Tehran");

$code = $_GET['code'];

$mysqli = new mysqli($db_host, "$db_username", "$db_password", "$db_name");

if (!$code) {
    http_response_code(404);
    $err_response = ['error_description' => "did not receive \"code\"",
        'error' => "not_found"];
    $err_json = json_encode($err_response);
    echo $err_json;
    die();
}

getAccessToken();

function getAccessToken()
{
    $curl = curl_init();

    global $client_id, $client_secret, $redirect_url, $code;

    $fields =
        'client_id=' . $client_id .
        '&client_secret=' . $client_secret .
        '&grant_type=' . 'authorization_code' .
        '&redirect_uri=' . $redirect_url .
        '&code=' . $code;

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.instagram.com/oauth/access_token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $url = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
//    echo "\nhttpcode : " . $httpcode;
//    echo "\nurl : " . $url;


    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        http_response_code($httpcode);


        if ($httpcode < 300) {
            if (addToDb(json_decode($response))) {
                launchIntent($response);

//                echo "logged in successfully\n\n";
//                echo $response;

            } else {
                echo "Error in processing";
            }
        } else {
            echo $response;
        }

    }
}

function addToDb($response)
{
    global $mysqli;
    $user = $response->user;
    $id = $user->id;
    $username = $user->username;
    $full_name = $user->full_name;

    $access_token = $response->access_token;
    $date = date("Y-m-d h:i:s");

    $query = "replace INTO users " .
        "(id,  username ,  profile_picture ,  full_name ,  bio ,  website ,  is_business ,  access_token ,  sign_date ,  meta ) VALUES" .
        "('$id', '$username', NULL, '$full_name', NULL, NULL, NULL, '$access_token', '$date', NULL);";
    if ($mysqli->query($query) === TRUE) {
        return true;
//        $last_id = $mysqli->insert_id;
//        echo "New record created successfully. Last inserted ID is: " . $last_id;
    } else {
        return false;
//        echo "Error: " . $query . "<br>" . $mysqli->error;
    }
}

function launchIntent($user_data)
{
    global $intent, $intent_action;
    global $launcher_intent;
    $url = sprintf($intent, (string)$user_data);
//    echo $url."\n";
//    echo $intent_action;

//    header('Location: ' . $url);
    $url2 = sprintf($launcher_intent, urlencode((string)$user_data));
//    echo $url2;
    echo "<a href = $url2 >Touch to launch app</a>";

//    header("Location: "+$url2);
//    die();
}
