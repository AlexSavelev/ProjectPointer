<!DOCTYPE html>
<html>
<head>
	<title>Авторизация</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Авторизация — ProjectPointer">
	<meta name="description" content="Войти в систему ProjectPointer">
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
	<section class="section-white section-mg-big">
		<h2 class="page-big-title">Авторизация</h2>
		<div class="lr-form-box">
			<form class="login-form" action="login-result.php" method="post">
				<?php
				if(!empty($_GET["error"])) {
					echo '<div class="lr-form-elem"><div class="lr-form-error">Данные указаны неверно</div></div>';
				}
				?>
				<div class="lr-form-elem">
					<label for="email">Эл. почта</label>
					<input type="email" name="email" id="email" maxlength="200" required>
				</div>
				<div class="lr-form-elem">
					<label for="password">Пароль&emsp;<a class="lr-form-elem-a-clean" href="change-password.php?page=login">Забыли пароль?</a></label>
					<input type="password" name="password" id="password" maxlength="120" required>
				</div>
				<div class="lr-form-elem lr-form-checkbox">
					<label for="sysstay">
						<input type="checkbox" name="sysstay" id="sysstay" value="1" Checked>Оставаться в системе
					</label>
				</div>
				<div class="lr-form-elem">
					<input type="submit" name="submit" value="Войти">
				</div>
				<div class="lr-form-elem">
					<a href="/profile/register.php">Нет аккаунта? Зарегистрироваться</a>
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