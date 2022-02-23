<?php
include '../base.php';

$email = strtolower(filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING));
$code = filter_var(trim($_POST['code']), FILTER_SANITIZE_STRING);
$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

$page = filter_var(trim($_POST['page']), FILTER_SANITIZE_STRING);

if(mb_strlen($email) < 1 || mb_strlen($email) > 100){
	header('Location: change-password.php?error=Недопустимая%20длина%20эл.%20почты');
	exit;
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
$result = $mysql->query("SELECT * FROM confirm_email WHERE email='$email'");


if (!$result || mysqli_num_rows($result) == 0) {
    $mysql->close();
    header('Location: change-password.php?error=Неизвестная%20ошибка!%20Обратитесь%20в%20службу%20поддержки');
	exit;
} else {
    $row = mysqli_fetch_assoc($result);
    
    if(mb_strlen($code)!=6){
        $row_id = $row['ID'];
        $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
        $mysql->close();
	    header('Location: change-password.php?error=Недопустимая%20длина%20кода');
	    exit;
    } else if(mb_strlen($password) < 1 || mb_strlen($password) > 120){
        $row_id = $row['ID'];
        $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
        $mysql->close();
	    header('Location: change-password.php?error=Недопустимая%20длина%20пароля');
	    exit;
    }
    $password = md5($password);
    
    $true_code = $row['code'];
	if($code!=$true_code) {
	    $row_id = $row['ID'];
        $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
        $mysql->close();
	    header('Location: change-password.php?error=Неправильный%20код');
	    exit;
	}
	
	$row_id = $row['ID'];
    $mysql->query("DELETE FROM `confirm_email` WHERE `ID` = '$row_id'");
    $mysql->query("UPDATE `users` SET `password` = '$password' WHERE `email`='$email'");
    
    $result = $mysql->query("SELECT * FROM users WHERE email='$email' AND password='$password'");
    if (!$result || mysqli_num_rows($result) == 0) {
	    $mysql->close();
	    header('Location: change-password.php?error=Неизвестная%20ошибка!%20Обратитесь%20в%20службу%20поддержки');
	    exit;
    } else {
        if (!empty($_COOKIE["user_id"])) {
	        unset($_COOKIE['user_id']); 
            setcookie('user_id', null, -1, '/'); 
        }
        
	    if(!empty($_POST['sysstay'])) {
	    	$row = mysqli_fetch_assoc($result);
	    	setcookie("user_id", $row['referral'], time()+60*60*24*30, '/');
	    } else {
	    	$row = mysqli_fetch_assoc($result);
	    	setcookie("user_id", $row['referral'], 0, '/');
	    }
    }
    $mysql->close();
    if($page=='index') {
        header('Location: index.php?success=Пароль%20успешно%20сброшен.%20Вы%20вошли%20в%20систему');
	    exit;
    } else {
        $actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	    header('Location: '.$actual_header_link);
	    exit;
    }
}
?>