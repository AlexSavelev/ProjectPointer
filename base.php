<?php

// DB
$db_host = "localhost";
$db_login = "u1514162_default";
$db_password = "5726smmcOsoPNaRIAx8I";
$db_base = "u1514162_default";
$db_projects = "u1514162_projects";

// QUERY Limits
$limit_com_query = 25;

// Project Limits
$limit_projects = 10;
$limit_sections = 10;
$limit_texts = 20;
$limit_imgs = 35;
$limit_files = 10;
$limit_img = 3; // In Mb
$limit_file = 25; // In Mb

/* CONTENT TYPES:
	- SECTION (place - PLACE, name - NAME, content - TEXT)
	- IMG (place - SECTION_ID, name - FILE_NAME, content - PATH)
	- M_QR (place - SECTION_ID, name - FILE_NAME, content - PATH)
*/
?>