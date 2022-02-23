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
$owner = $_COOKIE["user_id"];
$title = filter_var(trim($_POST['title']), FILTER_SANITIZE_STRING);
if($_POST['subject']=="Другое"){
    $subject = filter_var(trim($_POST['subject_plus']), FILTER_SANITIZE_STRING);
} else {
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
}
if($_POST['tags']=="Другой"){
    $tags = filter_var(trim($_POST['tags_plus']), FILTER_SANITIZE_STRING);
} else {
    $tags = filter_var(trim($_POST['tags']), FILTER_SANITIZE_STRING);
}

if(mb_strlen($subject) < 1 || mb_strlen($subject) > 40){
	header('Location: new.php?error=Недопустимая%20длина%20предмета');
	exit();
} else if(mb_strlen($title) < 1 || mb_strlen($title) > 190){
	header('Location: new.php?error=Недопустимая%20длина%20заголовка');
	exit();
} else if(mb_strlen($tags) > 70){
	header('Location: new.php?error=Недопустимая%20длина%20направленности');
	exit();
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);

// LIMIT
$result = $mysql->query("SELECT * FROM projects WHERE owner='$owner'");
if (!$result) {} else if(mysqli_num_rows($result) >= $limit_projects) {
	$mysql->close();
	header('Location: new.php?error=Вы%20превысили%20лимит%20на%20создание%20проектов%20('.$limit_projects.')');
	exit();
}

$mysql->query("INSERT INTO `projects` (`referral`, `owner`, `title`, `subject`, `tags`) VALUES ('$referral', '$owner', '$title', '$subject', '$tags')");
$mysql->close();

$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
$table_c = "CREATE TABLE `$db_projects`.`$referral` 
	( `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT ,
	`type` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
	`place` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
	`name` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
	`content` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
	PRIMARY KEY (`ID`)) ENGINE = InnoDB;";
$mysql->query($table_c);

// Intro, outro, literature, attachments
$mysql->query("INSERT INTO `$referral` (`type`, `place`, `name`) VALUES ('SECTION', 'INTRO', 'Введение')");
$mysql->query("INSERT INTO `$referral` (`type`, `place`, `name`) VALUES ('SECTION', 'OUTRO', 'Заключение')");
$mysql->query("INSERT INTO `$referral` (`type`, `place`, `name`) VALUES ('SECTION', 'LITERATURE', 'Список литературы')");
$mysql->query("INSERT INTO `$referral` (`type`, `place`, `name`, `content`) VALUES ('SECTION', 'ATTACHMENTS', 'Приложения', '__TRUE__')");

$mysql->close();

$path = "../media/".$referral;
if (!file_exists($path)) {
	mkdir($path, 0755);
}

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/project?p='.$referral);
exit();
?>