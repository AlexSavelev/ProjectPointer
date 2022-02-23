<?php
include '../base.php';

$email = strtolower(filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING));
$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);

$password = md5($password);

$result = $mysql->query("SELECT * FROM users WHERE email='$email' AND password='$password'");

if (!$result || mysqli_num_rows($result) == 0) {
	$mysql->close();
	header('Location: login.php?error=Неправильное%20имя%20пользователя%20или%20пароль');
	exit;
} else {
	if(!empty($_POST['sysstay'])) {
		while($row = mysqli_fetch_assoc($result)) {
			setcookie("user_id", $row['referral'], time()+60*60*24*30, '/');
		}
	} else {
		while($row = mysqli_fetch_assoc($result)) {
			setcookie("user_id", $row['referral'], 0, '/');
		}
	}
	$mysql->close();
	if (empty($_COOKIE["backpage"])) {
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
			$uri = 'https://';
		} else {
			$uri = 'http://';
		}
		$uri .= $_SERVER['HTTP_HOST'];
		header('Location: '.$uri);
		exit;
	} else {
		header('Location: '.$_COOKIE["backpage"]);
		exit;
	}
}

?>