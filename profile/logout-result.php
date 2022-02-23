<?php
if (!empty($_COOKIE["user_id"])) {
	unset($_COOKIE['user_id']); 
    setcookie('user_id', null, -1, '/'); 
}
$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
header('Location: '.$actual_header_link);
exit();
?>