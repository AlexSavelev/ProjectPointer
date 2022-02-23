<?php
include '../base.php';

$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$email = strtolower(filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING));
$content = filter_var(trim($_POST['content']), FILTER_SANITIZE_STRING);

if(mb_strlen($name) < 1 || mb_strlen($name) > 80){
	header('Location: feedback.php?error=Недопустимая%20длина%20имени');
	exit;
} else if(mb_strlen($email) < 1 || mb_strlen($email) > 100){
	header('Location: feedback.php?error=Недопустимая%20длина%20эл.%20почты');
	exit;
} else if(mb_strlen($content) < 10 || mb_strlen($content) > 1000){
	header('Location: feedback.php?error=Недопустимая%20длина%20отзыва');
	exit;
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);

$result = $mysql->query("SELECT * FROM feedbacks WHERE content='$content'");
if (!$result || mysqli_num_rows($result) == 0) {} else {
	$mysql->close();
	header('Location: feedback.php?error=Такой%20отзыв%20уже%20был%20отправлен');
	exit;
}

$mysql->query("INSERT INTO `feedbacks` (`name`, `email`, `content`) VALUES ('$name', '$email', '$content')");

$mysql->close();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Оставить отзыв</title>
	<meta charset="UTF-8" />
	<meta name="title" content="ProjectPointer - сделай свой проект">
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
	<section class="section-white section-mg-mid">
		<h2 class="page-big-title">Спасибо за отзыв!</h2>
	</section>
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
			<p class="section-content-paragraph">#02 Разбейте на разделы</p>
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
			<p class="section-content-paragraph">Файлы на электронных носителях, включенные в проект, содержат QR-код, отсылающий к ним</p>
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