<?php


	// mysql_connect($CFG->dbhost, $CFG->dbuser, $CFG->dbpass);
	// mysql_select_db($CFG->dbname);
	// Murphy Debug 20150211
	$CFG->con = mysqli_connect($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);

?>
