<?php
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
$default_lang = 'tc';
include 'config/config.php';

if (isset($_COOKIE[SITE_SESSION_KEY . 'lang'])) {
    $default_lang = $_COOKIE[SITE_SESSION_KEY . 'lang'];
}

$selected = (isset($_GET['type']) && $_GET['type'] != '') ? $_GET['type'] : '1';
$isTesting = (isset($_GET['testing']) && $_GET['testing'] == 'Y');
switch ($selected) {
    case "1":
    case "2":
        $_GET['lang'] = 'en';
        break;
    case "4":
        $_GET['lang'] = 'sc';
        break;
    default:
        $_GET['lang'] = 'tc';
        break;
}

$lang = (isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : $default_lang;

$langObj = new BilingualClass();
$langObj->setLanguage($lang);

if (!ERROR_MODE_ENABLE) {
    $db = new dbConnect();
    $conn = $db->connect();
}

$helperObj = new HelperClass($conn);

if (!ERROR_MODE_ENABLE) { 
    if (!$isTesting) {
        //TODO check later
        $helperObj->checkCurrentSection('any');
    }
    
    $currentSection = $helperObj->getCurrentSection();
    require_once 'sectionCheck.php';
} else {
    $currentSection = ERROR_MODE_SECTION;
}

// $versionValue = $helperObj->getSiteConfig('update_version');
$displayVideo = '';
$downloadFile = '';
switch ($currentSection) {
    case 'video_page_1':
        $displayVideo = $helperObj->getDisplayVideo(1, $selected);
        $downloadFile = VIDEO_DOWNLOAD_1;
        break;
    case 'video_page_2':
        $displayVideo = $helperObj->getDisplayVideo(2, $selected);
        $downloadFile = VIDEO_DOWNLOAD_2;
        break;
    case 'ready_page_1':
    case 'ready_page_2':
        $t = time();
        echo '<script>
            window.location = "home.php?from=s&t=' . $t . '";
            </script>';
        exit;
    default:
        echo '<script>
            window.location = "index.php?t=' . $t . '";
            </script>';
        exit;
}
?><!DOCTYPE html>
<html>
<meta name="viewport" content="">

<head>
    <title><?= $langObj->getText('title') ?></title>
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/css/main.css?v=<?= SITE_VERSION ?>">
    <script src="plugin/tools/functions.js?v=<?= SITE_VERSION ?>"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include_once 'page_reload_script.php'; ?>
</head>

<body class="bg-video lang-<?= $lang ?>">
    <?php require_once 'template/popup.php'; ?>
    <div class="main-container video">
        <iframe id="main-video" src="<?=$displayVideo?>" width="100%"
            style="aspect-ratio: 16/9;" frameborder="0" scrolling="no" allow="autoplay"
            allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>
        <div class="video-button-container">
            <?php if (!ERROR_MODE_ENABLE) { ?>
            <div class="btn s1" id="qandaBtn"><?= $langObj->getText('Submit Question') ?></div>
            <?php } ?>
            <a href="<?= $downloadFile ?>" target="_blank">
                <div class="btn s1"><?= $langObj->getText('Download Presentation') ?></div>
            </a>
        </div>

        <?php include_once 'template/select-video-lang-option.php'; ?>
        <?php 
            if (!ERROR_MODE_ENABLE) {
                include_once 'template/qanda-box.php';
            }
        ?>
    </div>
    <input type="hidden" value="<?= $currentSection ?>" />
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>