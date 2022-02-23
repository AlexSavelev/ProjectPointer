<?php
include '../base.php';

$pdf_subject = 'Project';
$pdf_keywords = 'PPOINTER.RU, ProjectBuilder';

$ptype = 'Индивидуальный проект'; // Const
$year = '2022'; // Const

// Parcer
$sections = array();

$pdf_title = '';
$pname = '';
$subject = '';
$tags = '';
$place = '';
$pdf_author = '';
$author_name = '';
$author_representative = '';
$manager_name = '';
$manager_representative = '';
$relevance = '';
$novelty = '';
$s_object = '';
$s_subject = '';
$goal = '';
$tasks = '';
$question = '';
$product = '';
$summary = '';

function parce_project($project_ref = "") {
    global $sections;
    
    global $pdf_title;
    global $pname;
    global $subject;
    global $tags;
    global $place;
    global $pdf_author;
    global $author_name;
    global $author_representative;
    global $manager_name;
    global $manager_representative;
    global $relevance;
    global $novelty;
    global $s_object;
    global $s_subject;
    global $goal;
    global $tasks;
    global $question;
    global $product;
    global $summary;
    
	include '../base.php';
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
	if (!$result || mysqli_num_rows($result) == 0) {
		$mysql->close();
		return False;
	} else {
		$row = mysqli_fetch_assoc($result);
		
		$pdf_title = $row['title'];
		$pname = $row['title'];
		$subject = $row['subject'];
		$tags = $row['tags'];
		$place = $row['place'];
		$pdf_author = $row['author'];
		$author_name = $row['author'];
		$author_representative = $row['authorr'];
		$manager_name = $row['manager'];
		$manager_representative = $row['managerr'];
		$relevance = $row['relevance'];
        $novelty = $row['novelty'];
        $s_object = $row['s_object'];
        $s_subject = $row['s_subject'];
		$goal = $row['goal'];
		$tasks = $row['tasks'];
		$question = $row['question'];
		$product = $row['product'];
		$summary = $row['summary'];
		
	    $mysql->close();
	}
	
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_projects);
	$result = $mysql->query("SELECT * FROM `$project_ref` WHERE type='SECTION'");
	if (!$result) {
		$mysql->close();
		return False;
	} else {
	    $chapter_counter = 1;
	    $text_counter = 1;
	    
	    $alphas = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10');
	    $attachment_counter = 0;
	    
	    while($row = mysqli_fetch_assoc($result)) {
		    $sid = $row['place'];
		    // Title and content
		    if($sid == "ATTACHMENTS" or $sid == "INTRO" or $sid == "OUTRO" or $sid == "LITERATURE") {
		        $sections[$sid] = array($row['name'], $row['content']);
		    } else {
		        $sections[$sid] = array('Глава '.$chapter_counter.'. '.$row['name'], $row['content']);
		        $chapter_counter++;
		        $text_counter = 1;
		    }
		    
		    // Text and media
		    $subresult = $mysql->query("SELECT * FROM `$project_ref` WHERE place='$sid' AND type!='SECTION'");
		    while($subrow = mysqli_fetch_assoc($subresult)) {
		        if($subrow['type'] == 'TEXT') {
		            if($sid == "ATTACHMENTS") {
		                $sections[$sid][2][$subrow['ID']] = array($subrow['type'], 'Приложение '.$alphas[$attachment_counter].'. '.$subrow['name'], $subrow['content']);
		                $attachment_counter++;
		            } else {
		                $sections[$sid][2][$subrow['ID']] = array($subrow['type'], ($chapter_counter-1).".".$text_counter.". ".$subrow['name'], $subrow['content']);
		                $text_counter++;
		            }
		        } else {
		            $sections[$sid][2][$subrow['ID']] = array($subrow['type'], $subrow['name'], $subrow['content']);
		        }
		    }
		}
	    $mysql->close();
	}
	return True;
}

function success_build($project_ref = "") {
    include '../base.php';
	$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
	$mysql->query("UPDATE `projects` SET `build` = '1' WHERE `referral` = '$project_ref'");
	$mysql->close();
}

?>