<?php
include '../base.php';

$name = "";
$email = "";

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
if (!empty($_COOKIE["user_id"])) {
	$result = $mysql->query("SELECT * FROM users WHERE referral='{$_COOKIE["user_id"]}'");
	if (!$result || mysqli_num_rows($result) == 0) {
		setcookie("user_id", time() - 3600);
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
		$mysql->close();
		header('Location: '.$actual_header_link);
		exit();
	} else {
		while($row = mysqli_fetch_assoc($result)) {
			$name = $row['name'];
			$email = $row['email'];
		}
	}
}
$mysql->close();

?>
<!DOCTYPE html>
<html>
<head>
	<title>Оставить отзыв</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Обратная связь — ProjectPointer">
	<meta name="description" content="Здесь вы можете оставить свой отзыв, пожелание или задать вопрос">
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
			<?php
    		if (!empty($_COOKIE["user_id"])) {
    			echo '<li><a href="/my">Мои проекты</a></li>
					<li><a href="/community">Все проекты</a></li>
					<li><a href="/profile">Мой профиль</a></li>';
    		} else {
    			echo '<li><a href="/community">Все проекты</a></li>
					<li><a href="/profile/login.php" class="login-register-ref">Войти</a></li>
					<li><a href="/profile/register.php" class="login-register-ref">Зарегистрироваться</a></li>';
    		}
    		?>
		</ul>
	</div>
</div></header>
<main>
	<section class="section-white">
		<h2 class="page-big-title">Оставить отзыв,<br>пожелание или задать вопрос</h2>
		<div class="lr-form-box">
			<form class="feedback-form" action="feedback-result.php" method="post">
				<?php
				if(!empty($_GET["error"])) {
					echo '<div class="lr-form-elem"><div class="lr-form-error">'.htmlspecialchars($_GET["error"]).'</div></div>';
				}
				?>
				<div class="lr-form-elem">
					<label for="name">Имя</label>
					<?php
						echo '<input type="text" name="name" id="name" maxlength="40" value="'.$name.'" required>';
					?>
				</div>
				<div class="lr-form-elem">
					<label for="email">Эл. почта</label>
					<?php
						echo '<input type="email" name="email" id="email" maxlength="100" value="'.$email.'" required>';
					?>
				</div>
				<div class="lr-form-elem">
					<label for="content">Текст</label>
					<textarea rows="4" name="content" id="content" minlength="10" maxlength="500" required></textarea>
				</div>
				<div class="lr-form-elem">
					<input type="submit" name="submit" value="Отправить">
				</div>
			</form>
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