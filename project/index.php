<!DOCTYPE html>
<html>
<head>
	<title>Обзор проекта</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Обзор проекта — ProjectPointer">
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
	<?php
	include '../base.php';
	
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
		$row = mysqli_fetch_assoc($result);
		
		$owner = $row['owner'];
		$public = $row['public'];
		$build = $row['build'];
		$title = $row['title'];
		$subject = $row['subject'];
		$tags = $row['tags'];
		$author = $row['author'];
		$authorr = $row['authorr'];
		$place = $row['place'];
		$manager = $row['manager'];
		$managerr = $row['managerr'];
		
		$relevance = $row['relevance'];
		$novelty = $row['novelty'];
		$s_object = $row['s_object'];
		$s_subject = $row['s_subject'];
		
		$goal = $row['goal'];
		$tasks = $row['tasks'];
		$question = $row['question'];
		$product = $row['product'];
		$summary = $row['summary'];
	}

	$mysql->close();
	
	if(!empty($_COOKIE["user_id"]) and $_COOKIE["user_id"]==$owner) {
	    $isOwner = true;
	} else {
	    $isOwner = false;
	}

	if($public == 0 and !$isOwner) {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		header('Location: '.$actual_header_link);
		exit();
	}
	?>
	<section class="section-white section-mg-big">
		<?php
		echo '<h2 class="page-big-title">'.$title.'</h2>';
		?>
		<div class="pv-box">
			<div class="pv-content-box">
			    <?php
			    echo '<a href="viewer.php?p='.$project_ref.'&s=INTRO"><div class="pv-content-elem"><div class="pv-content-elem-title">Введение</div></div></a>';
				?>
				
				<h2 class="pv-content-box-title">Главы</h2>
				<?php
				$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
				$result = $mysql->query("SELECT * FROM $project_ref WHERE type='SECTION' AND place!='INTRO' AND place!='OUTRO' AND place!='LITERATURE' AND place!='ATTACHMENTS'");
				if (!$result || mysqli_num_rows($result) == 0) { $s_count = 0; } else {
					$s_count = mysqli_num_rows($result);
					while($row = mysqli_fetch_assoc($result)) {
						echo '<a href="viewer.php?p='.$project_ref.'&s='.$row['place'].'"><div class="pv-content-elem"><div class="pv-content-elem-title">'.$row['name'].'</div></div></a>';
					}
				}
				$mysql->close();
				if($isOwner and $s_count < $limit_sections) {
					echo '<div class="pv-content-elem"><a href="a-new-section-result.php?p='.$project_ref.'"><img class="pv-content-new-box" src="/styles/img/new.png"></a></div>';
				}
				?>
				
				<h2 class="pv-content-box-title"></h2>
				<?php
			    echo '<a href="viewer.php?p='.$project_ref.'&s=OUTRO"><div class="pv-content-elem"><div class="pv-content-elem-title">Заключение</div></div></a>';
				?>
				
				<h2 class="pv-content-box-title">Приложения</h2>
				<?php
			    echo '<a href="viewer.php?p='.$project_ref.'&s=LITERATURE"><div class="pv-content-elem"><div class="pv-content-elem-title">Список литературы</div></div></a>';
				echo '<a href="viewer.php?p='.$project_ref.'&s=ATTACHMENTS"><div class="pv-content-elem"><div class="pv-content-elem-title">Приложения</div></div></a>';
				?>
			</div>
			<div class="pv-info-box">
			    <?php
			    if($isOwner) {
			        echo '<a href="/knowledge/" target="_blank"><div class="pv-info-knowledge pv-info-elem"><b>Как оформить проект</b></div></a>';
			    }
			    ?>
			    
				<div class="pv-info-box-title-box">
					<h2 class="pv-info-box-title">Паспорт проекта</h2>
					<?php
					if($isOwner) {
						echo '<a class="pv-info-box-edit" href="a-edit-passport.php?p='.$project_ref.'">Редактировать</a>';
					}
					?>
				</div>
				<?php
				if(empty($tags)) {
					$tags = html_entity_decode('—');
				}
				if(empty($author)) {
				    if(empty($authorr)) {
				        $author = html_entity_decode('—');
				    } else {
				        $author = $authorr;
				    }
				} else {
				    if(!empty($authorr)) {
				        $author = $author.', '.$authorr;
				    }
				}
				if(empty($place)) {
					$place = html_entity_decode('—');
				}
				if(empty($manager)) {
				    if(empty($managerr)) {
				        $manager = html_entity_decode('—');
				    } else {
				        $manager = $managerr;
				    }
				} else {
				    if(!empty($managerr)) {
				        $manager = $manager.', '.$managerr;
				    }
				}
				if(empty($relevance)) {
					$relevance = html_entity_decode('—');
				}
				if(empty($novelty)) {
					$novelty = html_entity_decode('—');
				}
				if(empty($s_object)) {
					$s_object = html_entity_decode('—');
				}
				if(empty($s_subject)) {
					$s_subject = html_entity_decode('—');
				}
				if(empty($goal)) {
					$goal = html_entity_decode('—');
				}
				if(empty($tasks)) {
					$tasks = html_entity_decode('—');
				} else {
					$tasks = '<br>'.$tasks;
				}
				if(empty($question)) {
					$question = html_entity_decode('—');
				}
				if(empty($product)) {
					$product = html_entity_decode('—');
				}
				if(empty($summary)) {
					$summary = html_entity_decode('—');
				} else {
					$summary = '<br>'.$summary;
				}
				echo '<div class="pv-info-elem">Автор: <b>'.$author.'</b></div>';
				echo '<div class="pv-info-elem">Учебная дисциплина: <b>'.$subject.'</b></div>';
				echo '<div class="pv-info-elem">Вид: <b>'.$tags.'</b></div>';
				echo '<div class="pv-info-elem">Образовательная организация: <b>'.$place.'</b></div>';
				echo '<div class="pv-info-elem">Руководитель: <b>'.$manager.'</b></div>';
				echo '<div class="pv-info-elem">Актуальность работы: <b>'.$relevance.'</b></div>';
				echo '<div class="pv-info-elem">Новизна: <b>'.$novelty.'</b></div>';
				echo '<div class="pv-info-elem">Объект исследования: <b>'.$s_object.'</b></div>';
				echo '<div class="pv-info-elem">Предмет исследования: <b>'.$s_subject.'</b></div>';
				echo '<div class="pv-info-elem">Цель: <b>'.$goal.'</b></div>';
				echo '<div class="pv-info-elem">Задачи: <b>'.$tasks.'</b></div>';
				echo '<div class="pv-info-elem">Вопрос проекта: <b>'.$question.'</b></div>';
				echo '<div class="pv-info-elem">Результат (продукт): <b>'.$product.'</b></div>';
				echo '<div class="pv-info-elem">Краткое содержание: <b>'.$summary.'</b></div>';
				?>
				
				<div class="pv-info-box-title-box">
					<h2 class="pv-info-box-title">Конструктор</h2>
					<?php
					if($isOwner and $build == '1') {
						echo '<a class="pv-info-box-edit" href="/build/?p='.$project_ref.'">Собрать заново</a>';
					}
					?>
				</div>
				<?php
				if($build == '1') {
					//echo '<div class="pv-info-elem"><a href="/media/'.$project_ref.'/Project.pdf" target="_blank"><img src="/styles/img/file.png" width="200"></a></div>';
					echo '<div class="pv-info-elem"><iframe src="/media/'.$project_ref.'/Project.pdf" width="100%" height="400px"></iframe></div>';
				} else {
				    if($isOwner) {
				        echo '<div class="pv-content-elem"><a href="/build/?p='.$project_ref.'"><img class="pv-content-new-box" src="/styles/img/new.png"></a></div>';
				    } else {
				        echo '<div class="pv-info-elem">Здесь пока ничего нет</div>';
				    }
				}
				?> 
			</div>
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