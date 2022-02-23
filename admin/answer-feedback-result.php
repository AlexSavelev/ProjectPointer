<?php
include '../base.php';
include '../base_mail.php';

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

$id = filter_var(trim($_POST['id']), FILTER_SANITIZE_STRING);
$answer = filter_var(trim($_POST['answer']), FILTER_SANITIZE_STRING);

if(mb_strlen($answer) < 10 || mb_strlen($answer) > 1000){
	header('Location: index.php?&answer-error=Недопустимая%20длина%20ответа');
	exit;
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
$mysql->query("UPDATE `feedbacks` SET `answer` = '$answer' WHERE `ID` = '$id'");
$mysql->close();


$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
$answer = wordwrap($answer, 70, "\r\n");
$subject = 'Ответ на Ваш отзыв';
mail($email, $subject, $answer, $mail_headers);


if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/admin');
exit();
?>