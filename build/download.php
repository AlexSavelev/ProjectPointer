<?php
if(empty($_GET['p'])) {
    die();
}
$project_ref = $_GET['p'];

// Check OWNER
include '../base.php';
$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
if (!$result || mysqli_num_rows($result) == 0) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	$trueOwner = mysqli_fetch_assoc($result)['owner'];
}
if(empty($_COOKIE["user_id"]) or $_COOKIE["user_id"]!=$trueOwner) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
}
// End check OWNER

$filename = '../media/'.$project_ref.'/Project.pdf';

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");
header('Content-Disposition: attachment; filename="'.basename($filename).'"');
header('Content-Length: ' . filesize($filename));
header('Pragma: public');

flush();
readfile($filename);
die();
?>