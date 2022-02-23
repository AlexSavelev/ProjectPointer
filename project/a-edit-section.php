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
	<title>Подглава проекта</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Раздел проекта — ProjectPointer">
	<meta name="description" content="">
	<link rel="stylesheet" type="text/css" href="/styles/base.css">
	<link rel="icon" type="image/png" href="/favicon.ico"/>
	<script type="text/javascript">
	    function content_update(){
	        if(document.getElementById('single').checked) {
	            document.getElementById('content-box').style.display = "none";
            } else {
                 document.getElementById('content-box').style.display = "block";
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
			if($section_id == 'INTRO') {
		        $tags = $row['tags'];
		
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
			$content = $row['content'];
		}
	}
	
	$isIO = 0;
	if($section_id == 'INTRO' or $section_id == 'OUTRO' or $section_id == 'LITERATURE') {
	    $isIO = 123;
	} else if($section_id == 'ATTACHMENTS') {
	    $isIO = 4;
	}

	$mysql->close();
	?>
	<section class="section-white section-mg-big">
		<?php
		echo '<h2 class="page-big-title">'.$name.'</h2>';
		?>
		<div class="p-form-box">
			<form class="edit-section" action="a-edit-section-result.php" method="post">
				<?php
				if(!empty($_GET["error"])) {
					echo '<div class="p-form-elem"><div class="p-form-error">'.htmlspecialchars($_GET["error"]).'</div></div>';
				}
				?>
				<?php
				    if($isIO == 0) {
                        echo '<div class="p-form-elem">
					            <label for="name">Название главы</label>
					            <input type="text" name="name" id="name" maxlength="100" value="'.$name.'" required>
				            </div>
				            <div class="p-form-elem p-form-checkbox">
					            <label for="single">';
                        if($content=="__TRUE__") {
								echo '<input type="checkbox" name="single" id="single" value="1" onchange="content_update()" Checked>Подразделяется на подглавы';
						} else {
								echo '<input type="checkbox" name="single" id="single" value="1" onchange="content_update()">Подразделяется на подглавы';
						}
						echo '</label>
				            </div>
				            <div class="p-form-elem" id="content-box">
					            <label for="content">Содержание главы</label>';
                        if($content=="__TRUE__") {
					        $content = "";
                        }
                        echo '<textarea rows="30" name="content" id="content" maxlength="5000">'.$content.'</textarea>';
                        echo '</div>
				        <script type="text/javascript">content_update();</script>';
                    } else if($isIO == 123) {
                        echo '<div class="p-form-elem" id="content-box">
                                <textarea rows="20" name="content" id="content" maxlength="5000">'.$content.'</textarea>
                            </div>';
                    }
                    if($section_id == 'INTRO') {
                        echo '<script type="text/javascript">function tags_update(){ if(document.getElementById(\'tags\').value == "Другой") { document.getElementById(\'tags_plus_box\').style.display = "block"; } else { document.getElementById(\'tags_plus_box\').style.display = "none"; } } </script>';
                        echo '<h3>Дополнительные параметры</h3>';
                        echo '<div class="p-form-elem"><label for="relevance">Актуальность работы</label><textarea rows="4" name="relevance" id="relevance" maxlength="700">'.$relevance.'</textarea></div>';
                        echo '<div class="p-form-elem"><label for="novelty">Новизна</label><textarea rows="4" name="novelty" id="novelty" maxlength="700">'.$novelty.'</textarea></div>';
                        echo '<div class="p-form-elem"><label for="s_object">Объект исследования</label><input type="text" name="s_object" id="s_object" maxlength="150" value="'.$s_object.'"></div>';
                        echo '<div class="p-form-elem"><label for="s_subject">Предмет исследования</label><input type="text" name="s_subject" id="s_subject" maxlength="150" value="'.$s_subject.'"></div>';
                        echo '<div class="p-form-elem"><label for="goal">Цель</label><textarea rows="4" name="goal" id="goal" maxlength="500">'.$goal.'</textarea></div>';
                        echo '<div class="p-form-elem"><label for="tasks">Задачи</label><textarea rows="4" name="tasks" id="tasks" maxlength="1000">'.$tasks.'</textarea></div>';
                        echo '<div class="p-form-elem"><label for="question">Вопрос проекта</label><input type="text" name="question" id="question" maxlength="500" value="'.$question.'"></div>';
                        // TAGS START
                        echo '<div class="p-form-elem"><label for="tags">Вид</label>';
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
						echo '</select></div>';
						echo '<div class="p-form-elem" id="tags_plus_box" style="display: none;"><input type="text" name="tags_plus" id="tags_plus" maxlength="35" value="'.$tags.'"></div>';
					    echo '<script type="text/javascript">tags_update();</script>';
					    // TAGS END
                        echo '<div class="p-form-elem"><label for="product">Результат (продукт)</label><input type="text" name="product" id="product" maxlength="500" value="'.$product.'"></div>';
                        echo '<div class="p-form-elem"><label for="summary">Краткое содержание</label><textarea rows="3" name="summary" id="summary" maxlength="3000">'.$summary.'</textarea></div>';
                        
                        echo "<script>document.getElementById('relevance').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
	                    echo "<script>document.getElementById('novelty').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
	                    echo "<script>document.getElementById('goal').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
	                    echo "<script>document.getElementById('tasks').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
	                    echo "<script>document.getElementById('summary').addEventListener('keydown', function(e) { if (e.key == 'Tab') { e.preventDefault(); var start = this.selectionStart; var end = this.selectionEnd; this.value = this.value.substring(0, start) + \"\\t\" + this.value.substring(end); this.selectionStart = this.selectionEnd = start + 1; }});</script>";
	                }
				?>
				<script>
				document.getElementById('content').addEventListener('keydown', function(e) {
                        if (e.key == 'Tab') {
                            e.preventDefault();
                            var start = this.selectionStart;
                            var end = this.selectionEnd;

                            // set textarea value to: text before caret + tab + text after caret
                            this.value = this.value.substring(0, start) +
                              "\t" + this.value.substring(end);

                            // put caret at right position again
                            this.selectionStart =
                            this.selectionEnd = start + 1;
                        }
                    });
                </script>
				<?php
				echo '<input type="hidden" name="p" value="'.$project_ref.'">';
				echo '<input type="hidden" name="s" value="'.$section_id.'">';
				?>
				<div class="p-form-elem">
					<input type="submit" name="submit" value="Применить">
				</div>
			</form>
			<?php
			if(!$isIO) {
			    echo '<form class="remove-section" action="a-remove-section-result.php" method="post">
				        <input type="hidden" name="p" value="'.$project_ref.'">
        				<input type="hidden" name="s" value="'.$section_id.'">
    			        <div class="p-form-elem">
					        <input class="p-form-elem-remove" type="submit" name="submit" value="Удалить раздел">
				        </div>
			        </form>';
			}
			?>
		</div>
	</section>
</main>
<footer><div class="hf-main">
	<div class="hf-elem"><p class="footer-site-name">2022 ProjectPointer </p></div>
	<div class="hf-elem footer-menu-div">
		<ul class="footer-menu">
			<li><a href="/extra/about.php" class="footer-menu-elem">О проекте</a></li>
			<li><a href="/extra/feedback.php" class="footer-menu-elem">Оставить отзыв</a></li>
		</ul>
	</div>
</div></footer>
</body>
</html>