<?php
mb_internal_encoding("UTF-8");//cp1251

function checkNumetric($n, $mn, $mx) {
    if(!is_numeric($n)) {
        header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20загрузке%20числовых%20значений');
	    exit();
    }
    if(floatval($n) < $mn or floatval($n) > $mx) {
        header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20загрузке%20числовых%20значений');
	    exit();
    }
}
function checkFont($font, $style, $size) {
    if($font != "arial" and $font != "bahnschrift" and $font != "calibri" and $font != "georgia" and $font != "micross" and $font != "timesnewroman" and $font != "verdana") {
        header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20поиске%20подходящего%20шрифта');
	    exit();
    }
    if($style != "" and $style != "B" and $style != "I") {
        header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20поиске%20подходящего%20стиля%20шрифта');
	    exit();
    }
    if(!is_numeric($size)) {
        header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20загрузке%20числовых%20значений');
	    exit();
    }
    if(floatval($size) < 1 or floatval($size) > 30) {
        header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20загрузке%20числовых%20значений');
	    exit();
    }
}

// Parce SETTINGS
// -----------------------------------
$page_margin_left = filter_var(trim($_POST['margin-l']), FILTER_SANITIZE_STRING);
$page_margin_top = filter_var(trim($_POST['margin-t']), FILTER_SANITIZE_STRING);
$page_margin_right = filter_var(trim($_POST['margin-r']), FILTER_SANITIZE_STRING);
$page_margin_bottom = filter_var(trim($_POST['margin-b']), FILTER_SANITIZE_STRING);
checkNumetric($page_margin_left, 0, 100);
checkNumetric($page_margin_top, 0, 100);
checkNumetric($page_margin_right, 0, 100);
checkNumetric($page_margin_bottom, 0, 100);

$base_indent = filter_var(trim($_POST['base-indent']), FILTER_SANITIZE_STRING);
$base_lh = filter_var(trim($_POST['base-lh']), FILTER_SANITIZE_STRING);
checkNumetric($base_indent, 0, 100);
$base_indent .= 'mm';
checkNumetric($base_lh, 0.5, 5);
// Running title
$rt_placement = filter_var(trim($_POST['rt-placement']), FILTER_SANITIZE_STRING);
$rt_style = filter_var(trim($_POST['rt-fs']), FILTER_SANITIZE_STRING);
if($rt_placement != "tR" and $rt_placement != "tL" and $rt_placement != "bR" and $rt_placement != "bL") {
    header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20поиске%20нужного%20колонтитула');
    exit();
}
if($rt_style != "" and $rt_style != "B" and $rt_style != "I") {
    header('Location: setup.php?p='.$project_ref.'&error=Ошибка%20при%20поиске%20нужного%20колонтитула');
    exit();
}
// Title
$base_title_font = filter_var(trim($_POST['title-font']), FILTER_SANITIZE_STRING);
$base_title_font_style = filter_var(trim($_POST['title-style']), FILTER_SANITIZE_STRING);
$base_title_font_size = filter_var(trim($_POST['title-size']), FILTER_SANITIZE_STRING);
checkFont($base_title_font, $base_title_font_style, $base_title_font_size);
// Default
$base_font = filter_var(trim($_POST['base-font']), FILTER_SANITIZE_STRING);
$base_font_style = filter_var(trim($_POST['base-style']), FILTER_SANITIZE_STRING);
$base_size = filter_var(trim($_POST['base-size']), FILTER_SANITIZE_STRING);
checkFont($base_font, $base_font_style, $base_size);
// Author info
$base_author_font = filter_var(trim($_POST['author-font']), FILTER_SANITIZE_STRING);
$base_author_font_style = filter_var(trim($_POST['author-style']), FILTER_SANITIZE_STRING);
$base_author_font_size = filter_var(trim($_POST['author-size']), FILTER_SANITIZE_STRING);
checkFont($base_author_font, $base_author_font_style, $base_author_font_size);
// FOR TOC
$isTOC = false;
// -----------------------------------

require_once('TCPDF/tcpdf.php');

// Parce PROJECT
require_once('parcer.php');
$project_ref = $_POST['p'];

// Check OWNER
include '../base.php';
$mysql = new mysqli($db_host, $db_login, $db_password, $db_base);
$result = $mysql->query("SELECT * FROM projects WHERE referral='$project_ref'");
if (!$result || mysqli_num_rows($result) == 0) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
} else {
	$trueOwner = mysqli_fetch_assoc($result)['owner'];
}
if(empty($_COOKIE["user_id"]) or $_COOKIE["user_id"]!=$trueOwner) {
	$actual_header_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/my";
	$mysql->close();
	header('Location: '.$actual_header_link);
	exit();
}
// End check OWNER

