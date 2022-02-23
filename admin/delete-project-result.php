<?php
include '../base.php';

function check_user_id() {
	include '../base.php';
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	if (!empty($_COOKIE["user_id"])) {
		$result = $mysql->query("SELECT * FROM admins WHERE user='{$_COOKIE["user_id"]}'");
		if (!$result || mysqli_num_rows($result) == 0) {
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
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	header('Location: '.$actual_header_link);
	exit();
}

$project_ref = filter_var(trim($_POST['p']), FILTER_SANITIZE_STRING);

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);

$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
if (!$result || mysqli_num_rows($result) == 0) {
	$mysql->close();
	header('Location: index.php?delete-error=Проект%20не%20найден%20в%20базе%20данных');
	exit();
}

$mysql->query("DELETE FROM `projects` WHERE `referral` = '$project_ref'");
$mysql->close();

$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
$mysql->query("DROP TABLE `$project_ref`");
$mysql->close();

$folder_path = "../media/".$project_ref;
$files = glob($folder_path.'/*'); 
foreach($files as $file) {
	if(is_file($file)) {
		unlink($file); 
	}
}
rmdir($folder_path);

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/admin');
exit();
?>