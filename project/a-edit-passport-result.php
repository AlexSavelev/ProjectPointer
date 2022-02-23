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

if(!empty($_POST['public'])) {
	$public = 1;
} else {
	$public = 0;
}

$title = filter_var(trim($_POST['title']), FILTER_SANITIZE_STRING);
$author = filter_var(trim($_POST['author']), FILTER_SANITIZE_STRING);
$authorr = filter_var(trim($_POST['authorr']), FILTER_SANITIZE_STRING);
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
$place = filter_var(trim($_POST['place']), FILTER_SANITIZE_STRING);
$manager = filter_var(trim($_POST['manager']), FILTER_SANITIZE_STRING);
$managerr = filter_var(trim($_POST['managerr']), FILTER_SANITIZE_STRING);

$relevance = filter_var(trim($_POST['relevance']), FILTER_SANITIZE_STRING);
$novelty = filter_var(trim($_POST['novelty']), FILTER_SANITIZE_STRING);
$s_object = filter_var(trim($_POST['s_object']), FILTER_SANITIZE_STRING);
$s_subject = filter_var(trim($_POST['s_subject']), FILTER_SANITIZE_STRING);

$goal = filter_var(trim($_POST['goal']), FILTER_SANITIZE_STRING);
$tasks = filter_var(trim($_POST['tasks']), FILTER_SANITIZE_STRING);
$question = filter_var(trim($_POST['question']), FILTER_SANITIZE_STRING);
$product = filter_var(trim($_POST['product']), FILTER_SANITIZE_STRING);
$summary = filter_var(trim($_POST['summary']), FILTER_SANITIZE_STRING);

if(mb_strlen($title) < 1 || mb_strlen($title) > 200){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20заголовка');
	exit;
} else if(mb_strlen($author) > 120){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20автора');
	exit;
} else if(mb_strlen($authorr) > 80){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20статуса%20автора');
	exit;
} else if(mb_strlen($subject) < 1 || mb_strlen($subject) > 40){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20предмета');
	exit;
} else if(mb_strlen($tags) > 70){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20направленности');
	exit;
} else if(mb_strlen($place) > 300){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20образовательной%20организации');
	exit;
} else if(mb_strlen($manager) > 200){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20руководителя');
	exit;
} else if(mb_strlen($managerr) > 100){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20статуса%20руководителя');
	exit;
} else if(mb_strlen($relevance) > 1400){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20актуальности');
	exit;
} else if(mb_strlen($novelty) > 1400){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20новизны');
	exit;
} else if(mb_strlen($s_object) > 300){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20объекта%20исследования');
	exit;
} else if(mb_strlen($s_subject) > 300){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20предмета%20исследования');
	exit;
} else if(mb_strlen($goal) > 1000){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20цели');
	exit;
} else if(mb_strlen($tasks) > 2000){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20задач');
	exit;
} else if(mb_strlen($question) > 1000){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20вопроса%20проекта');
	exit;
} else if(mb_strlen($product) > 1000){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20результата');
	exit;
} else if(mb_strlen($summary) > 6000){
	header('Location: a-edit-passport.php?p='.$project_ref.'&error=Недопустимая%20длина%20краткого%20содержания');
	exit;
}

$mysql->query("UPDATE `projects` SET `public` = '$public', `title` = '$title', `author` = '$author', `authorr` = '$authorr', `subject` = '$subject', `tags` = '$tags', `place` = '$place', `manager` = '$manager', `managerr` = '$managerr', `relevance` = '$relevance', `novelty` = '$novelty', `s_object` = '$s_object', `s_subject` = '$s_subject', `goal` = '$goal', `tasks` = '$tasks', `question` = '$question', `product` = '$product', `summary` = '$summary' WHERE `referral` = '$project_ref'");

$mysql->close();

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/project?p='.$project_ref);
exit();
?>