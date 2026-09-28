<?php
// if(!isset($versionValu)) {
//     $versionValue = $helperObj->getSiteConfig('update_version');
// }
// if (isset($versionValue)) {
//     $pathVersion = (isset($_GET['v'])) ? $_GET['v'] :'';
//     if ($pathVersion != $versionValue) {
//         $currTime = time();
//         echo "<script>
//             var url = new URL(window.location.href);
//             url.searchParams.set('v', $versionValue);
//             window.history.replaceState({}, document.title, url.toString());
//             location.reload();
//             </script>";
//         exit;
//     }
// }