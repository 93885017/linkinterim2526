<?php
require_once 'class/dbConnect.php';
require_once 'class/HelperClass.php';
require_once 'class/BilingualClass.php';
$default_lang = 'tc';
include 'config/config.php';

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

if (!$isTesting && !$isForceLoadSection) {
    $helperObj->checkCurrentSection('finish_page');
}

$displayImg = DESKTOP_FINSIH_IMG_1;
$displayImgMobile = MOBILE_FINSIH_IMG_1;
$currentSection = $helperObj->getCurrentSection();

if ($isForceLoadSection) {
    if ($loadSection == 1) {
        // force to load section 1 ending page
        $currentSection = 'finish_page_2';
    }
} else {
    require_once 'sectionCheck.php';
}

if ($currentSection == 'finish_page_2') {
    $displayImg = DESKTOP_FINSIH_IMG_2;
    $displayImgMobile = MOBILE_FINSIH_IMG_2;
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
    <div class="main-container desktop-section"><div class="desktop-image-container"><img src="style/images/desktop/<?= $displayImg ?>" class="full-img" alt="finish" border=0 /></div></div>
    <div class="main-container mobile-section"><img src="style/images/mobile/<?=$displayImgMobile?>" class="full-img" alt="finish" border=0 /></div>
    <script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>