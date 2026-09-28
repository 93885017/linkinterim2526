<?php
require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
$default_lang = 'tc';
include 'config/config.php';

if (isset($_COOKIE[SITE_SESSION_KEY . 'lang'])) {
    $default_lang = $_COOKIE[SITE_SESSION_KEY . 'lang'];
}

$lang = (isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : $default_lang;
$isTesting = (isset($_GET['testing']) && $_GET['testing'] == 'Y');

$accept_lang = array('en', 'tc', 'sc');
if (!in_array($lang, $accept_lang)) {
    $lang = 'tc';
}
$langObj = new BilingualClass();
$langObj->setLanguage($lang);

if (ERROR_MODE_ENABLE) {
    switch (ERROR_MODE_SECTION) {
        case 'video_page_1':
        case 'video_page_2':
            header('Location: video.php?type=1');
            break;
        case 'ready_page_1':
        case 'ready_page_2':
            header('Location: home.php');
            break;
    }
} else {
    $db = new dbConnect();
    $conn = $db->connect(false);

    $errorMode = $conn === null;

    if (!$errorMode) {
        $helperObj = new HelperClass($conn);

        if (!$isTesting) {
            $helperObj->checkCurrentSection('contingency_page');
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="<?=HEADER_VIEWPORT?>">
    <title><?= $langObj->getText('title') ?></title>
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/css/main.css?v=<?=SITE_VERSION?>">
    <link rel="stylesheet" href="style/css/index.css?v=<?=SITE_VERSION?>">
    <script src="plugin/tools/functions.js?v=<?=SITE_VERSION?>"></script>
    <?php include_once 'page_reload_script.php'; ?>
</head>

<body>
    <div class="main-container desktop-section">
        <div class="desktop-image-container"><img src="style/images/desktop/<?= DESKTOP_CONTINGENCY_IMG ?>" class="full-img" alt="finish" border=0 /></div>
    </div>
    <div class="main-container mobile-section"><img src="style/images/mobile/<?= MOBILE_CONTINGENCY_IMG ?>" class="full-img" alt="finish" border=0 /></div>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>