<?php

define('SITE_VERSION', '1_4');

define('DB_USERNAME', 'uehy2gdxpeqbl');
define('DB_PASSWORD', '52_e#2%][6h2');
define('DB_HOST', 'localhost');
define('DB_NAME', 'db2zzsxihy8vht');

define('HEADER_VIEWPORT', 'width=device-width, initial-scale=1.0');
// define('HEADER_VIEWPORT', '');

define('HEADER_DATE', '');
define('SITE_SESSION_KEY', 'link2025linksREIT20252026001');

// desktop
define('DESKTOP_INDEX_IMG', 'index.jpg');
define('DESKTOP_CONTINGENCY_IMG', 'contingency_page.jpg');
define('DESKTOP_FINSIH_IMG_1', 'finish1.jpg');
define('DESKTOP_FINSIH_IMG_2', 'finish2.jpg');
define('DESKTOP_READY_IMG_1', 'ready1.jpg');
define('DESKTOP_READY_IMG_2', 'ready2.jpg');

// mobile
define('MOBILE_INDEX_IMG', 'index.jpg');
define('MOBILE_INDEX_IMG_LANDSCAPE', 'index.jpg');
define('MOBILE_CONTINGENCY_IMG', 'contingency_page.jpg');
define('MOBILE_FINSIH_IMG_1', 'finish1.jpg');
define('MOBILE_FINSIH_IMG_2', 'finish2.jpg');
define('MOBILE_READY_IMG_1', 'ready1.jpg?v=1');
define('MOBILE_READY_IMG_2', 'ready2.jpg');


// video 1
define('VIDEO_1_URL_FLOOR', 'https://player.castr.com/live_a8a8613059c511ed854f9d00f585fb33');
define('VIDEO_1_URL_ENGLISH', 'https://player.castr.com/live_95527950c56e11f083329792c8961059');
define('VIDEO_1_URL_CANTONESE', 'https://player.castr.com/live_719efb70f85011ed8cbbd9b57d40e70e');
define('VIDEO_1_URL_PUTONGHUA', 'https://player.castr.com/live_831ae28059c511edb095b3325e745ec8');

// video 2
define('VIDEO_2_URL_FLOOR', 'https://player.castr.com/live_a8a8613059c511ed854f9d00f585fb33');
define('VIDEO_2_URL_ENGLISH', 'https://player.castr.com/live_95527950c56e11f083329792c8961059');
define('VIDEO_2_URL_CANTONESE', 'https://player.castr.com/live_719efb70f85011ed8cbbd9b57d40e70e');
define('VIDEO_2_URL_PUTONGHUA', 'https://player.castr.com/live_831ae28059c511edb095b3325e745ec8');

// playback
define('VIDEO_3_URL_FLOOR', 'assets/pb_Recording_2200QnA.mp4');
define('VIDEO_3_URL_ENGLISH', 'assets/pb_Recording_2200QnA.mp4');
define('VIDEO_3_URL_CANTONESE', 'assets/pb_Recording_2200QnA.mp4');
define('VIDEO_3_URL_PUTONGHUA', 'assets/pb_Recording_2200QnA.mp4');

define('VIDEO_DOWNLOAD_1', 'http://linkinterimresults2526.com/20251120_IR202526_Interim_Results_Presentation.pdf?1');
define('VIDEO_DOWNLOAD_2', 'http://linkinterimresults2526.com/20251120_IR202526_Interim_Results_Presentation.pdf?2');
define('PLAYBACK_DOWNLOAD_FILE_PATH', 'http://linkinterimresults2526.com/20251120_IR202526_Interim_Results_Presentation.pdf?3');

define('ERROR_MODE_SECTION', '');
// video_page_1, video_page_2, ready_page_1, ready_page_2
if (ERROR_MODE_SECTION == '') {
    define('ERROR_MODE_ENABLE', false);
} else {
    define('ERROR_MODE_ENABLE', true);
}

define('SAME_IP_SUBMIT_MAX_COUNT', 50);
