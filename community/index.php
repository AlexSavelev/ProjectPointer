<!DOCTYPE html>
<html>
<head>
	<title>Все проекты</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Все проекты — ProjectPointer">
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
	<section class="section-mg-big">
		<h2 class="page-big-title">Все проекты</h2>
	<div class="pv-box">
			<div class="com-project-list com-pv-content-box">
				<?php
				include '../base.php';

				$subject = '';
				$tags = '';
					
				$qrt = "SELECT * FROM projects WHERE `public`='1'";
				if(isset($_POST['subject'])) {
				    if($_POST['subject']=="Другое") {
				        $subject = $_POST['subject_plus'];
				        $qrt .= " AND `subject` LIKE '%".$_POST['subject_plus']."%'";
				    }else if($_POST['subject']!='') {
						$subject = $_POST['subject'];
						$qrt .= " AND `subject` LIKE '%".$_POST['subject']."%'";
					}
				}
				if(isset($_POST['tags'])) {
				    if($_POST['tags']=="Другой") {
				        $tags = $_POST['tags_plus'];
				        $qrt .= " AND `tags` LIKE '%".$_POST['tags_plus']."%'";
					} else if($_POST['tags']!='') {
						$tags = $_POST['tags'];
						$qrt .= " AND `tags` LIKE '%".$_POST['tags']."%'";
					}
				}
				$qrt .= " LIMIT ".$limit_com_query;

				$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
				$result = $mysql->query($qrt);
				if (!$result || mysqli_num_rows($result) == 0) {
				    echo '<div class="ple"><div class="ple-name">Таких проектов ещё нет :-/</div><div class="ple-subject">'.$subject.'</div></div>';
				} else {
				    $counter = 0;
					while($row = mysqli_fetch_assoc($result)) {
					    if($counter >= $limit_com_query) {
					        break;
					    }
						echo '<a class="ple" href="'.'/project?p='.$row['referral'].'"><div class="ple-name">'.$row['title'].'</div><div class="ple-subject">'.$row['subject'].'</div></a>';
						$counter += 1;
					}
				}
				$mysql->close();
				?>
			</div>
			<div class="com-pv-info-box"> 
				<div class="pv-info-box-title-box">
					<h2 class="pv-info-box-title">Фильтры</h2>
				</div>
				<div class="com-pv-info-elem">
					<form class="edit-section" action="/community/" method="post" enctype="multipart/form-data">
						<div class="p-form-elem p-form-radio">
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
						
						<div class="p-form-elem p-form-radio">
							<label for="tags">Тип проекта</label>
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
							if($tags=="Практико-ориентированный") {
							    $selected = True;
								echo '<option value="Практико-ориентированный" selected>Практико-ориентированный</option>';
							} else {
								echo '<option value="Практико-ориентированный">Практико-ориентированный</option>';
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
							echo '<input type="text" name="tags_plus" id="tags_plus" maxlength="30" value="'.$tags.'">';
							?>
						</div>
						
						<script type="text/javascript">tags_update();</script>
						
						<div class="p-form-elem">
							<input type="submit" name="submit" value="Применить">
						</div>
					</form>
				</div>
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