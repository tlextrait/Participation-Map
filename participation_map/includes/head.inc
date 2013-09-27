<!-- UTF8 Support -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="css/main.css" />	

<title>Participation Map</title>

<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	// Get Moodle config info
	require_once("../config.php");
	
	// Include classes
	include("functions.inc");
	include("classes/post.class");
	include("classes/discussion.class");
	include("classes/student.class");
	include("classes/plot.class");
	include("classes/forum.class");
	include("classes/part_map.class");
	
	// Participation Map reporting center
	//include("reporter.inc");
	
	// Get course id
	if(isset($_GET["course"])){
		$course = $_GET["course"];
	}else{$course = false;}
	
	$prefix = $CFG->prefix;
	$moodleURL = $CFG->wwwroot;
	
	// Require login for that course
	require_login($course);
	
	// Create moodle data directory if necessary
	if(!file_exists($CFG->dataroot."/participation_map")){
		mkdir($CFG->dataroot."/participation_map");
		if(file_exists($CFG->dataroot."/participation_map")){
			mkdir($CFG->dataroot."/participation_map/config");
			mkdir($CFG->dataroot."/participation_map/quickplots");
			mkdir($CFG->dataroot."/participation_map/temp");
			// copy default settings into it
			copy("config/default.bgs", $CFG->dataroot."/participation_map/config/default.bgs");
		}
	}
	
	// Login to MySQL
	include("mysql/login.inc");

?>