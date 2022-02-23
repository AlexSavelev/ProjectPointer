<?php
include '../base.php';

$base_article = 10;

if(empty($_GET["article"])) {
	$article = $base_article;
} else {
    $article = $_GET["article"];
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
if(is_numeric($article)) {
    $result = $mysql->query("SELECT * FROM articles WHERE ID='$article'");
} else {
    $result = $mysql->query("SELECT * FROM articles WHERE name='$article'");
}
if (!$result || mysqli_num_rows($result) == 0) {
	$mysql->close();
	header('Location: index.php?article='.$base_article);
	exit();
} else {
    $row = mysqli_fetch_assoc($result);
	$name = $row['name'];
	$content = $row['content'];
}
$mysql->close();
?>


<!DOCTYPE html>
<html>
<head>
    <?php
    if($name != 'База знаний')
        echo '<title>'.$name.' — База знаний</title>';
    else
        echo '<title>База знаний</title>';
    ?>
	<meta charset="UTF-8" />
	<meta name="title" content="База знаний — ProjectPointer">
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
<style>
	    .article-box {
	        max-width: 1000px;
	        padding-top: 10px;
	        padding-bottom: 10px;
	        margin: auto;
	        
	        font-size: 17px;
	    }
	    .article-box h3 {
	        margin-top: 5px;
	        margin-bottom: 7px;
	        font-size: 3em;
	    }
	    .article-box li {
	        padding: 3px;
	    }
	    .article-box a {
	        text-decoration: underline;
	    }
	    .section-gray p::selection, .section-gray h3::selection, .section-gray li::selection {
            color: var(--color-reverse-selection);
            background: var(--background-reverse-selection);
	    }
	    
	    .a-extra {
	        display: none;
	    }
	    
	    .article-box a:hover ~ .a-extra {
	        margin: 3px;
	        margin-top: 10px;
	        margin-bottom: 10px;
	        border-radius: 8px;
            border: 1px solid rgba(60, 66, 87, 0.12);
            
            max-width: 700px;
            display: flex;
            justify-content: left;
            word-wrap: break-word;
            font-size: 19px;
	        padding: 5px;
	        padding-left: 15px;
        }
	</style>
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
	<section class="section-white">
	    <?php
	    echo '<h2 class="page-big-title">'.$name.'</h2>';
	    ?>
	</section>
	<?php
	    echo base64_decode($content);
	?>
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