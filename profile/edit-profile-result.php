<?php
include '../base.php';

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
if (!empty($_COOKIE["user_id"])) {
	$result = $mysql->query("SELECT * FROM users WHERE referral='{$_COOKIE["user_id"]}'");
	if (!$result || mysqli_num_rows($result) == 0) {
		setcookie("user_id", time() - 3600);
		$mysql->close();
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
		header('Location: '.$actual_header_link);
		exit();
	} else {
		$row = mysqli_fetch_assoc($result);
		$true_password = $row['password'];
		$referral = $row['referral'];
	}
} else {
	$mysql->close();
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
	header('Location: '.$actual_header_link);
	exit();
}

$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = strtolower(filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING));
$old_password = filter_var(trim($_POST['old-password']), FILTER_SANITIZE_STRING);
$password = filter_var(trim($_POST['new-password']), FILTER_SANITIZE_STRING);

if(mb_strlen($name) < 1 || mb_strlen($name) > 80){
	header('Location: register.php?error=Недопустимая%20длина%20имени');
	exit();
} else if(mb_strlen($email) < 1 || mb_strlen($email) > 200){
	header('Location: register.php?error=Недопустимая%20длина%20эл.%20почты');
	exit();
} else if(mb_strlen($password) < 1 || mb_strlen($password) > 120){
	header('Location: register.php?error=Недопустимая%20длина%20пароля');
	exit();
}

$old_password = md5($old_password);
$password = md5($password);

if($old_password!=$true_password) {
	$mysql->close();
	header('Location: index.php?error=Старый%20пароль%20введен%20неверно');
	exit();
}

$mysql->query("UPDATE `users` SET `name` = '$name', `email` = '$email', `password` = '$password' WHERE `referral` = '$referral'");

$mysql->close();
header('Location: index.php?success=Данные%20успешно%20обновлены');
exit();
?>