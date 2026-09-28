<?php

$loadSection = isset($_GET['section']) ? $_GET['section'] : '';
if ($loadSection == '') {
    // get from session first then cookie
    if (isset($_SESSION[SITE_SESSION_KEY . '_load_section'])) {
        $loadSection = $_SESSION[SITE_SESSION_KEY . '_load_section'];
    } else if (isset($_COOKIE[SITE_SESSION_KEY . '_load_section'])) {
        $loadSection = $_COOKIE[SITE_SESSION_KEY . '_load_section'];
    }
}

if ($loadSection != '') {
    setcookie(SITE_SESSION_KEY . '_load_section', $loadSection, time() + (30 * 24 * 60 * 60), "/");
    $_SESSION[SITE_SESSION_KEY . '_load_section'] = $loadSection;

    // echo 'checking section mapping...<br>';
    // echo $loadSection . '<br>';
    // echo $currentSection . '<br>';

    $redirectPage = '';

    if ($loadSection == 1) {
        if (
            $currentSection == 'ready_page_1'
            || $currentSection == 'video_page_1'
            || $currentSection == 'finish_page_1'
        ) {
            $redirectPage = 'finish.php?t=' . time() . '&section=1&f=y';
        }
    } else {
        // as 2
        if (
            $currentSection == 'ready_page_2'
            || $currentSection == 'video_page_2'
            || $currentSection == 'finish_page_2'
        ) {
            $redirectPage = 'index.php?t=' . time() . '&section=2&f=y';
        }
    }

    if ($redirectPage != '') {
        echo '<script>
            window.location = "' . $redirectPage . '";
            </script>';
        exit;
    }
}
?>