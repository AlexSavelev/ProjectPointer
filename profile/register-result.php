<?php
include '../base.php';

function generate_string($strength = 25) {
	$input = '0123456789abcdefghijklmnopqrstuvwxyz';
	$input_length = strlen($input);
	$random_string = '';
	for($i = 0; $i < $strength; $i++) {
		$random_character = $input[mt_rand(0, $input_length - 1)];
		$random_string .= $random_character;
	}
	return $random_string;
}

$referral = generate_string();
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = strtolower(filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING));
$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
$code = filter_var(trim($_POST['code']), FILTER_SANITIZE_STRING);

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
$result = $mysql->query("SELECT * FROM confirm_email WHERE email='$email'");

if (!$result || mysqli_num_rows($result) == 0) {
    $mysql->close();
    header('Location: register.php?error=Неизвестная%20ошибка!%20Обратитесь%20в%20службу%20поддержки');
	exit;
}

$row = mysqli_fetch_assoc($result);

if(mb_strlen($code)!=6){
    $row_id = $row['ID'];
    $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
    $mysql->close();
    header('Location: register.php?error=Недопустимая%20длина%20кода');
    exit;
} else if(mb_strlen($password) < 1 || mb_strlen($password) > 120){
    $row_id = $row['ID'];
    $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
    $mysql->close();
    header('Location: register.php?error=Недопустимая%20длина%20пароля');
    exit;
} else if(mb_strlen($name) < 1 || mb_strlen($name) > 80){
    $row_id = $row['ID'];
    $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
    $mysql->close();
    header('Location: register.php?error=Недопустимая%20длина%20имени');
    exit;
}
$password = md5($password);

$true_code = $row['code'];
if($code!=$true_code) {
    $row_id = $row['ID'];
    $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
    $mysql->close();
    header('Location: register.php?error=Неправильный%20код');
    exit;
}
	
$row_id = $row['ID'];
$mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");

$result = $mysql->query("SELECT * FROM users WHERE email='$email'");
if (!$result || mysqli_num_rows($result) == 0) {
	$mysql->query("INSERT INTO `users` (`email`, `password`, `name`, `referral`) VALUES ('$email', '$password', '$name', '$referral')");
	if(!empty($_POST['sysstay'])) {
		setcookie("user_id", $referral, time()+60*60*24*30, '/');
	} else {
		setcookie("user_id", $referral, 0, '/');
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
} else {
	# HAS ACCOUNT
	$mysql->close();
	header('Location: register.php?error=Неизвестная%20ошибка!%20Обратитесь%20в%20службу%20поддержки');
	exit;
}
?>