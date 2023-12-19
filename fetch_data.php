<?php
session_start();

function getAuthToken(){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.baubuddy.de/index.php/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\"username\":\"365\", \"password\":\"1\"}",
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
            "Content-Type: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response);
        return $response->oauth->access_token;
    }
}

function getTasksData($authToken){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ". $authToken,
            "Content-Type: application/json"
        ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response = json_decode($response);
        return $response;
    }
}

function checkAuth(){
    if (empty($_SESSION["authToken"])){
        $authToken = updateAuth();
    }
    $authToken = $_SESSION["authToken"];
    $authTokenUpdateTime = $_SESSION["authTokenUpdateTime"];
    $currentTimestamp = time();
    $thresholdTimestamp = $authTokenUpdateTime + 1200;

    if ($currentTimestamp > $thresholdTimestamp) {
        $authToken = updateAuth();
    }

    return $authToken;
}
function updateAuth(){
    $currentTimestamp = time();
    $authToken = getAuthToken();
    $_SESSION["authToken"] = $authToken;
    $_SESSION["authTokenUpdateTime"] = $currentTimestamp;

    return $authToken;
}

$authToken = checkAuth();
$tasksData = getTasksData($authToken);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($tasksData);
exit;
?>