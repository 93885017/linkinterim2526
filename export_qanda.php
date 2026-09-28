<?php
ini_set('display_errors', 0);
require_once 'class/QandaInfo.php';
require_once 'class/dbConnect.php';

$db = new dbConnect();
$conn = $db->connect();

$logObj = new QandaInfo();
$filterBySection = $_GET['section'] ?? '';
if (trim($filterBySection) != '') {
    $filterOptions['section'] = $filterBySection;
}
// export need to include the hide status item
$logObj->exportCSV($conn, $filterOptions);
