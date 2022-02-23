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
	<title>Раздел проекта</title>
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
	<?php
	if(empty($_GET["p"])) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		header('Location: '.$actual_header_link);
		exit();
	}

	$project_ref = $_GET["p"];
	$section_id = $_GET["s"];

	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
	if (!$result || mysqli_num_rows($result) == 0) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		$mysql->close();
		header('Location: '.$actual_header_link);
		exit();
	} else {
		while($row = mysqli_fetch_assoc($result)) {
			$owner = $row['owner'];
		}
	}
	$mysql->close();

	if(!empty($_COOKIE["user_id"])) {
		if($_COOKIE["user_id"]!=$owner) {
			$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
			header('Location: '.$actual_header_link);
			exit();
		}
	} else {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		header('Location: '.$actual_header_link);
		exit();
	}

	$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
	$result = $mysql->query("SELECT * FROM $project_ref WHERE type='SECTION' AND place='$section_id'");
	if (!$result || mysqli_num_rows($result) == 0) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		$mysql->close();
		header('Location: '.$actual_header_link);
		exit();
	} else {
		while($row = mysqli_fetch_assoc($result)) {
			$name = $row['name'];
		}
	}
	?>
	<section class="section-white section-mg-big">
		<?php
		echo '<h2 class="page-big-title">'.$name.'</h2>';
		?>
		<div class="p-form-box">
			<form class="edit-section" action="a-edit-section-file-result.php" method="post" enctype="multipart/form-data">
				<?php
				if(!empty($_GET["error"])) {
					echo '<div class="p-form-elem">
							<div class="p-form-error">'.htmlspecialchars($_GET["error"]).'</div>
						</div>';
				}
				?>
				<div class="p-form-elem">
					<label for="files">Добавить файлы</label>
					<input type="file" name="file[]" id="files" multiple>
				</div>
				<?php
				echo '<input type="hidden" name="p" value="'.$project_ref.'">';
				echo '<input type="hidden" name="s" value="'.$section_id.'">';
				?>
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Добавить">
				</div>
			</form>
			<br><br>
			<?php
			// IMG
			$result = $mysql->query("SELECT * FROM $project_ref WHERE type='IMG' AND place='$section_id'");
			if (!$result || mysqli_num_rows($result) == 0) {} else {
				echo '<label for="files">Загруженные изображения</label>
    				<div class="p-form-elem p-form-section-data-remove-box">';
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div class="p-form-section-data-remove">
					            <img src="/media/'.$project_ref.'/'.$row['content'].'">
					            <form class="rename-img" action="a-rename-file-result.php" method="post">
					                <div class="p-form-elem">
					                    <label for="name">Название</label>
					                    <input type="text" name="name" id="name" maxlength="50" value="'.$row['name'].'"><br>
    								    <input type="hidden" name="p" value="'.$project_ref.'">
    								    <input type="hidden" name="s" value="'.$section_id.'">
    								    <input type="hidden" name="id" value="'.$row['ID'].'">
									    <input type="submit" name="submit" value="Переименовать">
								    </div>
								</form>
    							<form class="remove-file" action="a-remove-section-file-result.php" method="post">
    								<input type="hidden" name="p" value="'.$project_ref.'">
    								<input type="hidden" name="s" value="'.$section_id.'">
    								<input type="hidden" name="id" value="'.$row['ID'].'">
									<input class="p-form-elem-remove" type="submit" name="submit" value="Удалить">
								</form>
						</div>';
				}
				echo '</div>';
			}
			// M_QR
			$result = $mysql->query("SELECT * FROM $project_ref WHERE type='M_QR' AND place='$section_id'");
			if (!$result || mysqli_num_rows($result) == 0) {} else {
				echo '<label for="files">Загруженные файлы</label>
    				<div class="p-form-elem p-form-section-data-remove-box">';
				while($row = mysqli_fetch_assoc($result)) {
					echo '<div class="p-form-section-data-remove">
					            <a href="/media/'.$project_ref.'/'.$row['content'].'" target="_blank"><img src="/media/'.$project_ref.'/QR_'.$row['content'].'.png"></a>
					            <form class="rename-img" action="a-rename-file-result.php" method="post">
					                <div class="p-form-elem">
					                    <label for="name">Название</label>
					                    <input type="text" name="name" id="name" maxlength="50" value="'.$row['name'].'"><br>
    								    <input type="hidden" name="p" value="'.$project_ref.'">
    								    <input type="hidden" name="s" value="'.$section_id.'">
    								    <input type="hidden" name="id" value="'.$row['ID'].'">
									    <input type="submit" name="submit" value="Переименовать">
								    </div>
								</form>
    							<form class="remove-file" action="a-remove-section-file-result.php" method="post">
    								<input type="hidden" name="p" value="'.$project_ref.'">
    								<input type="hidden" name="s" value="'.$section_id.'">
    								<input type="hidden" name="id" value="'.$row['ID'].'">
									<input class="p-form-elem-remove" type="submit" name="submit" value="Удалить">
								</form>
						</div>';
					
				}
				echo '</div>';
			}
			$mysql->close();
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