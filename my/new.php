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
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my/new.php";
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
	setcookie("backpage", $actual_link, 0, '/');
	header('Location: '.$actual_header_link);
	exit();
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Новый проект</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Создать проект — ProjectPointer">
	<meta name="description" content="">
	<link rel="stylesheet" type="text/css" href="/styles/base.css">
	<link rel="icon" type="image/png" href="/favicon.ico"/>
	<script type="text/javascript">
	    function subject_update(){
	        if(document.getElementById('subject').value == "Другое") {
	            document.getElementById('subject_plus_box').style.display = "block";
            } else {
                 document.getElementById('subject_plus_box').style.display = "none";
            }
	    }
	    function tags_update(){
	        if(document.getElementById('tags').value == "Другой") {
	            document.getElementById('tags_plus_box').style.display = "block";
            } else {
                 document.getElementById('tags_plus_box').style.display = "none";
            }
	    }
	</script>
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
	<section class="section-white section-mg-big">
		<h2 class="page-big-title">Создать проект</h2>
		<div class="p-form-box">
			<form class="new-project-form" action="new-result.php" method="post">
				<?php
				if(!empty($_GET["error"])) {
					echo '<div class="p-form-elem"><div class="p-form-error">'.htmlspecialchars($_GET["error"]).'</div></div>';
				}
				?>
				<div class="p-form-elem">
					<p class="p-form-excerption">- Проект – это черновик будущего.<br>&commat;Жюль Ренар</p>
				</div>
				<div class="p-form-elem">
					<label for="subject">Учебная дисциплина</label>
					<select name="subject" id="subject" onchange="subject_update()"><option value="Математика">Математика</option><option value="История">История</option><option value="География">География</option><option value="Физика">Физика</option><option value="Химия">Химия</option><option value="Обществознание">Обществознание</option><option value="Экономика">Экономика</option><option value="Право">Право</option><option value="Информатика">Информатика</option><option value="Биология">Биология</option><option value="Литература">Литература</option><option value="Иностранный язык">Иностранный язык</option><option value="Русский язык">Русский язык</option><option value="Другое">Другое</option></select>
				</div>
				<div class="p-form-elem" id="subject_plus_box" style="display: none;">
				    <input type="text" name="subject_plus" id="subject_plus" maxlength="20">
				</div>
						
				<script type="text/javascript">subject_update();</script>
				
				<div class="p-form-elem">
					<label for="title">Заголовок проекта</label>
					<input type="text" name="title" id="title" maxlength="90" required>
				</div>
				<div class="p-form-elem">
					<label for="tags">Вид</label>
					<select name="tags" id="tags" onchange="tags_update()"><option value=""></option><option value="Информационный">Информационный</option><option value="Исследовательский">Исследовательский</option><option value="Практический">Практический</option><option value="Творческий">Творческий</option><option value="Социальный">Социальный</option><option value="Экспериментальный">Экспериментальный</option><option value="Другой">Другой</option></select>
				</div>
				<div class="p-form-elem" id="tags_plus_box" style="display: none;">
				    <input type="text" name="tags_plus" id="tags_plus" maxlength="35">
				</div>
						
				<script type="text/javascript">tags_update();</script>
				
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Создать">
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