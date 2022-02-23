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

function generate_string($strength = 25) {
	$input = '0123456789abcdefghijklmnopqrstuvwxyz';
	$input_length = strlen($input);
	$random_string = '';
	for($i = 0; $i < $strength; $i++) {
		$random_character = $input[mt_rand(0, $input_length - 1)];
		$random_string .= $random_character;
	}
	return $random_string;
}

$project_ref = filter_var(trim($_POST['p']), FILTER_SANITIZE_STRING);
$section_id = filter_var(trim($_POST['s']), FILTER_SANITIZE_STRING);
$owner = $_COOKIE["user_id"];

$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);

$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
if (!$result || mysqli_num_rows($result) == 0) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	while($row = mysqli_fetch_assoc($result)) {
		$trueOwner = $row['owner'];
	}
}
$mysql->close();
if($owner!=$trueOwner) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	header('Location: '.$actual_header_link);
	exit();
}

if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$uri = 'https://';
} else {
	$uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];

// FILE
//$files = array_filter($_FILES['file']['name']); //something like that to be used before processing files.
$total = count($_FILES['file']['name']);
if($total > 10) {
	header('Location: a-edit-section.php?p='.$project_ref.'&s='.$section_id.'&error=Слишком%20много%20файлов%20для%20одной%20загрузки');
	exit();
}

$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);

// LIMIT
// IMG
$result = $mysql->query("SELECT * FROM `$project_ref` WHERE type='IMG'");
if (!$result) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	$img_count = mysqli_num_rows($result);
}
// M_QR
$result = $mysql->query("SELECT * FROM `$project_ref` WHERE type='M_QR'");
if (!$result) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	$m_qr_count = mysqli_num_rows($result);
}


class QrCode {
	private $apiUrl = 'http://chart.apis.google.com/chart';
	private $data;
	public function URL($url = null) {
		$this->data = preg_match("#^https?\:\/\/#", $url) ? $url : "http://{$url}";
	}
	public function QRCODE($size = 400, $filename = null) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "chs={$size}x{$size}&cht=qr&chl=".urlencode($this->data)."&chld=L|1");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$img = curl_exec($ch);
		curl_close($ch);
		if ($img) {
			if ($filename) {
				if (!preg_match("#\.png$#i", $filename)) {
					$filename .= ".png";
				}
				return file_put_contents($filename, $img);
			} else {
				return true;
			}
		}
		return false;
	}
}
$qc = new QrCode();
// CHECK LIMITS
for( $i=0 ; $i < $total ; $i++ ) {
	if(!is_uploaded_file($_FILES['file']['tmp_name'][$i])) {
		$total = 0;
		break;
	}

	//$image = $_FILES['image'];
	$format = explode(".", $_FILES['file']['name'][$i]);

	if($format[count($format)-1] == "jpg" || $format[count($format)-1] == "png" || $format[count($format)-1] == "jpeg") {
		// IF IMAGE
		// LIMIT COUNT
		if($img_count >= $limit_imgs) {
			$mysql->close();
			header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Достигнут%20лимит%20заргужаемых%20изображений');
			exit();
		}
		// LIMIT SIZE
		if(filesize($_FILES['file']['tmp_name'][$i]) == 0) {
			$mysql->close();
			header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Один%20из%20файлов%20слишком%20большой');
			exit();
		}
		if(filesize($_FILES['file']['tmp_name'][$i]) > $limit_img*1048576) {
			$mysql->close();
			header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Один%20из%20файлов%20слишком%20большой');
			exit();
		}
		$img_count = $img_count + 1;
	} else {
		// IF OTHER
		// LIMIT COUNT
		if($m_qr_count >= $limit_files) {
			$mysql->close();
			header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Достигнут%20лимит%20заргужаемых%20QR-файлов');
			exit();
		}
		// LIMIT SIZE
		if(filesize($_FILES['file']['tmp_name'][$i]) == 0) {
			$mysql->close();
			header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Один%20из%20файлов%20слишком%20большой');
			exit();
		}
		if(filesize($_FILES['file']['tmp_name'][$i]) > $limit_file*1048576) {
			$mysql->close();
			header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Один%20из%20файлов%20слишком%20большой');
			exit();
		}
		$m_qr_count = $m_qr_count + 1;
	}
}
for( $i=0 ; $i < $total ; $i++ ) {
	//$image = $_FILES['image'];
	$format = explode(".", $_FILES['file']['name'][$i]);

	$file_name = generate_string().".".$format[count($format)-1];
	if(!move_uploaded_file($_FILES['file']['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT']."/media/".$project_ref."/".$file_name)) {
		$mysql->close();
		header('Location: a-edit-section-file.php?p='.$project_ref.'&s='.$section_id.'&error=Не%20удалось%20загрузить%20файл%20');
		exit();
	}

	if($format[count($format)-1] == "jpg" || $format[count($format)-1] == "png" || $format[count($format)-1] == "jpeg") {
		// IF IMAGE
		$file_type = "IMG";
	} else {
		// IF OTHER
		$file_type = "M_QR";
		// CREATE QR
		$qc->URL($uri."/media/".$project_ref."/".$file_name);
		$qc->QRCODE(150, $_SERVER['DOCUMENT_ROOT']."/media/".$project_ref."/QR_".$file_name);
	}

	//$file_bn = $_FILES['file']['name'][$i];

	$mysql->query("INSERT INTO `$project_ref` (`type`, `place`, `name`, `content`) VALUES ('$file_type', '$section_id', '', '$file_name')");
}

$mysql->close();
header('Location: '.$uri.'/project/viewer.php?p='.$project_ref.'&s='.$section_id);
exit();
?>