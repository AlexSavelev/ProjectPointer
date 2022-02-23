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

$project_ref = filter_var(trim($_POST['p']), FILTER_SANITIZE_STRING);
$section_id = filter_var(trim($_POST['s']), FILTER_SANITIZE_STRING);
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

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];


if($section_id == 'ATTACHMENTS') {
    $actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	header('Location: '.$actual_header_link);
	exit();
}

if($section_id == 'INTRO' or $section_id == 'OUTRO' or $section_id == 'LITERATURE') {
    $isIO = true;
    $content = filter_var(trim($_POST['content']), FILTER_SANITIZE_STRING);
    if($section_id == 'INTRO') {
        if($_POST['tags']=="Другой"){
            $tags = filter_var(trim($_POST['tags_plus']), FILTER_SANITIZE_STRING);
        } else {
            $tags = filter_var(trim($_POST['tags']), FILTER_SANITIZE_STRING);
        }
        $relevance = filter_var(trim($_POST['relevance']), FILTER_SANITIZE_STRING);
        $novelty = filter_var(trim($_POST['novelty']), FILTER_SANITIZE_STRING);
        $s_object = filter_var(trim($_POST['s_object']), FILTER_SANITIZE_STRING);
        $s_subject = filter_var(trim($_POST['s_subject']), FILTER_SANITIZE_STRING);
        $goal = filter_var(trim($_POST['goal']), FILTER_SANITIZE_STRING);
        $tasks = filter_var(trim($_POST['tasks']), FILTER_SANITIZE_STRING);
        $question = filter_var(trim($_POST['question']), FILTER_SANITIZE_STRING);
        $product = filter_var(trim($_POST['product']), FILTER_SANITIZE_STRING);
        $summary = filter_var(trim($_POST['summary']), FILTER_SANITIZE_STRING);
        
        if(mb_strlen($tags) > 70 || mb_strlen($relevance) > 1400 || mb_strlen($novelty) > 1400 || mb_strlen($s_object) > 300 || mb_strlen($s_subject) > 300 || mb_strlen($goal) > 1000 || mb_strlen($tasks) > 2000 || mb_strlen($question) > 1000 || mb_strlen($product) > 1000 || mb_strlen($summary) > 6000){
	        header('Location: a-edit-section.php?p='.$project_ref.'&s='.$section_id.'&error=Недопустимая%20длина%20одного%20из%20параметров');
	        exit;
        }
        
        $mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
        $mysql->query("UPDATE projects SET `tags` = '$tags', `relevance` = '$relevance', `novelty` = '$novelty', `s_object` = '$s_object', `s_subject` = '$s_subject', `goal` = '$goal', `tasks` = '$tasks', `question` = '$question', `product` = '$product', `summary` = '$summary' WHERE `referral`='$project_ref'");
        $mysql->close();
    }
} else {
    $isIO = false;
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    if(mb_strlen($name) < 1 || mb_strlen($name) > 200) {
	    header('Location: a-edit-section.php?p='.$project_ref.'&s='.$section_id.'&error=Недопустимая%20длина%20заголовка');
	    exit;
    }
    if(!empty($_POST['single'])) {
        $content = "__TRUE__";
    } else {
        $content = filter_var(trim($_POST['content']), FILTER_SANITIZE_STRING);
        if($content == "__TRUE__") {
            header('Location: a-edit-section.php?p='.$project_ref.'&s='.$section_id.'&error=Недопустимое%20значение%20содержания');
	        exit;
        }
    }
}

if(mb_strlen($content) > 10000) {
	header('Location: a-edit-section.php?p='.$project_ref.'&s='.$section_id.'&error=Недопустимая%20длина%20содержания');
	exit;
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
if($isIO) {
    $mysql->query("UPDATE $project_ref SET `content` = '$content' WHERE `type`='SECTION' AND `place`='$section_id'");
} else {
    $mysql->query("UPDATE $project_ref SET `name` = '$name', `content` = '$content' WHERE `type`='SECTION' AND `place`='$section_id'");
}
$mysql->close();

header('Location: '.$uri.'/project/viewer.php?p='.$project_ref.'&s='.$section_id);
exit();
?>