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

$project_ref = filter_var(trim($_POST['p']), FILTER_SANITIZE_STRING);
$section_id = filter_var(trim($_POST['s']), FILTER_SANITIZE_STRING);
$file_id = filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING);

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

$mysql->close();

if($owner!=$trueOwner) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	header('Location: '.$actual_header_link);
	exit();
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);

// DELETE FILE
$result = $mysql->query("SELECT * FROM `$project_ref` WHERE `ID` = '$file_id' AND `place` = '$section_id'");
if (!$result || mysqli_num_rows($result) == 0) {} else {
	while($row = mysqli_fetch_assoc($result)) {
		$fname = '../media/'.$project_ref.'/'.$row['content'];
		if(is_file($fname)) {
			unlink($fname); 
		}
		if($row['type']=='M_QR') {
			$fname_qr = '../media/'.$project_ref.'/QR_'.$row['content'].'.png';
			if(is_file($fname_qr)) {
				unlink($fname_qr);
			}
		}
	}
}

$mysql->query("DELETE FROM `$project_ref` WHERE `ID` = '$file_id' AND `place` = '$section_id'");

$mysql->close();

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/project/a-edit-section-file.php?p='.$project_ref.'&s='.$section_id);
exit();
?>