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

$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$name_plus = filter_var(trim($_POST['name_plus']), FILTER_SANITIZE_STRING);
$content = base64_encode($_POST['content']);

if(mb_strlen($name) < 1 || mb_strlen($name) > 110){
	header('Location: index.php?&article-error=Недопустимая%20длина%20названия');
	exit;
} else if(mb_strlen($name_plus) > 110){
	header('Location: index.php?&article-error=Недопустимая%20длина%20содержания');
	exit;
} else if(mb_strlen($content) < 1 || mb_strlen($content) > 60000){
	header('Location: index.php?&article-error=Недопустимая%20длина%20содержания');
	exit;
}

if($name_plus=='') {
    $name_plus = $name;
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
$result = $mysql->query("SELECT * FROM articles WHERE name='$name'");
if (!$result || mysqli_num_rows($result) == 0) {
    $mysql->query("INSERT INTO `articles` (`name`, `content`) VALUES ('$name', '$content')");
    $article_id = $mysql->insert_id;
} else {
    $article_id = mysqli_fetch_assoc($result)['ID'];
    $mysql->query("UPDATE `articles` SET `name` = '$name_plus', `content` = '$content' WHERE `ID` = '$article_id'");
}
$mysql->close();

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/knowledge/?article='.$article_id);
exit();
?>