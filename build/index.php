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
?>


<!DOCTYPE html>
<html>
<head>
	<title>Конструктор проектов</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Конструктор — ProjectPointer">
	<meta name="description" content="">
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
    <?php
	if(empty($_GET["p"])) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		header('Location: '.$actual_header_link);
		exit();
	}
	$project_ref = $_GET["p"];

	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
	if (!$result || mysqli_num_rows($result) == 0) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		$mysql->close();
		header('Location: '.$actual_header_link);
		exit();
	} else {
		$owner = mysqli_fetch_assoc($result)['owner'];
	}
	$mysql->close();

	if($_COOKIE["user_id"] != $owner) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		header('Location: '.$actual_header_link);
		exit();
	}
	?>
    
	<!-- TOP -->
	<section class="section-white">
		<h2 class="page-big-title">Конструктор</h2>
	</section>
	<!-- INST -->
	<section class="section-gray"><div class="section-content">
		<div class="section-content-title-box"><h3 class="section-content-title">Подготовка</h3></div>
		<div class="section-content-image-box"><img class="section-content-image" src="/styles/img/checkbox.png"></div>
		<div class="section-content-paragraph-box">
			<p class="section-content-paragraph">#01 Убедитесь в:</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Заполнении паспорта проекта;</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Готовности текстового материала;</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Загруженности медиафайлов;</p>
		</div>
	</div></section>
	<section class="section-gray"><div class="section-content">
		<div class="section-content-title-box"></div>
		<div class="section-content-image-box"><img class="section-content-image" src="/styles/img/paste-img.png"></div>
		<div class="section-content-paragraph-box">
			<p class="section-content-paragraph">#02 Вставьте изображения / файлы в проект:</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Подпишите все загруженные медиафайлы;</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Чтобы включить медиафайл в проект, в тексте выберите место для него и впишите в фигурных скобках название медиафайла. Пример: {Диаграмма 1}</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Не забывайте: Каждый медиафайл распространяется исключительно в области главы;</p>
		</div>
	</div></section>
	<section class="section-gray"><div class="section-content">
		<div class="section-content-title-box"></div>
		<div class="section-content-image-box"><img class="section-content-image" src="/styles/img/file.png"></div>
		<div class="section-content-paragraph-box">
			<p class="section-content-paragraph">#03 Укажите параметры оформления:</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Перейдите по ссылке ниже и укажите все параметры оформления;</p>
			<p class="section-content-paragraph" style="margin-left: 25px;">&middot; Уже предустановленные настройки являются стандартом для оформления школьного итогового проекта;</p>
			<?php
			    echo '<div class="button-box white-button" style="margin-left: 15px;width:236px;"><a href="setup.php?p='.$project_ref.'">Перейти к следующему этапу</a></div>';
			?>
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