if(!parce_project($project_ref)) {
	header('Location: setup.php?p='.$project_ref.'&error=Не%20удалось%20загрузить%20проект');
	exit();
}

class PP_PDF extends TCPDF {
    public function addTOC($page='', $numbersfont='', $filler='.', $toc_name='TOC', $style='', $color=array(0,0,0)) {
		$fontsize = $this->FontSizePt;
		$fontfamily = $this->FontFamily;
		$fontstyle = $this->FontStyle;
		$w = $this->w - $this->lMargin - $this->rMargin;
		$spacer = $this->GetStringWidth(chr(32)) * 4;
		$lmargin = $this->lMargin;
		$rmargin = $this->rMargin;
		$x_start = $this->GetX();
		$page_first = $this->page;
		$current_page = $this->page;
		$page_fill_start = false;
		$page_fill_end = false;
		$current_column = $this->current_column;
		if (TCPDF_STATIC::empty_string($numbersfont)) {
			$numbersfont = $this->default_monospaced_font;
		}
		if (TCPDF_STATIC::empty_string($filler)) {
			$filler = ' ';
		}
		if (TCPDF_STATIC::empty_string($page)) {
			$gap = ' ';
		} else {
			$gap = '';
			if ($page < 1) {
				$page = 1;
			}
		}
		$this->SetFont($numbersfont, $fontstyle, $fontsize);
		$numwidth = $this->GetStringWidth('00000');
		$maxpage = 0; //used for pages on attached documents
		foreach ($this->outlines as $key => $outline) {
			// check for extra pages (used for attachments)
			if (($this->page > $page_first) AND ($outline['p'] >= $this->numpages)) {
				$outline['p'] += ($this->page - $page_first);
			}
			if ($this->rtl) {
				$aligntext = 'R';
				$alignnum = 'L';
			} else {
				$aligntext = 'L';
				$alignnum = 'R';
			}
			if ($outline['l'] == 0) {
				$this->SetFont($fontfamily, $outline['s'].'', $fontsize);
			} else {
				$this->SetFont($fontfamily, $outline['s'], $fontsize - $outline['l']);
			}
			$this->SetTextColorArray($outline['c']);
			// check for page break
			$this->checkPageBreak(2 * $this->getCellHeight($this->FontSize));
			// set margins and X position
			if (($this->page == $current_page) AND ($this->current_column == $current_column)) {
				$this->lMargin = $lmargin;
				$this->rMargin = $rmargin;
			} else {
				if ($this->current_column != $current_column) {
					if ($this->rtl) {
						$x_start = $this->w - $this->columns[$this->current_column]['x'];
					} else {
						$x_start = $this->columns[$this->current_column]['x'];
					}
				}
				$lmargin = $this->lMargin;
				$rmargin = $this->rMargin;
				$current_page = $this->page;
				$current_column = $this->current_column;
			}
			$this->SetX($x_start);
			$indent = ($spacer * $outline['l']);
			if ($this->rtl) {
				$this->x -= $indent;
				$this->rMargin = $this->w - $this->x;
			} else {
				$this->x += $indent;
				$this->lMargin = $this->x;
			}
			$link = $this->AddLink();
			$this->SetLink($link, $outline['y'], $outline['p']);
			// write the text
			if ($this->rtl) {
				$txt = ' '.$outline['t'];
			} else {
				$txt = $outline['t'].' ';
			}
			$this->Write(0, $txt, $link, false, $aligntext, false, 0, false, false, 0, $numwidth, '');
			if ($this->rtl) {
				$tw = $this->x - $this->lMargin;
			} else {
				$tw = $this->w - $this->rMargin - $this->x;
			}
			$this->SetFont($numbersfont, $fontstyle, $fontsize);
			if (TCPDF_STATIC::empty_string($page)) {
				$pagenum = $outline['p'];
			} else {
				// placemark to be replaced with the correct number
				$pagenum = '{#'.($outline['p']).'}';
				if ($this->isUnicodeFont()) {
					$pagenum = '{'.$pagenum.'}';
				}
				$maxpage = max($maxpage, $outline['p']);
			}
			$fw = ($tw - $this->GetStringWidth($pagenum.$filler));
			$wfiller = $this->GetStringWidth($filler);
			if ($wfiller > 0) {
				$numfills = floor($fw / $wfiller);
			} else {
				$numfills = 0;
			}
			if ($numfills > 0) {
				$rowfill = str_repeat($filler, $numfills);
			} else {
				$rowfill = '';
			}
			if ($this->rtl) {
				$pagenum = $pagenum.$gap.$rowfill;
			} else {
				$pagenum = $rowfill.$gap.$pagenum;
			}
			// write the number
			$this->Cell($tw, 0, $pagenum, 0, 1, $alignnum, 0, $link, 0);
		}
		$page_last = $this->getPage();
		$numpages = ($page_last - $page_first + 1);
		// account for booklet mode
		if ($this->booklet) {
			// check if a blank page is required before TOC
			$page_fill_start = ((($page_first % 2) == 0) XOR (($page % 2) == 0));
			$page_fill_end = (!((($numpages % 2) == 0) XOR ($page_fill_start)));
			if ($page_fill_start) {
				// add a page at the end (to be moved before TOC)
				$this->addPage();
				++$page_last;
				++$numpages;
			}
			if ($page_fill_end) {
				// add a page at the end
				$this->addPage();
				++$page_last;
				++$numpages;
			}
		}
		$maxpage = max($maxpage, $page_last);
		if (!TCPDF_STATIC::empty_string($page)) {
			for ($p = $page_first; $p <= $page_last; ++$p) {
				// get page data
				$temppage = $this->getPageBuffer($p);
				for ($n = 1; $n <= $maxpage; ++$n) {
					// update page numbers
					$a = '{#'.$n.'}';
					// get page number aliases
					$pnalias = $this->getInternalPageNumberAliases($a);
					// calculate replacement number
					if (($n >= $page) AND ($n <= $this->numpages)) {
						$np = $n + $numpages;
					} else {
						$np = $n;
					}
					$na = TCPDF_STATIC::formatTOCPageNumber(($this->starting_page_number + $np - 1));
					$nu = TCPDF_FONTS::UTF8ToUTF16BE($na, false, $this->isunicode, $this->CurrentFont);
					// replace aliases with numbers
					foreach ($pnalias['u'] as $u) {
						$sfill = str_repeat($filler, max(0, (strlen($u) - strlen($nu.' '))));
						if ($this->rtl) {
							$nr = $nu.TCPDF_FONTS::UTF8ToUTF16BE(' '.$sfill, false, $this->isunicode, $this->CurrentFont);
						} else {
							$nr = TCPDF_FONTS::UTF8ToUTF16BE($sfill.' ', false, $this->isunicode, $this->CurrentFont).$nu;
						}
						$temppage = str_replace($u, $nr, $temppage);
					}
					foreach ($pnalias['a'] as $a) {
						$sfill = str_repeat($filler, max(0, (strlen($a) - strlen($na.' '))));
						if ($this->rtl) {
							$nr = $na.' '.$sfill;
						} else {
							$nr = $sfill.' '.$na;
						}
						$temppage = str_replace($a, $nr, $temppage);
					}
				}
				// save changes
				$this->setPageBuffer($p, $temppage);
			}
			// move pages
			$this->Bookmark($toc_name, 0, 0, $page_first, $style, $color);
			if ($page_fill_start) {
				$this->movePage($page_last, $page_first);
			}
			for ($i = 0; $i < $numpages; ++$i) {
				$this->movePage($page_last, $page);
			}
		}
	}
	
