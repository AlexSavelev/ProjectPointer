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
		$name = $row['name'];
		$email = $row['email'];
	}
} else {
	$mysql->close();
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
	header('Location: '.$actual_header_link);
	exit();
}
$mysql->close();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Мой профиль</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Мой профиль — ProjectPointer">
	<meta name="description" content="Посмотреть и редактировать данные своего профиля">
	<link rel="stylesheet" type="text/css" href="/styles/base.css">
	<link rel="icon" type="image/png" href="/favicon.ico"/>
	<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(86272886, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/86272886" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</head>
<body>
<header><div class="hf-main">
	<div class="hf-elem"><h1 class="header-site-name"><a href="/">ProjectPointer</a></h1></div>
	<div class="hf-elem header-menu-div">
		<ul class="header-menu">
			<li><a href="/my">Мои проекты</a></li>
			<li><a href="/community">Все проекты</a></li>
			<li><a href="/profile">Мой профиль</a></li>
		</ul>
	</div>
</div></header>
<main>
	<section class="section-white">
		<h2 class="page-big-title">Мой профиль</h2>
		<div class="p-form-box">
			<?php
				if(!empty($_POST['edit']) and $_POST['edit'] == "1") {
					// Edit
					echo '<form class="edit-profile" action="edit-profile-result.php" method="post">';
					if(!empty($_GET["error"])) {
						echo '<div class="lr-form-elem"><div class="lr-form-error">'.htmlspecialchars($_GET["error"]).'</div></div>';
					}
					echo '<div class="lr-form-elem">
						<label for="name">Действующее имя</label>';
					echo '<input type="text" name="name" id="name" maxlength="40" required>';
					echo '</div>
						<div class="lr-form-elem">
						<label for="email">Действующая эл. почта</label>';
					echo '<input type="email" name="email" id="email" maxlength="100" required>';
					echo '</div>
						<div class="lr-form-elem">
						<label for="old-password">Старый пароль (подтвержение)</label>';
					echo '<input type="password" name="old-password" id="old-password" maxlength="60" required>';
					echo '</div>
						<div class="lr-form-elem">
						<label for="new-password">Новый пароль</label>';
					echo '<input type="password" name="new-password" id="new-password" maxlength="60" required>';
					echo '</div>
						<div class="lr-form-elem">
							<input type="submit" name="submit" value="Применить">
						</div>
						</form>';
				} else {
					// Base
					echo '<form class="view-profile" action="index.php" method="post">';
					if(!empty($_GET["error"])) {
						echo '<div class="lr-form-elem"><div class="lr-form-error">'.htmlspecialchars($_GET["error"]).'</div></div>';
					}
					if(!empty($_GET["success"])) {
						echo '<div class="lr-form-elem"><div class="lr-form-success">'.htmlspecialchars($_GET["success"]).'</div></div>';
					}
					echo '<div class="lr-form-elem">
						<label for="name">Имя</label>';
					echo '<input type="text" name="name" id="name" maxlength="40" value="'.$name.'" readonly>';
					echo '</div>
						<div class="lr-form-elem">
						<label for="email">Эл. почта</label>';
					echo '<input type="email" name="email" id="email" maxlength="100" value="'.$email.'" readonly>';
					echo '</div>
						<div class="lr-form-elem">
						<label for="password">Пароль&emsp;<a class="lr-form-elem-a-clean" href="change-password.php?page=index">Забыли пароль?</a></label>';
					echo '<input type="text" name="password" id="password" maxlength="60" value="СКРЫТ" readonly>';
					echo '</div>';
					echo '<input type="hidden" name="edit" id="edit" value="1">';
					echo '<div class="lr-form-elem">
							<input type="submit" name="submit" value="Редактировать данные">
						</div>
						</form>';
					
					echo '<form class="logout-profile" action="logout-result.php" method="post">';
					echo '<div class="lr-form-elem">
							<input type="submit" class="lr-form-elem-remove" name="submit" value="Выйти">
						</div>
						</form>';
				}
			?>
		</div>
	</section>
</main>
<footer><div class="hf-main">
	<div class="hf-elem"><p class="footer-site-name">2022 ProjectPointer </p></div>
	<div class="hf-elem footer-menu-div">
		<ul class="footer-menu">
			<li><a href="/extra/about.php" class="footer-menu-elem">О проекте</a></li>
			<li><a href="/extra/feedback.php" class="footer-menu-elem">Обратная связь</a></li>
		</ul>
	</div>
</div></footer>
</body>
</html>