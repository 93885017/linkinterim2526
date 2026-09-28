<?php
// error_reporting(E_ALL);
session_start(); // start the session
date_default_timezone_set('Asia/Hong_Kong');

require_once 'class/dbConnect.php';
require_once 'class/Member.php';
require_once 'class/BilingualClass.php';
include_once 'config/config.php';

$db = new dbConnect();
$conn = $db->connect();
require_once 'class/HelperClass.php';
$helperObj = new HelperClass($conn);
$testMode = false;
$default_lang = 'tc';

if (isset($_GET['testing'])) {
    if ($_GET['testing'] == '23flbskj@J24') {
        echo '<h2>Test Mode</h2>';
        $testMode = true;
        $_SESSION['testMode'] = true;
    }

} else if (isset($_SESSION['testMode']) && $_SESSION['testMode'] == true) {
    echo '<h2>Test Mode</h2>';
    $testMode = true;
}

if (!$testMode && $currentSection != 'logout') {
    $helperObj->checkCurrentSection($currentSection);
}

if (isset($_COOKIE[SITE_SESSION_KEY . 'lang']) && $_COOKIE[SITE_SESSION_KEY . 'lang'] != '') {
    $default_lang = $_COOKIE[SITE_SESSION_KEY . 'lang'];
}

$lang = (isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : $default_lang;


$accept_lang = array('en', 'tc', 'sc');
if (!in_array($lang, $accept_lang)) {
    $lang = 'tc';
}

$langObj = new BilingualClass();
$langObj->setLanguage($lang);

?>
<script src="plugin/tools/functions.js"></script>
<script>setLanguage('<?= SITE_SESSION_KEY ?>', '<?= $lang ?>', false);</script>
<?php
if (isset($section) && $section != 'login') {
    $memberObj = new Member();

    // get cookie and check if the user has logged in
    $tmpUsername = '';
    $tmpToken = '';
    if (isset($_COOKIE[SITE_SESSION_KEY . 'username']) && $_COOKIE[SITE_SESSION_KEY . 'username'] != '') {
        $tmpUsername = $_COOKIE[SITE_SESSION_KEY . 'username'];
    }
    if (isset($_COOKIE[SITE_SESSION_KEY . 'token']) && $_COOKIE[SITE_SESSION_KEY . 'token'] != '') {
        $tmpToken = $_COOKIE[SITE_SESSION_KEY . 'token'];
    }

    if ($tmpUsername == '' || $tmpToken == '') {
        echo $memberObj->logout();
        echo '<script>window.location.href = "login.php";</script>';
        exit;
    }

    $valid_access_token = $memberObj->checkIsValidAccessToken($conn, $tmpToken, $tmpUsername);

    if (!$valid_access_token) {
        echo $memberObj->logout();
        echo '<script>alert("' . $langObj->getText('multi_login') . '");window.location.href = "login.php";</script>';
        exit;
    }

}