    public function Header() {
        global $isTOC;
        global $rt_placement;
        global $rt_style;
        global $base_font;
        
        if(substr($rt_placement, 0, 1) != 't') { return; }
        
        $rtitle_text_align = substr($rt_placement, 1, 1);
        $rtitle_font = $base_font;
        $rtitle_font_style = $rt_style;
        $rtitle_font_size = 11;
        
        $this->SetFont($rtitle_font, $rtitle_font_style, $rtitle_font_size);
        if($isTOC) {
            $c_page = 2;
        } else {
            $c_page = $this->getPage()+1;
        }
        $this->Cell(0, 0, $c_page, 0, 1, $rtitle_text_align);
    }

    public function Footer() {
        global $isTOC;
        global $rt_placement;
        global $rt_style;
        global $base_font;
        
        if(substr($rt_placement, 0, 1) != 'b') { return; }
        
        $rtitle_text_align = substr($rt_placement, 1, 1);
        $rtitle_font = $base_font;
        $rtitle_font_style = $rt_style;
        $rtitle_font_size = 11;
        
        $this->SetFont($rtitle_font, $rtitle_font_style, $rtitle_font_size);
        if($isTOC) {
            $c_page = 2;
        } else {
            $c_page = $this->getPage()+1;
        }
        $this->Cell(0, 0, $c_page, 0, 1, $rtitle_text_align);
    }
}

