<?php
session_start();

$response = array('loggedIn' => false);

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $response['loggedIn'] = true;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
