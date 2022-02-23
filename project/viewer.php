<!DOCTYPE html>
<html>
<head>
	<title>Обзор главы</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Раздел проекта — ProjectPointer">
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
	mb_internal_encoding("UTF-8");//cp1251
	include '../base.php';
	
	if(empty($_GET["p"]) or empty($_GET["s"])) {
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
			$public = $row['public'];
			$owner = $row['owner'];
		}
	}
	$mysql->close();
	
	if(!empty($_COOKIE["user_id"])) {
		if($_COOKIE["user_id"]==$owner) {
			$isOwner = True;
		} else {
			$isOwner = False;
		}
	} else {
		$isOwner = False;
	}

	if($public == 0) {
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
	}
	
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
	$result = $mysql->query("SELECT * FROM $project_ref WHERE type='SECTION' AND place='$section_id'");
	if (!$result || mysqli_num_rows($result) == 0) {} else {
	    $row = mysqli_fetch_assoc($result);
	    $name = $row['name'];
	    $content = $row['content'];
	    
	    if($content!='__TRUE__') {
	        $single = true;
	    } else {
	        $single = false;
	    }
	}
	
	function add_point($str) {
	    if(mb_substr($str, -1) != '.') {
	        return $str.'.';
	    } else {
	        return $str;
	    }
	}
	if($section_id == 'INTRO') {
	    $mysql_new = new mysqli($db_host, $db_login, $db_password, $db_base);
	    $result = $mysql_new->query("SELECT * FROM projects WHERE referral='$project_ref'");
	    $row = mysqli_fetch_assoc($result);
	    
		$intro_extra = '';
		$new_line_str = "\r\n";
		if($row['relevance'] != '') {
		    $intro_extra .= '<b>Актуальность работы:</b> '.add_point($row['relevance']).$new_line_str;
		}
        if($row['novelty'] != '') {
		    $intro_extra .= '<b>Новизна:</b> '.add_point($row['novelty']).$new_line_str;
		}
		if($row['s_object'] != '') {
		    $intro_extra .= '<b>Объект исследования:</b> '.add_point($row['s_object']).$new_line_str;
		}
		if($row['s_subject'] != '') {
		    $intro_extra .= '<b>Предмет исследования:</b> '.add_point($row['s_subject']).$new_line_str;
		}
		if($row['goal'] != '') {
		    $intro_extra .= '<b>Цель работы:</b> '.add_point($row['goal']).$new_line_str;
		}
		if($row['tasks'] != '') {
		    $intro_extra .= '<b>Задачи работы:</b>'.$new_line_str.add_point($row['tasks']).$new_line_str;
		}
		if($row['question'] != '') {
		    $intro_extra .= '<b>Гипотеза:</b> '.add_point($row['question']).$new_line_str;
		}
		if($row['tags'] != '') {
		    $intro_extra .= '<b>Вид проекта</b> – '.add_point(mb_strtolower(mb_substr($row['tags'], 0, 1)).mb_substr($row['tags'], 1, null, "UTF-8")).$new_line_str;
		}
		if($row['product'] != '') {
		    $intro_extra .= '<b>Готовый продукт</b> – '.add_point(mb_strtolower(mb_substr($row['product'], 0, 1)).mb_substr($row['product'], 1, null, "UTF-8")).$new_line_str;
		}
		if($row['summary'] != '') {
		    $intro_extra .= '<b>Краткое содержание:</b> '.add_point($row['summary']).$new_line_str;
		}
		
		$content .= nl2br($new_line_str).$intro_extra;
    }
	?>
	<section class="section-white section-mg-big">
		<?php
		echo '<h2 class="page-big-title">'.$name.'</h2>';
		echo '<a class="page-back" href="/project/?p='.$project_ref.'"><< Обзор проекта</a>';
		?>
		<div class="pv-box">
			<div class="pv-content-box">
				<div class="pv-content-box-title-box">
				    <?php
				    if($single) {
				        echo '<h2 class="pv-content-box-title">Содержание</h2>';
				    } else if($section_id != 'ATTACHMENTS') {
				        echo '<h2 class="pv-content-box-title">Подглавы</h2>';
				    }
				    ?>
					<?php
					if($isOwner and $section_id != 'ATTACHMENTS') {
						echo '<a class="pv-content-box-edit" href="a-edit-section.php?p='.$project_ref.'&s='.$section_id.'">Редактировать</a>';
					}
					?>
				</div>
				<?php
				if($single) {
				    echo '<div class="pv-section-body"><div class="pv-section-body-content">'.($content).'</div></div>';
				} else {
				    
            	$result = $mysql->query("SELECT * FROM $project_ref WHERE type='TEXT' AND place='$section_id'");
	            if (!$result || mysqli_num_rows($result) == 0) { $t_count = 0; } else {
	                $t_count = mysqli_num_rows($result);
	                
	                $alphas = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
	                $lc = 0;
	                if($isOwner) {
	                    while($row = mysqli_fetch_assoc($result)) {
	                        if($section_id == 'ATTACHMENTS') {
	                            $tname = 'Приложение '.$alphas[$lc].'. '.$row['name'];
	                            $lc += 1;
	                            if($lc >= 10) {
	                                break;
	                            }
	                        } else {
	                            $tname = $row['name'];
	                        }
	            		    echo '<a href="a-edit-text.php?p='.$project_ref.'&t='.$row['ID'].'"><div class="pv-content-elem"><div class="pv-content-elem-title"><b>'.$tname.'</b><hr>'.($row['content']).'</div></div></a>';
	            	    }
	                } else {
	                    while($row = mysqli_fetch_assoc($result)) {
	                        if($section_id == 'ATTACHMENTS') {
	                            $tname = 'Приложение '.$alphas[$lc];
	                            $lc += 1;
	                        } else {
	                            $tname = $row['name'];
	                        }
	            		    echo '<div class="pv-content-elem"><div class="pv-content-elem-title"><b>'.$tname.'</b><hr>'.($row['content']).'</div></div>';
	            	    }
	                }
	            }
				if($isOwner and $t_count < $limit_texts) {
					echo '<div class="pv-content-elem"><a href="a-new-text-result.php?p='.$project_ref.'&s='.$section_id.'"><img class="pv-content-new-box" src="/styles/img/new.png"></a></div>';
				}
				
				}
				?>
			</div>
			<div class="pv-info-box"> 
			    <?php
			    if($isOwner) {
			        if($section_id == 'INTRO') {
			            echo '<a href="/knowledge/?article=12" target="_blank"><div class="pv-info-knowledge pv-info-elem"><b>Как оформить введение</b></div></a>';
			        } else if($section_id == 'OUTRO' or $section_id == 'LITERATURE' or $section_id == 'ATTACHMENTS') {
			            echo '<a href="/knowledge/?article=15" target="_blank"><div class="pv-info-knowledge pv-info-elem"><b>Как оформить заключение, список литературы, приложения</b></div></a>';
			        } else {
			            echo '<a href="/knowledge/?article=14" target="_blank"><div class="pv-info-knowledge pv-info-elem"><b>Как оформить основную часть работы</b></div></a>';
			        }
			    }
			    ?>
			
				<div class="pv-info-box-title-box">
					<h2 class="pv-info-box-title">Изображения</h2>
					<?php
					if($isOwner) {
						echo '<a class="pv-info-box-edit" href="a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'">Редактировать</a>';
					}
					?>
				</div>
				<div class="pv-info-elem pv-info-flex">
					<?php
					$result = $mysql->query("SELECT * FROM $project_ref WHERE type='IMG' AND place='$section_id'");
					if (!$result || mysqli_num_rows($result) == 0) {
						echo 'Здесь пока ничего нет';
					} else {
						while($row = mysqli_fetch_assoc($result)) {
							//echo '<div class="pv-info-flex-elem"><img src="/media/'.$project_ref.'/'.$row['content'].'"></div>';
							echo '<div class="pv-info-elem">
							    <b class="pv-info-elem-img-b">'.$row['name'].'</b>
							    <div class="pv-info-flex">
							        <div class="pv-info-flex-elem">
								        <img src="/media/'.$project_ref.'/'.$row['content'].'"></a>
							        </div>
							    </div>
						    </div>';
						}
					}
					?>
				</div>
				<div class="pv-info-box-title-box">
					<h2 class="pv-info-box-title">Файлы</h2>
					<?php
					if($isOwner) {
						echo '<a class="pv-info-box-edit" href="a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'">Редактировать</a>';
					}
					?>
				</div>
				<?php
				$result = $mysql->query("SELECT * FROM $project_ref WHERE type='M_QR' AND place='$section_id'");
				if (!$result || mysqli_num_rows($result) == 0) {
					echo '<div class="pv-info-elem">Здесь пока ничего нет</div>';
				} else {
					while($row = mysqli_fetch_assoc($result)) {
						echo '<div class="pv-info-elem">
							<b>'.$row['name'].'</b><div class="pv-info-flex">
							<div class="pv-info-flex-elem">
								<a href="/media/'.$project_ref.'/'.$row['content'].'" target="_blank"><img src="/styles/img/file_arrow.png"></a>
							</div>
							<div class="pv-info-flex-elem">
								<a href="/media/'.$project_ref.'/'.$row['content'].'" target="_blank"><img src="/media/'.$project_ref.'/QR_'.$row['content'].'.png">
								</a>
							</div></div>
						</div>';
					}
				}
				$mysql->close();
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