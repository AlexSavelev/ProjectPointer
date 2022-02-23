<?php
include '../base.php';

function check_user_id() {
	include '../base.php';
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	if (!empty($_COOKIE["user_id"])) {
		$result = $mysql->query("SELECT * FROM users WHERE referral='{$_COOKIE["user_id"]}'");
		if (!$result || mysqli_num_rows($result) == 0) {
			setcookie("user_id", time() - 3600);
			$mysql->close();
			return False;
		} else {
			$mysql->close();
			return True;
		}
	} else {
		$mysql->close();
		return False;
	}
}

if(!check_user_id()) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
	header('Location: '.$actual_header_link);
	exit();
}

if(empty($_GET["p"]) or empty($_GET["s"])) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	header('Location: '.$actual_header_link);
	exit();
}
		
$project_ref = $_GET["p"];
$section_id = $_GET["s"];
$owner = $_COOKIE["user_id"];

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);

$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
if (!$result || mysqli_num_rows($result) == 0) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	while($row = mysqli_fetch_assoc($result)) {
		$trueOwner = $row['owner'];
	}
}
if($owner!=$trueOwner) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
}
$mysql->close();

$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
$result = $mysql->query("SELECT * FROM `$project_ref` WHERE place='$section_id' AND type='TEXT'");
if (!$result) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	$t_count = mysqli_num_rows($result);
}

if($t_count >= $limit_texts) {
	$mysql->close();
	header('Location: index.php?p='.$project_ref.'&s='.$section_id);
	exit();
}

$mysql->query("INSERT INTO `$project_ref` (`type`, `place`) VALUES ('TEXT', '$section_id')");
$result = $mysql->query("SELECT * FROM `$project_ref` WHERE place='$section_id' AND type='TEXT'");
if (!$result) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	while($row = mysqli_fetch_assoc($result)) {
		$tid = $row['ID'];
	}
}

$mysql->close();

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/project/a-edit-text.php?p='.$project_ref.'&t='.$tid);
exit();
?>