<?php
include '../base.php';

function check_user_id() {
	include '../base.php';
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	if (!empty($_COOKIE["user_id"])) {
		$result = $mysql->query("SELECT * FROM admins WHERE user='{$_COOKIE["user_id"]}'");
		if (!$result || mysqli_num_rows($result) == 0) { $mysql->close(); return False;
		} else { $mysql->close(); return True; }
	} else { $mysql->close(); return False; }
}

if(!check_user_id()) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	header('Location: '.$actual_header_link);
	exit();
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Администация</title>
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
			<li><a href="/my">Мои проекты</a></li>
			<li><a href="/community">Все проекты</a></li>
			<li><a href="/profile">Мой профиль</a></li>
		</ul>
	</div>
</div></header>
<main>
	<section class="section-white section-mg-big">
		<?php
		$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
		$result = $mysql->query("SELECT * FROM feedbacks WHERE answer=''");
		if (!$result || mysqli_num_rows($result) == 0) {
			$id = 0;
			$name = 'НЕТ ОТЗЫВОВ';
			$content = 'НЕТ ОТЗЫВОВ';
			$answer = 'НЕТ ОТЗЫВОВ';
		} else {
			$row = mysqli_fetch_assoc($result);
			$id = $row['ID'];
			$name = $row['name'].', '.$row['email'];
			$email = $row['email'];
			$content = $row['content'];
			$answer = $row['name'].', спасибо за ваш отзыв.
';
		}
		$mysql->close();
		?>
		<div class="p-form-box">
			<form class="answer-feedback" action="answer-feedback-result.php" method="post">
				<?php
				if(!empty($_GET["answer-error"])) {
					echo '<div class="p-form-elem">
							<div class="p-form-error">'.htmlspecialchars($_GET["answer-error"]).'</div>
						</div>';
				}
				?>
				<div class="p-form-elem"><b>
					<?php
					echo htmlspecialchars($name);
					?>
				</b></div>
				<div class="p-form-elem" style="font-size: 19px;">
					<?php
					echo htmlspecialchars($content);
					?>
				</div>
				<div class="p-form-elem">
					<?php
					echo '<textarea rows="4" name="answer" id="answer" maxlength="500" required>'.$answer.'</textarea>';
					?>
				</div>
				<?php
				echo '<input type="hidden" name="id" value="'.$id.'">';
				echo '<input type="hidden" name="email" value="'.$email.'">';
				?>
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Ответить">
				</div>
			</form>
		</div>
		<br><br><br>
		<div class="p-form-box">
			<form class="create-article" action="create-article-result.php" method="post">
				<?php
				if(!empty($_GET["article-error"])) {
					echo '<div class="p-form-elem">
							<div class="p-form-error">'.htmlspecialchars($_GET["article-error"]).'</div>
						</div>';
				}
				?>
				<div class="p-form-elem"><h1>Добавить/подправить статью</h1></div>
				<div class="p-form-elem">
					<label for="name">Название статьи</label>
					<input type="text" name="name" id="name" required>
				</div>
				<div class="p-form-elem">
					<label for="name_plus">Название статьи (замена)</label>
					<input type="text" name="name_plus" id="name_plus">
				</div>
				<div class="p-form-elem">
				    <label for="content">Содержание статьи</label>
					<textarea rows="4" name="content" id="content" maxlength="20000" required></textarea>
				</div>
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Создать">
				</div>
			</form>
		</div>
		<br><br><br>
		<div class="p-form-box">
			<form class="delete-project" action="delete-project-result.php" method="post">
				<?php
				if(!empty($_GET["delete-error"])) {
					echo '<div class="p-form-elem">
							<div class="p-form-error">'.htmlspecialchars($_GET["delete-error"]).'</div>
						</div>';
				}
				?>
				<div class="p-form-elem-remove p-form-elem">
					<label for="p">Удалить проект</label>
					<input type="text" name="p" id="p" required>
				</div>
				<div class="p-form-elem">
					<input class="p-form-elem-remove" type="submit" name="submit" value="Удалить">
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