$pdf = new PP_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator('PPOINTER.RU');
$pdf->SetAuthor($pdf_author);
$pdf->SetTitle($pdf_title);
$pdf->SetSubject($pdf_subject);
$pdf->SetKeywords($pdf_keywords);

/*
$pdf->SetMargins($page_margin_left, 0, $page_margin_right, true);
$pdf->SetHeaderMargin($page_margin_top);
$pdf->SetFooterMargin($page_margin_bottom);
*/

if(substr($rt_placement, 0, 1) == 'b') {
    $pdf->SetMargins($page_margin_left, $page_margin_top, $page_margin_right, true);
    $pdf->SetFooterMargin($page_margin_bottom);
} else {
    $pdf->SetMargins($page_margin_left, $page_margin_top, $page_margin_right, true);
    $pdf->SetHeaderMargin($page_margin_top/2);
    $pdf->SetFooterMargin($page_margin_bottom);
}


$pdf->SetAutoPageBreak(TRUE, $page_margin_bottom);

//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setCellHeightRatio($base_lh);


// Title list
$pdf->SetAutoPageBreak(FALSE, $page_margin_bottom);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Place
$pdf->SetFont($base_title_font, $base_title_font_style, $base_title_font_size, '', false);
$title_list_width = 210 - $page_margin_left - $page_margin_right - 10;
$title_list = '<span>'.$place.'</span>';
$pdf->writeHTMLCell($title_list_width, 0, ceil(100-$title_list_width/2)+1, '', $title_list, 0, 0, 0, false, 'C', false);
// Title
$pdf->SetFont($base_title_font, $base_title_font_style, $base_title_font_size, '', false);
$title_list = mb_strtoupper($ptype).'<br/>по учебной дисциплине<br/>'.mb_strtoupper($subject).'<br/><br/>Тема: '.$pname;
$pdf->writeHTMLCell(0, 0, '', 105-$page_margin_top, $title_list, 0, 0, 0, false, 'C', false);
// Author
$pdf->SetFont($base_author_font, $base_author_font_style, $base_author_font_size, '', false);
$title_list_width = 210 - $page_margin_right - 70;
// $message = wordwrap($message, 25, '<br/>');
$author_representative = wordwrap($author_representative, 55, '<br/>');
$author_name = wordwrap($author_name, 55, '<br/>');
$manager_representative = wordwrap($manager_representative, 55, '<br/>');
$manager_name = wordwrap($manager_name, 55, '<br/>');
$title_list = '<p style="text-align:justify;">Выполнил:<br/>'.$author_representative.'<br/>'.$author_name.'<br/>Руководитель проекта:<br/>'.$manager_representative.'<br/>'.$manager_name.'</p>';
$pdf->writeHTMLCell(70, 0, $title_list_width, 155-$page_margin_top, $title_list, 0, 0, 0, false, 'L', false);
// Year
$pdf->SetFont($base_author_font, $base_author_font_style, $base_author_font_size, '', false);
$title_list = $year;
$pdf->writeHTMLCell(0, 0, '', 260-$page_margin_top, $title_list, 0, 0, 0, false, 'C', false);
// New
$pdf->lastPage();
$pdf->SetAutoPageBreak(TRUE, $page_margin_bottom);
$pdf->setPrintHeader(true);
$pdf->AddPage();
$pdf->setPrintFooter(true);

