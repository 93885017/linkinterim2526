<?php
exit;
$section = 'login';
$currentSection = 'video_page';
require_once 'common.inc.php';
require_once 'class/LoginLog.php';
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <title><?= $langObj->getText('title') ?></title>
    <link rel="stylesheet" href="style/css/main.css?v=<?=SITE_VERSION?>">
    <?php
    $default_username  = '';
    $default_password = '';
    $default_terms = true;

    if (isset($_GET['reset']) && $_GET['reset'] == 'Y') {
        $memberObj = new Member();
        echo $memberObj->logout();
    }

    if (isset($_COOKIE[SITE_SESSION_KEY . 'username']) && $_COOKIE[SITE_SESSION_KEY . 'username'] != '' && isset($_COOKIE[SITE_SESSION_KEY . 'token']) && $_COOKIE[SITE_SESSION_KEY . 'token'] != '') {
        $db = new dbConnect();
        $conn = $db->connect();
        $memberObj = new Member();
        $memberObj->setAccessToken($_COOKIE[SITE_SESSION_KEY . 'token']);
        $valid_access_token = $memberObj->checkIsValidAccessToken($conn, $memberObj->getAccessToken(), $_COOKIE[SITE_SESSION_KEY . 'username']);
        if ($valid_access_token) {
            echo '<script>window.location.href = "home.php";</script>';
            exit;
        } else {
            echo $memberObj->logout();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new dbConnect();
        $conn = $db->connect();

        $memberObj = new Member();

        $valid_login = $memberObj->login($conn, $username, $password);

        if ($valid_login) {
            $logObj = new LoginLog();
            $logObj->LogData($conn, $username, $_SERVER['HTTP_USER_AGENT']);

            echo '<script>
                setCookie("' . SITE_SESSION_KEY . 'username", "' . $username . '");
                setCookie("' . SITE_SESSION_KEY . 'token", "' . $memberObj->getAccessToken() . '");
                window.location.href = "home.php";
                </script>';

            exit;
        } else {
            $default_username = $username;
            $default_password = $password;
            $default_terms = ($_POST['sel_acknowledae'] === 'Y');
            $error_message = $langObj->getText('form_err1');
        }
    }
    ?>
</head>

<body class="bg-login lang-<?= $lang ?>">
    <?php require_once 'header.php'; ?>
    <div class="main-container login-page">
        <form method="post" id="loginForm">

            <div class="">
                <div class="row">
                    <div class="col-style col-md-6 col-sm-12 mobile_input">
                        <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('login_name') ?></label><label class="required-txt">*</label></div>
                        <input type="text" class="input-style" id="username" name="username" value="<?= $default_username ?>" placeholder="<?= $langObj->getText('login_name') ?>" />
                    </div>
                    <div class="col-style col-md-6 col-sm-12 mobile_input">
                        <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('password') ?></label><label class="required-txt">*</label></div>
                        <input type="password" class="input-style" id="password" name="password" value="<?= $default_password ?>" placeholder="<?= $langObj->getText('password') ?>" />
                    </div>
                </div>
            </div>

            <div class="acknowledae-field">
                <div class="row">
                    <div class="col-style col-md-6 col-sm-12 mobile_input">
                        <div class="inut-label"><label class="input-lable-text"><?= $langObj->getText('acknowledae') ?></label></div>
                        <select class="select-style" id="sel_acknowledae" name="sel_acknowledae">
                            <option value=""></option>
                            <option value="Y" <?= ($default_terms) ? "selected" : "" ?>><strong><?= $langObj->getText('confirm') ?></strong></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="desktop-section"><br><br><br><br></div>
            <div class="mobile-section"><br></div>
            <div class="bnt-submit mobile_input" onclick="formCheck();"><?= $langObj->getText('submit') ?></div>
        </form>
    </div>

    <script>
        function formCheck() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            var sel_acknowledae = document.getElementById('sel_acknowledae').value;

            if (username == '' && password == '' && sel_acknowledae == '') {
                messageDisplay('<?= $langObj->getText('fill_all') ?>');
                return;
            }

            if (username == '') {
                messageDisplay('<?= $langObj->getText('fill_all') ?>');
                return;
            }

            if (password == '') {
                messageDisplay('<?= $langObj->getText('fill_all') ?>');
                return;
            }

            if (sel_acknowledae == '') {
                messageDisplay('<?= $langObj->getText('fill_acknowledae') ?>');
                return;
            }

            document.getElementById("loginForm").submit();
        }

        $(document).ready(function() {
            <?php if (isset($error_message)) { ?>
                messageDisplay('<?= $error_message ?>');
            <?php } ?>
        });
    </script>
</body>

</html>