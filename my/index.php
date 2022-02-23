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
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/profile/login.php";
	setcookie("backpage", $actual_link, 0, '/');
	header('Location: '.$actual_header_link);
	exit();
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Мои проекты</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Мои проекты — ProjectPointer">
	<meta name="description" content="Список личных проектов">
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
	<section class="project-list section-mg-big">
		<?php
		$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
		$result = $mysql->query("SELECT * FROM projects WHERE owner='{$_COOKIE["user_id"]}'");
		if (!$result || mysqli_num_rows($result) == 0) { $p_count = 0; } else {
			$p_count = mysqli_num_rows($result);
			while($row = mysqli_fetch_assoc($result)) {
				if(empty($row['tags'])) {
					echo '<a class="ple" href="'.'/project?p='.$row['referral'].'"><div class="ple-name">'.$row['title'].'</div><div class="ple-subject">'.$row['subject'].'</div></a>';
				} else {
				    $subject = $row['subject'].', '.$row['tags'];
				    if(mb_strlen($subject, "UTF-8") > 29) {
				        $subject = mb_substr($subject, 0, 29, "UTF-8").'...';
				    }
					echo '<a class="ple" href="'.'/project?p='.$row['referral'].'"><div class="ple-name">'.$row['title'].'</div><div class="ple-subject">'.$subject.'</div></a>';
				}
			}
		}
		$mysql->close();
		if($p_count < $limit_projects) {
			echo '<a class="ple" href="new.php"><img class="ple-new-box" src="/styles/img/new.png"></a>';
		}
		?>
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