$p_styles = 'text-indent: '.$base_indent.';';
// Sections
function fill_images($str_a, &$media, &$pic_counter) {
    global $project_ref;
    global $base_indent;
    global $p_styles;
    $str = $str_a;
    
    $html = '';
    $finding_start = 0;
    while($i = mb_strpos($str, "{", $finding_start)) {
        $j = mb_strpos($str, "}", max($finding_start, $i));
        $ind = mb_substr($str, $i+1, $j-$i-1);
        
        if(mb_strlen($ind) > 100) {
            $finding_start = $j+1;
            continue;
        }
                
        $finding_start = 0;
                    
        if(ord($str[$i-1]) == 10) {
            $before = nl2br(mb_substr($str, 0, $i-2));
                        
        } else {
            $before = nl2br(mb_substr($str, 0, $i));
        }
                    
        $html .= '<p style="'.$p_styles.'">'.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', $before).'</p>';
        
        $file_a = array();
        foreach($media as &$me) {
            if( $me[1] == $ind ) {
                $file_a = $me;
            }
        }
                
        if($file_a[0] == "M_QR") {
            $img_src = 'QR_'.$file_a[2].'.png';
            $img_path = '../media/'.$project_ref.'/'.$img_src;
            
            if($file_a == array()) {
                $img_path = '../styles/img/file_empty.png';
            }   
            
            $html .= '<div height="100" style="text-align:center;"><img src="'.$img_path.'" height="100" align="middle" border="0" /></div><span style="text-align:center;"><i>Рис. '.$pic_counter.'. '.$file_a[1].'</i></span>';
        } else {
            $img_src = $file_a[2];
            $img_path = '../media/'.$project_ref.'/'.$img_src;
            
            if($file_a != array()) {
                $img_sz = getimagesize($img_path);
                if(!$img_sz) {
                    $finding_start = $j+1;
                    continue;
                }
                $img_height = $img_sz[1];
                if($img_height > 150) {
                    $img_height = 150;
                }
            } else {
                $img_height = 150;
                $img_path = '../styles/img/file_empty.png';
            }         
            
            $html .= '<div height="'.$img_height.'" style="text-align:center;"><img src="'.$img_path.'" height="'.$img_height.'" align="middle" border="0" /></div><span style="text-align:center;"><i>Рис. '.$pic_counter.'. '.$file_a[1].'</i></span>';
        }           
        $str = mb_substr($str, $j+1);
        if(ord($str[1]) == 10) {
            $str = mb_substr($str, 2);
        }
        $pic_counter += 1;
    }
    $html .= '<p style="'.$p_styles.'">'.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', nl2br($str)).'</p>';
    return $html;
}

$picture_counter = 1;

// INTRO
$extra_section = $sections['INTRO'];
$html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.'">'.$extra_section[0].'</h1>';
$html .= fill_images($extra_section[1], $extra_section[2], $picture_counter);
// INTRO EXTRA START
function add_point($str) {
    if(mb_substr($str, -1) != '.') {
        return $str.'.';
    } else {
        return $str;
    }
}
$intro_extra = '<p style="text-indent: '.$base_indent.';">';
$new_line_str = "<br>";
if($relevance != '') {
    $intro_extra .= '<b>Актуальность работы:</b> '.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', nl2br(add_point($relevance).$new_line_str));
}
if($novelty != '') {
    $intro_extra .= '<b>Новизна:</b> '.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', nl2br(add_point($novelty).$new_line_str));
}
if($s_object != '') {
    $intro_extra .= '<b>Объект исследования:</b> '.add_point($s_object).$new_line_str;
}
if($s_subject != '') {
    $intro_extra .= '<b>Предмет исследования:</b> '.add_point($s_subject).$new_line_str;
}
if($goal != '') {
    $intro_extra .= '<b>Цель работы:</b> '.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', nl2br(add_point($goal).$new_line_str));
}
if($tasks != '') {
    $intro_extra .= '<b>Задачи работы:</b><br>'.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', nl2br(add_point($tasks).$new_line_str));
}
if($question != '') {
    $intro_extra .= '<b>Гипотеза:</b> '.add_point($question).$new_line_str;
}
if($tags != '') {
    $intro_extra .= '<b>Вид проекта</b> – '.add_point(mb_strtolower(mb_substr($tags, 0, 1)).mb_substr($tags, 1)).$new_line_str;
}
if($product != '') {
    $intro_extra .= '<b>Готовый продукт</b> – '.add_point(mb_strtolower(mb_substr($product, 0, 1)).mb_substr($product, 1)).$new_line_str;
}
if($summary != '') {
    $intro_extra .= '<b>Краткое содержание:</b> '.str_replace("\t", '<span color="white" style="display: inline;">&tab;</span>', nl2br(add_point($summary).$new_line_str));
}
$intro_extra .= '</p>';
$html .= $intro_extra;
// INTRO EXTRA END
$pdf->SetFont($base_font, $base_font_style, $base_font_size, '', false);
$pdf->Bookmark($extra_section[0], 0, 0, '', '', array(0, 0, 0));
$pdf->writeHTML($html, true, false, false, false, 'J');
$pdf->lastPage();
$pdf->AddPage();

