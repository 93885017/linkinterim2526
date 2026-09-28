<?php
require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
$default_lang = 'tc';
include 'config/config.php';

if (isset($_COOKIE[SITE_SESSION_KEY . 'lang'])) {
    $default_lang = $_COOKIE[SITE_SESSION_KEY . 'lang'];
}

$isTesting = (isset($_GET['testing']) && $_GET['testing'] == 'Y');
$selected = (isset($_GET['type']) && $_GET['type'] != '') ? $_GET['type'] : '1';
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

$db = new dbConnect();
$conn = $db->connect();

$helperObj = new HelperClass($conn);

if (!$isTesting) {
    $helperObj->checkCurrentSection('palyback_page');
}

$displayVideo = '';
switch ($selected) {
    case "1":
        $displayVideo = VIDEO_3_URL_FLOOR;
        break;
    case "2":
        $displayVideo = VIDEO_3_URL_ENGLISH;
        break;
    case "4":
        $displayVideo = VIDEO_3_URL_PUTONGHUA;
        break;
    default:
        $displayVideo = VIDEO_3_URL_CANTONESE;
        break;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="<?=HEADER_VIEWPORT?>">
    <title><?= $langObj->getText('title') ?></title>
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/css/main.css?v=<?= SITE_VERSION ?>">
    <script src="plugin/tools/functions.js?v=<?= SITE_VERSION ?>"></script>
    <?php include_once 'page_reload_script.php'; ?>
</head>

<body>
    <div class="main-container desktop-section video">
        <video id="main-video" width="100%" height="100%" controls="" autoplay="autoplay" muted="" loop="loop" defaultmuted=""
            playsinline="" oncontextmenu="return false;" preload="auto">
            <source src="<?= $displayVideo ?>" type="video/mp4">
        </video>
        <div class="video-button-container">
            <a href="<?=PLAYBACK_DOWNLOAD_FILE_PATH?>" target="_blank" ><div class="btn s1"><?= $langObj->getText('Download Presentation') ?></div></a>
            <div class="btn s2 placyback">
                <select onchange="selectChapter(this.value)">
                    <option value=""><?= $langObj->getText('Please Select') ?></option>
                    <option value="0"><?= $langObj->getText('Presentation') ?></option>
                    <option value="1"><?= $langObj->getText('Q&A') ?></option>
                </select>
            </div>
            <script>
                function selectChapter(value) {
                    var video = document.getElementById("main-video");
                    var allowPlay = false;
                    if (value === '0') {
                        video.currentTime = 0;
                        allowPlay = true;
                    } else if (value === '1') {
                        video.currentTime = 22 * 60;
                        allowPlay = true;
                    }
                    if (allowPlay) {
                        video.play();
                    }
                }
            </script>
        </div>
        
        <?php 
            $toSection = 'playback';
            // include_once 'template/select-video-lang-option.php'; ?>
    </div>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>