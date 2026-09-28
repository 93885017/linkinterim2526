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

$isAccessFromHomePage = (isset($_GET['from']) && $_GET['from'] == 's' ) || $isForceLoadSection;
if (!ERROR_MODE_ENABLE && !$isAccessFromHomePage) {
    $currTime = time();
    echo '<script>
        window.location = "index.php?t=' . $currTime . '";
        </script>';
    exit;
}

$lang = (isset($_GET['lang']) && $_GET['lang'] != '') ? $_GET['lang'] : $default_lang;
$isTesting = (isset($_GET['testing']) && $_GET['testing'] == 'Y');


$accept_lang = array('en', 'tc', 'sc');
if (!in_array($lang, $accept_lang)) {
    $lang = 'tc';
}
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
} else {
    $currentSection = ERROR_MODE_SECTION;
}
$showSelectLang = false;
$displayImg = '';

if ($isForceLoadSection) {
    if ($loadSection == 2) {
        // force to load section 1 ready page
        $currentSection = 'ready_page_1';
    }
} else {
    require_once 'sectionCheck.php';
}

switch ($currentSection) {
    case 'ready_page_1':
        $displayImg = DESKTOP_READY_IMG_1;
        $displayImgMobile = MOBILE_READY_IMG_1;
        break;
    case 'ready_page_2':
        $displayImg = DESKTOP_READY_IMG_2;
        $displayImgMobile = MOBILE_READY_IMG_2;
        break;
    case 'video_page_1':
    case 'video_page_2':
        $showSelectLang = true;
        break;
    default:
        $currTime = time();
        echo '<script>
            window.location = "index.php?t=' . $currTime . '";
            </script>';
        exit;
        break;
}

// $buttonArr: button no => type value
$buttonArr = array(
    '1' => '1',
    '2' => '2',
    '3' => '3',
    '4' => '4'
);

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="<?=HEADER_VIEWPORT?>">
    <title><?= $langObj->getText('title') ?></title>
    <link rel="stylesheet" href="plugin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/css/main.css?v=<?= SITE_VERSION ?>">
    <link rel="stylesheet" href="style/css/index.css?v=<?= SITE_VERSION ?>">
    <script src="plugin/tools/functions.js?v=<?=SITE_VERSION?>"></script>
    <?php include_once 'page_reload_script.php'; ?>
</head>

<body class="lang-<?= $lang ?>">
<?php if ($showSelectLang) { ?>
    <div class="main-container desktop-section">
        <div class="desktop-image-container">
            <img src="style/images/desktop/home.jpg" class="full-img" alt="logo" border=0 />
            <div class="btn-field">
                <img src="style/images/desktop/home-lang.jpg" class="full-img" alt="logo" border=0 />
                <?php foreach ($buttonArr as $buttonNo => $typeValue): ?>
                    <a href="video.php?type=<?= $typeValue ?>&t=<?=time()?>" border=0>
                        <div class="btn-click b<?= $buttonNo ?>">&nbsp;</div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <br><br><br><br>
    </div>
    <div class="main-container mobile-section">
        <div class="mobile-image-container">
            <img src="style/images/mobile/home.jpg" class="full-img" alt="logo" border=0 />
            <div class="btn-field">
                <img src="style/images/mobile/home-lang.jpg" class="full-img" alt="logo" border=0 />
                <?php foreach ($buttonArr as $buttonNo => $typeValue): ?>
                    <a href="video.php?type=<?= $typeValue ?>&t=<?=time()?>" border=0>
                        <div class="btn-click b<?= $buttonNo ?>">&nbsp;</div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <br><br><br><br>
    </div>
    <style>
        .btn-field {
            width: 100%;
            position: relative;
        }

        .btn-click {
            position: absolute;
            top: 0;
            left: 0;
            width: 44.5%;
            height: 52%;
            cursor: pointer;
        }

        .btn-click.b1 {
            left: 6%;
            width: 19.5%;
        }

        .btn-click.b2 {
            left: 28.8%;
            width: 19.5%;
        }

        .btn-click.b3 {
            left: 51.6%;
            width: 19.5%;
        }

        .btn-click.b4 {
            left: 74.5%;
            width: 19.5%;
        }

        .btn-click.bm1 {
            left: 6%;
            width: 19.5%;
        }

        .btn-click.bm2 {
            left: 28.5%;
            width: 19.5%;
        }

        .btn-click.bm3 {
            left: 51.5%;
            width: 19.5%;
        }

        .btn-click.bm4 {
            left: 74.5%;
            width: 19.5%;
        }

        /* Styles for mobile devices */
        @media only screen and (max-width: 759px) {
            .btn-click {
                height: 100%;
            }
        }
    </style>
<?php } else { ?>
    <div class="main-container desktop-section">
        <div class="desktop-image-container"><img src="style/images/desktop/<?= $displayImg ?>" class="full-img" alt="finish" border=0 /></div>
    </div>
    <div class="main-container mobile-section"><img src="style/images/mobile/<?=$displayImgMobile?>" class="full-img" alt="finish" border=0 /></div>
<?php } ?>
<script src="plugin/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>