foreach ($sections as $section_key => &$section) {
    if($section_key == "INTRO" or $section_key == "OUTRO" or $section_key == "LITERATURE" or $section_key == "ATTACHMENTS") {
        continue;
    }
    
    $html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.'">'.$section[0].'</h1>';
    $pdf->Bookmark($section[0], 0, 0, '', '', array(0, 0, 0));
    $pdf->writeHTML($html, true, false, false, false, 'J');
    $pdf->Ln();
    
    if($section[1] != '__TRUE__') {
        $html = fill_images('<br>'.$section[1], $section[2], $picture_counter);
        
        $pdf->SetFont($base_font, $base_font_style, $base_font_size, '', false);
        $pdf->writeHTML($html, true, false, false, false, 'J');
    } else {
        if(is_array($section[2])) {
        foreach ($section[2] as &$data) {
            if($data[0]=="TEXT") {
                $html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.';">'.$data[1].'</h1>';
                $html .= fill_images($data[2].'<br>', $section[2], $picture_counter);
                
                $pdf->SetFont($base_font, $base_font_style, $base_font_size, '', false);
                $pdf->Bookmark($data[1], 1, 0, '', '', array(0, 0, 0));
                $pdf->writeHTML($html, true, false, false, false, 'J');
                $pdf->Ln();
            }
        }
        }
    }
    $pdf->lastPage();
    $pdf->AddPage();
}

// OUTRO
$extra_section = $sections['OUTRO'];
$html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.'">'.$extra_section[0].'</h1>';
$html .= fill_images($extra_section[1], $extra_section[2], $picture_counter);
$pdf->SetFont($base_font, $base_font_style, $base_font_size, '', false);
$pdf->Bookmark($extra_section[0], 0, 0, '', '', array(0, 0, 0));
$pdf->writeHTML($html, true, false, false, false, 'J');
$pdf->lastPage();
// LITERATURE
$extra_section = $sections['LITERATURE'];
$html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.'">'.$extra_section[0].'</h1>';
//$html .= fill_images($extra_section[1], $extra_section[2], $picture_counter);
$literature_list = mb_split("/\r\n|\n|\r/", $extra_section[1]);
$literature_count = count($literature_list);
for($i = 0; $i < $literature_count; $i++) {
    $html .= '<li style="text-indent: '.$base_indent.'">'.($i+1).'. '.$literature_list[$i].'</li>';
}
if($extra_section[1] != '') {
    $pdf->AddPage();
    $pdf->SetFont($base_font, $base_font_style, $base_font_size, '', false);
    $pdf->Bookmark($extra_section[0], 0, 0, '', '', array(0, 0, 0));
    $pdf->writeHTML($html, true, false, false, false, 'J');
    $pdf->lastPage();
}
// ATTACHMENTS
$extra_section = $sections['ATTACHMENTS'];
if(is_array($extra_section[2])) {
    foreach ($extra_section[2] as &$data) {
        if($data[0]=="TEXT") {
            $pdf->AddPage();

            $html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.'">'.$data[1].'</h1>';
        
            $html .= fill_images($data[2], $extra_section[2], $picture_counter);
        
            $pdf->SetFont($base_font, $base_font_style, $base_font_size, '', false);
            $pdf->Bookmark($data[1], 0, 0, '', '', array(0, 0, 0));
            $pdf->writeHTML($html, true, false, false, false, 'J');
            $pdf->lastPage();
        }
    }
}
// TOC
if(substr($rt_placement, 0, 1) == 't') {
    $isTOC = true;
}
$pdf->addTOCPage();
$html = '<h1 style="text-indent: '.$base_indent.'; font-size: '.$base_title_font_size.'">Оглавление</h1><p></p>';
$pdf->writeHTML($html, true, false, false, false, 'J');
$pdf->Ln();
$pdf->setCellHeightRatio($base_lh*1.5);
$pdf->SetFont($base_font, $base_font_style, $base_title_font_size, '', false);
$isTOC = true;
$pdf->addTOC(2, $base_font, '.', 'Оглавление', '', array(0,0,0));
$pdf->endTOCPage();
$isTOC = false;


// COMPLETE!
$pdf->Output(__DIR__.'/../media/'.$project_ref.'/Project.pdf', 'F');
success_build($project_ref);
header('Location: success.php?p='.$project_ref);
exit();
// IT`S SUCCESS!