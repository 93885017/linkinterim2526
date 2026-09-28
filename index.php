<?php
session_start();
// enable php debug mode
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
$default_lang = 'tc';
require_once 'config/config.php';

if (isset($_COOKIE[SITE_SESSION_KEY . 'lang'])) {
    $default_lang = $_COOKIE[SITE_SESSION_KEY . 'lang'];
}

$isForceLoadSection = (isset($_GET['f']) && $_GET['f'] == 'y');
$loadSection = isset($_GET['section']) ? $_GET['section'] : '';

$lang = (isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : $default_lang;
$isTesting = (isset($_GET['testing']) && $_GET['testing'] == 'Y');

$accept_lang = array('en', 'tc', 'sc');
if (!in_array($lang, $accept_lang)) {
    $lang = 'tc';
}
$langObj = new BilingualClass();
$langObj->setLanguage($lang);

$db = new dbConnect();
$conn = $db->connect();

$helperObj = new HelperClass($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $validRequest = true;
    $requestIP = $helperObj->getUserIP();
    $sessionKey = 'request_' . $requestIP . '_cache';
    $sessionLifetime = 180; // 3 minutes
    $requestCount = $helperObj->getSessionWithLifetime($sessionKey);

    $lastname = $_POST['lastname'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $companyname = $_POST['companyname'] ?? '';

    if ($requestCount) {
        $requestCount = (int) $requestCount;
        if ($requestCount >= SAME_IP_SUBMIT_MAX_COUNT) {
            $validRequest = false;

            if($requestCount < (SAME_IP_SUBMIT_MAX_COUNT + 50) ) {
                // only log first 50 exceeded attempts to avoid log flood
                // add file log
                $logMessage = "[" . date("Y-m-d H:i:s") . "] IP: $requestIP exceeded the maximum submission limit.\n";
                $exceededLogFile = 'logs/request_limit_' . date('Y-m-d') . '.log';

                if (!is_dir('logs')) {
                    mkdir('logs', 0755, true);
                }
                
                $logResult1 = file_put_contents($exceededLogFile, $logMessage, FILE_APPEND);
                $dataLog = "count: " . $requestCount . " - ";
                $dataLog.= "[" . date("Y-m-d H:i:s") . "] IP: $requestIP, lastname: $lastname, firstname: $firstname, companyname: $companyname";
                $logResult2 = file_put_contents('logs/request_data' . date('Y-m-d') . '.log', $dataLog . "\n", FILE_APPEND);

                // increment the session variable
                $helperObj->createSessionWithLifetime($sessionKey, $requestCount + 1, $sessionLifetime);
            } else {
               // echo 'ignored add log<br>';
            }
            
        } else {
            // increment the session variable
            $helperObj->createSessionWithLifetime($sessionKey, $requestCount + 1, $sessionLifetime);
        }
    } else {
        // set the session variable
        $helperObj->createSessionWithLifetime($sessionKey, 1, $sessionLifetime);
    }

    if ($validRequest) {
        
        include_once 'class/RegisterInfo.php';
        $registerInfoObj = new RegisterInfo();

        try {
            $registerInfoObj->saveData(
                $conn,
                $lastname,
                $firstname,
                $companyname,
                $_SERVER['HTTP_USER_AGENT']
            );
        } catch (Exception $e) {
            // echo '' . $e->getMessage() . '';
            // exit;
        }
    }

    $extraPath = '';
    if (isset($_POST['f']) && $_POST['f'] == '1') { 
        $extraPath = '&f=y&section=' . $loadSection; 
    }

    echo '<script>
        window.location.href = "home.php?lang=' . $lang . '&from=s&t=' . time() . $extraPath . '";
        </script>';

    exit;
}

$testMode = true;
if (!$isTesting) {
    //TODO check later
    $helperObj->checkCurrentSection('any');
}
$currentSection = $helperObj->getCurrentSection();

if (!$isForceLoadSection) {
    require_once 'sectionCheck.php';

    switch ($currentSection) {
        case 'ready_page_1':
        case 'ready_page_2':
        case 'video_page_1':
        case 'video_page_2':
            break;
        default:
            $currTime = time();
            echo '<script>
                window.location = "' . $helperObj->getRedirectPage($currentSection) . '?t=' . $currTime . '";
                </script>';
            exit;
            break;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $langObj->getText('title') ?></title>
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/css/main.css?v=<?= SITE_VERSION ?>">
    <link rel="stylesheet" href="style/css/index.css?v=<?= SITE_VERSION ?>">
    <script src="plugin/tools/functions.js?v=<?= SITE_VERSION ?>"></script>
    <?php include_once 'page_reload_script.php'; ?>
</head>

<body class="lang-<?= $lang ?>" style="background: #dfdced;">
    <?php
    $isResponsive = true;
    require_once 'header.php'; ?>
    <?php require_once 'template/popup.php'; ?>
    <div class="main-container login-page">
        <div class="desktop-section">
            <img src="style/images/desktop/index_body_<?= $lang ?>.jpg" border=0 />
        </div>
        <form method="post" id="loginForm">
            <div class="mobile-section mobile-register-form">
                <div class="notice-msg-field">
                    <?= $langObj->getText('Please fill in your information below.') ?>
                </div>
                <div class="">
                    <div class="row">
                        <div class="col-style col-md-12">
                            <div class="inut-label"><label
                                    class="input-lable-text"><?= $langObj->getText('firstname') ?></label></div>
                            <input type="text" class="input-style" id="mfirstname" name="mfirstname" value="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-style col-md-12">
                            <div class="inut-label"><label
                                    class="input-lable-text"><?= $langObj->getText('lastname') ?></label></div>
                            <input type="text" class="input-style" id="mlastname" name="mlastname" value="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-style col-md-12">
                            <div class="inut-label"><label
                                    class="input-lable-text"><?= $langObj->getText('companyname') ?></label></div>
                            <input type="text" class="input-style" id="mcompanyname" name="mcompanyname" value="" />
                        </div>
                    </div>
                </div>

                <div class="policy-field">
                    <button type="button" class="submit-button"
                        onclick="formCheck('mobile')"><?= $langObj->getText('submit') ?></button>
                    &nbsp;
                    <button type="button" class="submit-button"
                        onclick="pageSkip()"><?= $langObj->getText('skip') ?></button>
                    <div class="policy-view" onclick="openPolicy()"><u><?= $langObj->getText('Privacy Policy') ?></u></div>
                </div>
            </div><!--mobile-section-->

            <div class="desktop-section">
                <input type="hidden" name="f" value="<?=$isForceLoadSection ? 1: 0?>" />
                <input type="text" class="form-input-box i1 form-input-item" id="firstname" name="firstname" value="" />
                <input type="text" class="form-input-box i2 form-input-item" id="lastname" name="lastname" value="" />
                <input type="text" class="form-input-box i3 form-input-item" id="companyname" name="companyname" value="" />
                <div class="form-input-box i4" onclick="formCheck('desktop')"></div>
                <div class="form-input-box i5" onclick="pageSkip()"></div>
                <div class="form-input-box i6" onclick="openPolicy()"></div>
            </div><!--desktop-section-->
        </form>
    </div>

    <script>
        function formCheck(type) {
            if (type == 'mobile') {
                var firstname = document.getElementById('mfirstname').value;
                var lastname = document.getElementById('mlastname').value;
                var companyname = document.getElementById('mcompanyname').value;

                document.getElementById('firstname').value = firstname;
                document.getElementById('lastname').value = lastname;
                document.getElementById('companyname').value = companyname;
            } else {
                var firstname = document.getElementById('firstname').value;
                var lastname = document.getElementById('lastname').value;
                var companyname = document.getElementById('companyname').value;
            }

            if (firstname == '' || lastname == '' || companyname == '') {
                messageDisplay('<?= $langObj->getText('fill_all') ?>');
                return;
            }

            document.getElementById("loginForm").submit();
        }

        function pageSkip() {
            window.location.href = 'home.php?lang=<?= $lang ?>&from=s&t=<?= time() ?><?php 
                if ($isForceLoadSection) { 
                    echo '&f=y&section=' . $loadSection; 
                }
                ?>';
        }

        $(document).ready(function () {
            <?php if (isset($error_message)) { ?>
                messageDisplay('<?= $error_message ?>');
            <?php } ?>
        });

        function openPolicy() {
            policyDisplay();
        }
    </script>
    <style>
        .form-input-box {
            position: absolute;
            border: 0;
            /* border: 1px solid red; */
            width: 19.5%;
            height: 4.3%;
        }

        .form-input-box.i1 {
            top: 11%;
            left: 5.5%;
        }

        .form-input-box.i2 {
            top: 11%;
            left: 38.5%;
        }

        .form-input-box.i3 {
            top: 25.5%;
            left: 5.5%;
            width: 52.5%;
        }

        .form-input-box.i4 {
            top: 37.1%;
            left: 5.4%;
            width: 3.8%;
            height: 4%;
        }

        .form-input-box.i5 {
            top: 37.1%;
            left: 9.5%;
            width: 3.8%;
            height: 4%;
        }

        .form-input-box.i6 {
            top: 42%;
            left: 5.5%;
            width: 10%;
            height: 3%;
        }

        .form-input-item {
            font-size: 1vw;
        }
    </style>
</body>

</html>