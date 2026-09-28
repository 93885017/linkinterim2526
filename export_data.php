<?php
require_once 'class/RegisterInfo.php';
require_once 'class/dbConnect.php';

$db = new dbConnect();
$conn = $db->connect();

$logObj = new RegisterInfo();
$logObj->exportCSV($conn);
