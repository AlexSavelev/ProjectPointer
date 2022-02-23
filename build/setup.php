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
	<section class="section-white section-mg-big">
		<!-- <h2 class="page-big-title">Конструктор</h2> -->
		<div class="p-form-box">
			<form class="build-settings" action="build.php" method="post">
				<?php
					if(!empty($_GET["error"])) {
						echo '<div class="p-form-elem">
								<div class="p-form-error">'.htmlspecialchars($_GET["error"]).'</div>
							</div>';
					}
				?>
				<div class="p-form-elem">
					<label for="margin-l">Отступ страницы слева (мм)</label>
					<input type="number" name="margin-l" id="margin-l" min="0" max="100" value="30" required>
				</div>
				<div class="p-form-elem">
					<label for="margin-r">Отступ страницы справа (мм)</label>
					<input type="number" name="margin-r" id="margin-r" min="0" max="100" value="15" required>
				</div>
				<div class="p-form-elem">
					<label for="margin-l">Отступ страницы сверху (мм)</label>
					<input type="number" name="margin-t" id="margin-t" min="0" max="100" value="20" required>
				</div>
				<div class="p-form-elem">
					<label for="margin-l">Отступ страницы снизу (мм)</label>
					<input type="number" name="margin-b" id="margin-b" min="0" max="100" value="20" required>
				</div>
				
				<hr><br>
				
				<div class="p-form-elem">
					<label for="base-indent">Отступ абзаца (мм)</label>
					<input type="number" name="base-indent" id="base-indent" min="0" max="100" step="0.5" value="12.5" required>
				</div>
				<div class="p-form-elem">
					<label for="base-lh">Интервал</label>
					<input type="number" name="base-lh" id="base-lh" min="0.5" max="5" step="0.05" value="1.15" required>
				</div>
				
				<hr><br>
				
				<div class="p-form-elem">
					<label for="rt-placement">Расположение колонтитула</label>
				    <select name="rt-placement" id="rt-placement">
				        <option value="tR">Сверху справа</option>
				        <option value="tL">Сверху слева</option>
				        <option value="bR" selected>Снизу справа</option>
				        <option value="bL">Снизу слева</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="rt-fs">Стиль шрифта колонтитула: Обычный, <b>Жирный</b>, <i>Курсив</i></label>
				    <select name="rt-fs" id="rt-fs">
				        <option value="" selected>Обычный</option>
				        <option value="B">Жирный</option>
				        <option value="I">Курсив</option>
				    </select>
				</div>
				
				<hr><br>
				
				<div class="p-form-elem">
					<label for="base-font">Основной текст: Шрифт</label>
				    <select name="base-font" id="base-font">
				        <option value="arial">Arial</option>
				        <option value="bahnschrift">Bahnschrift</option>
				        <option value="calibri">Calibri</option>
				        <option value="georgia">Georgia</option>
				        <option value="micross">SansSerif</option>
				        <option value="timesnewroman" selected>TimesNewRoman</option>
				        <option value="verdana">Verdana</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="base-style">Основной текст: Стиль шрифта: Обычный, <b>Жирный</b>, <i>Курсив</i></label>
				    <select name="base-style" id="base-style">
				        <option value="" selected>Обычный</option>
				        <option value="B">Жирный</option>
				        <option value="I">Курсив</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="base-size">Основной текст: Размер шрифта</label>
					<input type="number" name="base-size" id="base-size" min="1" max="30" step="0.5" value="13" required>
				</div>
				
				<hr><br>
				
				<div class="p-form-elem">
					<label for="title-font">Шрифт оглавления</label>
				    <select name="title-font" id="title-font">
				        <option value="arial">Arial</option>
				        <option value="bahnschrift">Bahnschrift</option>
				        <option value="calibri">Calibri</option>
				        <option value="georgia">Georgia</option>
				        <option value="micross">SansSerif</option>
				        <option value="timesnewroman" selected>TimesNewRoman</option>
				        <option value="verdana">Verdana</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="title-style">Стиль шрифта оглавления: Обычный, <b>Жирный</b>, <i>Курсив</i></label>
				    <select name="title-style" id="title-style">
				        <option value="">Обычный</option>
				        <option value="B" selected>Жирный</option>
				        <option value="I">Курсив</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="title-size">Размер шрифта оглавления</label>
					<input type="number" name="title-size" id="title-size" min="1" max="30" step="0.5" value="14" required>
				</div>
				
				<hr><br>
				
				<div class="p-form-elem">
					<label for="author-font">Шрифт авторства</label>
				    <select name="author-font" id="author-font">
				        <option value="arial">Arial</option>
				        <option value="bahnschrift">Bahnschrift</option>
				        <option value="calibri">Calibri</option>
				        <option value="georgia">Georgia</option>
				        <option value="micross">SansSerif</option>
				        <option value="timesnewroman" selected>TimesNewRoman</option>
				        <option value="verdana">Verdana</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="author-style">Стиль шрифта авторства: Обычный, <b>Жирный</b>, <i>Курсив</i></label>
				    <select name="author-style" id="author-style">
				        <option value="" selected>Обычный</option>
				        <option value="B">Жирный</option>
				        <option value="I">Курсив</option>
				    </select>
				</div>
				<div class="p-form-elem">
					<label for="author-size">Размер шрифта авторства</label>
					<input type="number" name="author-size" id="author-size" min="1" max="30" step="0.5" value="13" required>
				</div>
				
				<hr><br>
				
				<?php
					echo '<input type="hidden" name="p" value="'.$project_ref.'">';
				?>
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Собрать проект">
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