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
	<title>Паспорт проекта</title>
	<meta charset="UTF-8" />
	<meta name="title" content="ProjectPointer - сделай свой проект">
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
		$row = mysqli_fetch_assoc($result);
		
		$owner = $row['owner'];
		$public = $row['public'];
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

	if(!empty($_COOKIE["user_id"])) {
		if($_COOKIE["user_id"] != $owner) {
			$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
			$mysql->close();
			header('Location: '.$actual_header_link);
			exit();
		}
	} else {
		$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
		$mysql->close();
		header('Location: '.$actual_header_link);
		exit();
	}
	?>
	<section class="section-white section-mg-big">
		<h2 class="page-big-title">Паспорт проекта</h2>
		<div class="p-form-box">
			<form class="edit-project-passport" action="a-edit-passport-result.php" method="post">
				<?php
					if(!empty($_GET["error"])) {
						echo '<div class="p-form-elem">
								<div class="p-form-error">'.htmlspecialchars($_GET["error"]).'</div>
							</div>';
					}
				?>
				<div class="p-form-elem">
					<label for="title">Название проекта</label>
					<?php
						echo '<input type="text" name="title" id="title" maxlength="100" value="'.$title.'" required>';
					?>
				</div>
				<div class="p-form-elem">
					<label for="author">ФИО автора</label>
					<?php
						echo '<input type="text" name="author" id="author" maxlength="60" value="'.$author.'">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="authorr">Статус автора</label>
					<?php
						echo '<input type="text" name="authorr" id="authorr" maxlength="40" value="'.$authorr.'" placeholder="Пример: Ученик 9 «А» класса">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="subject">Учебная дисциплина</label>
					<?php
							$selected = False;
							echo '<select name="subject" id="subject" onchange="subject_update()">';
							if($subject=="Математика") {
							    $selected = True;
								echo '<option value="Математика" selected>Математика</option>';
							} else {
								echo '<option value="Математика">Математика</option>';
							}
							if($subject=="История") {
							    $selected = True;
								echo '<option value="История" selected>История</option>';
							} else {
								echo '<option value="История">История</option>';
							}
							if($subject=="География") {
							    $selected = True;
								echo '<option value="География" selected>География</option>';
							} else {
								echo '<option value="География">География</option>';
							}
							if($subject=="Физика") {
							    $selected = True;
								echo '<option value="Физика" selected>Физика</option>';
							} else {
								echo '<option value="Физика">Физика</option>';
							}
							if($subject=="Химия") {
							    $selected = True;
								echo '<option value="Химия" selected>Химия</option>';
							} else {
								echo '<option value="Химия">Химия</option>';
							}
							if($subject=="Обществознание") {
							    $selected = True;
								echo '<option value="Обществознание" selected>Обществознание</option>';
							} else {
								echo '<option value="Обществознание">Обществознание</option>';
							}
							if($subject=="Экономика") {
							    $selected = True;
								echo '<option value="Экономика" selected>Экономика</option>';
							} else {
								echo '<option value="Экономика">Экономика</option>';
							}
							if($subject=="Право") {
							    $selected = True;
								echo '<option value="Право" selected>Право</option>';
							} else {
								echo '<option value="Право">Право</option>';
							}
							if($subject=="Информатика") {
							    $selected = True;
								echo '<option value="Информатика" selected>Информатика</option>';
							} else {
								echo '<option value="Информатика">Информатика</option>';
							}
							if($subject=="Биология") {
							    $selected = True;
								echo '<option value="Биология" selected>Биология</option>';
							} else {
								echo '<option value="Биология">Биология</option>';
							}
							if($subject=="Литература") {
							    $selected = True;
								echo '<option value="Литература" selected>Литература</option>';
							} else {
								echo '<option value="Литература">Литература</option>';
							}
							if($subject=="Иностранный язык") {
							    $selected = True;
								echo '<option value="Иностранный язык" selected>Иностранный язык</option>';
							} else {
								echo '<option value="Иностранный язык">Иностранный язык</option>';
							}
							if($subject=="Русский язык") {
							    $selected = True;
								echo '<option value="Русский язык" selected>Русский язык</option>';
							} else {
								echo '<option value="Русский язык">Русский язык</option>';
							}
							if($selected == False) {
								echo '<option value="Другое" selected>Другое</option>';
							} else {
								echo '<option value="Другое">Другое</option>';
							}
							echo '</select>';
							?>
				</div>
				<div class="p-form-elem" id="subject_plus_box" style="display: none;">
					<?php
					echo '<input type="text" name="subject_plus" id="subject_plus" maxlength="20" value="'.$subject.'">';
					?>
				</div>
				
				<script type="text/javascript">subject_update();</script>
						
				<div class="p-form-elem">
					<label for="tags">Вид</label>
					<?php
							$selected = False;
							echo '<select name="tags" id="tags" onchange="tags_update()">';
							if($tags=="") {
							    $selected = True;
								echo '<option value="" selected></option>';
							} else {
								echo '<option value=""></option>';
							}
							if($tags=="Информационный") {
							    $selected = True;
								echo '<option value="Информационный" selected>Информационный</option>';
							} else {
								echo '<option value="Информационный">Информационный</option>';
							}
							if($tags=="Исследовательский") {
							    $selected = True;
								echo '<option value="Исследовательский" selected>Исследовательский</option>';
							} else {
								echo '<option value="Исследовательский">Исследовательский</option>';
							}
							if($tags=="Практический") {
							    $selected = True;
								echo '<option value="Практический" selected>Практический</option>';
							} else {
								echo '<option value="Практический">Практический</option>';
							}
							if($tags=="Творческий") {
							    $selected = True;
								echo '<option value="Творческий" selected>Творческий</option>';
							} else {
								echo '<option value="Творческий">Творческий</option>';
							}
							if($tags=="Социальный") {
							    $selected = True;
								echo '<option value="Социальный" selected>Социальный</option>';
							} else {
								echo '<option value="Социальный">Социальный</option>';
							}
							if($tags=="Экспериментальный") {
							    $selected = True;
								echo '<option value="Экспериментальный" selected>Экспериментальный</option>';
							} else {
								echo '<option value="Экспериментальный">Экспериментальный</option>';
							}
							if($selected == False) {
								echo '<option value="Другой" selected>Другой</option>';
							} else {
								echo '<option value="Другой">Другой</option>';
							}
							echo '</select>';
							?>
				</div>
				<div class="p-form-elem" id="tags_plus_box" style="display: none;">
					<?php
					echo '<input type="text" name="tags_plus" id="tags_plus" maxlength="35" value="'.$tags.'">';
					?>
				</div>
						
				<script type="text/javascript">tags_update();</script>
				
				<div class="p-form-elem">
					<label for="place">Наименование образовательной организации</label>
					<?php
						echo '<input type="text" name="place" id="place" maxlength="150" value="'.$place.'">';
					?>
				</div>	
				<div class="p-form-elem">
					<label for="manager">ФИО руководителя</label>
					<?php
						echo '<input type="text" name="manager" id="manager" maxlength="100" value="'.$manager.'">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="managerr">Статус руководителя</label>
					<?php
						echo '<input type="text" name="managerr" id="managerr" maxlength="50" value="'.$managerr.'" placeholder="Пример: Учитель биологии">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="relevance">Актуальность работы</label>
					<?php
					echo '<textarea rows="4" name="relevance" id="relevance" maxlength="700">'.$relevance.'</textarea>';
					?>
				</div>
				<div class="p-form-elem">
					<label for="novelty">Новизна</label>
					<?php
					echo '<textarea rows="4" name="novelty" id="novelty" maxlength="700">'.$novelty.'</textarea>';
					?>
				</div>
				<div class="p-form-elem">
					<label for="s_object">Объект исследования</label>
					<?php
						echo '<input type="text" name="s_object" id="s_object" maxlength="150" value="'.$s_object.'">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="s_subject">Предмет исследования</label>
					<?php
						echo '<input type="text" name="s_subject" id="s_subject" maxlength="150" value="'.$s_subject.'">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="goal">Цель</label>
					<?php
						echo '<textarea rows="4" name="goal" id="goal" maxlength="500">'.$goal.'</textarea>';
					?>
				</div>
				<div class="p-form-elem">
					<label for="tasks">Задачи</label>
					<?php
						echo '<textarea rows="4" name="tasks" id="tasks" maxlength="1000">'.$tasks.'</textarea>';
					?>
				</div>
				<div class="p-form-elem">
					<label for="question">Вопрос проекта</label>
					<?php
						echo '<input type="text" name="question" id="question" maxlength="500" value="'.$question.'">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="product">Результат (продукт)</label>
					<?php
						echo '<input type="text" name="product" id="product" maxlength="500" value="'.$product.'">';
					?>
				</div>
				<div class="p-form-elem">
					<label for="summary">Краткое содержание</label>
					<?php
						echo '<textarea rows="3" name="summary" id="summary" maxlength="3000">'.$summary.'</textarea>';
					?>
				</div>
				<div class="p-form-elem p-form-checkbox">
					<label for="public">
						<?php
							if($public=="1") {
								echo '<input type="checkbox" name="public" id="public" value="1" Checked>Публичный проект (виден всеми)';
							} else {
								echo '<input type="checkbox" name="public" id="public" value="1">Публичный проект (виден всеми)';
							}
						?>
					</label>
				</div>
				<?php
                echo "<script>document.getElementById('relevance').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
                echo "<script>document.getElementById('novelty').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
                echo "<script>document.getElementById('goal').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
                echo "<script>document.getElementById('tasks').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
                echo "<script>document.getElementById('summary').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
                
                echo '<input type="hidden" name="p" value="'.$project_ref.'">';
				?>
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Применить">
				</div>
			</form>
			<form class="remove-project" action="a-remove-passport-result.php" method="post">
				<?php
					echo '<input type="hidden" name="p" value="'.$project_ref.'">';
				?>
    			<div class="p-form-elem">
					<?php
						echo '<input class="p-form-elem-remove" type="submit" name="submit" value="Удалить проект">';
					?>
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