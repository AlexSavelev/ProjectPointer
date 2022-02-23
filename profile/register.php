<!DOCTYPE html>
<html>
<head>
	<title>Регистрация</title>
	<meta charset="UTF-8" />
	<meta name="title" content="Регистрация — ProjectPointer">
	<meta name="description" content="Создать профиль и войти в систему ProjectPointer">
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
	<section class="section-white section-mg-big">
		<h2 class="page-big-title">Регистрация</h2>
		<div class="lr-form-box">
		    <?php
		        include '../base.php';
		        include '../base_mail.php';

                function generate_code($strength = 6) {
	                $input = '0123456789';
                    $input_length = strlen($input);
	                $random_string = '';
	                for($i = 0; $i < $strength; $i++) {
	                	$random_character = $input[mt_rand(0, $input_length - 1)];
	                	$random_string .= $random_character;
	                }
	                return $random_string;
                }

				if(isset($_POST['email'])) {
				    $fsubmit = True;
				    $name = $_POST['name'];
				    $email = strtolower($_POST['email']);
				    $password = $_POST['password'];
                    
				    if(mb_strlen($email) < 1 || mb_strlen($email) > 100) {
				        header('Location: register.php?error=Недопустимая%20длина%20эл.%20почты');
	                    exit;
                    }
				    $mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
				    
				    $code = generate_code();
				    
				    $result = $mysql->query("SELECT * FROM users WHERE `email`='$email'");
				    if (!$result || mysqli_num_rows($result) == 0) {} else {
				        $mysql->close();
				        header('Location: register.php?error=Пользователь%20с%20такой%20электронной%20почтой%20уже%20существует');
	                    exit;
				    }
				    $result = $mysql->query("SELECT * FROM confirm_email WHERE `email`='$email'");
				    if (!$result || mysqli_num_rows($result) == 0) {
				        $mysql->query("INSERT INTO `confirm_email` (`email`, `code`) VALUES ('$email', '$code')");
				        
				        $message = "Код для подтвержения эл. почты: ".$code."\r\nНикому его не говорите!";
                        $message = wordwrap($message, 70, "\r\n");
                        $subject = 'Сброс пароля';
                        mail($email, $subject, $message, $mail_headers);
				    }
				    $mysql->close();
				} else {
				    $fsubmit = False;
				}
				
				
				if($fsubmit) {
				    echo '<form class="register-form" action="register-result.php" method="post">';
			        if(!empty($_GET["error"])) {
					    echo '<div class="lr-form-elem"><div class="lr-form-error">'.$_GET["error"].'</div></div>';
				    }
				    echo '<div class="lr-form-elem">
					    <label for="code">Код-подтвержение по эл. почте</label>
					    <input type="text" name="code" id="code" maxlength="6" required>
				    </div>
				    <div class="lr-form-elem lr-form-checkbox">
					    <label for="sysstay">
						    <input type="checkbox" name="sysstay" id="sysstay" value="1" Checked>Оставаться в системе
					    </label>
				    </div>
				    <input type="hidden" name="name" id="name" maxlength="40" value="'.$name.'">
				    <input type="hidden" name="email" id="email" maxlength="100" value="'.$email.'">
				    <input type="hidden" name="password" id="password" maxlength="60" value="'.$password.'">
				    <div class="lr-form-elem">
				    	<input type="submit" name="submit" value="Зарегистрироваться">
				    </div>
			    </form>';
				} else {
				    echo '<form class="register-form" action="register.php" method="post">';
			        if(!empty($_GET["error"])) {
					    echo '<div class="lr-form-elem"><div class="lr-form-error">'.$_GET["error"].'</div></div>';
				    }
				    echo '<div class="lr-form-elem">
					    <label for="name">Имя</label>
					    <input type="text" name="name" id="name" maxlength="40" required>
				    </div>
				    
				    <div class="lr-form-elem">
					    <label for="email">Эл. почта</label>
					    <input type="email" name="email" id="email" maxlength="100" required>
				    </div>
				    <div class="lr-form-elem">
					    <label for="password">Пароль</label>
					    <input type="password" name="password" id="password" maxlength="60" required>
				    </div>
				    <div class="lr-form-elem">
				    	<input type="submit" name="submit" value="Зарегистрироваться">
				    </div>
				    <div class="lr-form-elem">
					    <a href="/profile/login.php">Уже есть аккаунт? Войти</a>
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
			<li><a href="/extra/feedback.php" class="footer-menu-elem">Обратная связь</a></li>
		</ul>
	</div>
</div></footer>
</body>
</html>