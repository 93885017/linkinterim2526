<?php
session_start();
header('Content-Type: application/json'); // Set response type to JSON
require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
require_once 'class/QandaInfo.php';
require_once 'config/config.php';

$response = [
    'status' => 'error',
    'message' => 'An unknown error occurred.'
];

$responseCodeValue = 400;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $defaultLang = 'tc';
    $lang = (isset($_POST['lang']) && $_POST['lang'] != '') ? $_POST['lang'] : $defaultLang;
    $langObj = new BilingualClass();
    $langObj->setLanguage($lang);

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        $response['message'] = $langObj->getText('403_forbidden');
        echo json_encode($response);
        exit;
    }

    if (isset($_POST['firstname'], $_POST['lastname'], $_POST['companyname'], $_POST['email'], $_POST['question'])) {
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $companyname = trim($_POST['companyname']);
        $email = trim($_POST['email']);
        $question = trim($_POST['question']);
        $sectionVal = trim($_POST['section']);

        if (empty($firstname) || empty($lastname) || empty($companyname) || empty($email) || empty($question)) {
            $response['message'] = $langObj->getText('fill_all');
            http_response_code(422);
            echo json_encode($response);
            exit;
        }

        $db = new dbConnect();
        $conn = $db->connect();

        $qandaObj = new QandaInfo();

        if (
            $qandaObj->saveData(
                $conn,
                $firstname,
                $lastname,
                $companyname,
                $email,
                $_SERVER['HTTP_USER_AGENT'],
                $question,
                $sectionVal
            )
        ) {
            $response['status'] = 'success';
            $responseCodeValue = 200;
            $response['message'] = $langObj->getText('submit_success');
        } else {
            $response['message'] = $langObj->getText('submit_fail');
        }
    } else {
        $response['message'] = $langObj->getText('fill_all');
    }
} else {
    $response['message'] = 'Invalid request method.';
}

http_response_code($responseCodeValue);
echo json_encode($response);
exit;