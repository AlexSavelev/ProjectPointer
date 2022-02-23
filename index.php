<!DOCTYPE html>
<html>
<head>
	<title>ProjectPointer</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Конструктор проектов ProjectPointer">
	<meta name="description" content="Создайте свой проект быстро и легко, используя сервис ProjectPointer.">
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
	<!-- TOP -->
	<section class="section-white section-mg-mid">
		<h2 class="page-big-title">Создайте свой проект</h2>
		<div class="double-button-box">
			<a href="/my/new.php"><div class="button-box black-button horisontally-box">Создать проект</div></a>
			<a href="/knowledge"><div class="button-box white-button horisontally-box">База знаний</div></a>
		</div>
	</section>
	<!-- INST -->
	<section class="section-gray"><div class="section-content">
		<div class="section-content-title-box"><h3 class="section-content-title">Project Pointer</h3></div>
		<div class="section-content-image-box"><img class="section-content-image" src="/styles/img/new-200.png"></div>
		<div class="section-content-paragraph-box">
			<p class="section-content-paragraph">#01 Создайте проект</p>
			<p class="section-content-paragraph">Свой проект можно создать в личном кабинете после регистрации/авторизации</p>
		</div>
	</div></section>
	<section class="section-gray"><div class="section-content">
		<div class="section-content-title-box"></div>
		<div class="section-content-image-box"><img class="section-content-image" src="/styles/img/sequence.png"></div>
		<div class="section-content-paragraph-box">
			<p class="section-content-paragraph">#02 Разбейте на главы (подглавы)</p>
			<p class="section-content-paragraph">ProjectPointer разделяет проект на разделы</p>
			<p class="section-content-paragraph">Каждый раздел содержит уникальный набор данных: текст, изображения и файлы</p>
		</div>
	</div></section>
	<section class="section-gray"><div class="section-content">
		<div class="section-content-title-box"></div>
		<div class="section-content-image-box"><img class="section-content-image" src="/styles/img/file.png"></div>
		<div class="section-content-paragraph-box">
			<p class="section-content-paragraph">#03 Загружайте все, что необходимо, в любом формате</p>
			<p class="section-content-paragraph">Изображения будут импортированы по умолчанию</p>
			<p class="section-content-paragraph">Аудио и видеофайлы, документы из других источников, включенные в проект, содержат QR-код, отсылающий к ним</p>
		</div>
	</div></section>
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