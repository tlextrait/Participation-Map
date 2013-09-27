<?php
	
	/*
	PARTICIPATION MAP
	Copyright (C) 2011-2012 Brant Knutzen, Thomas Lextrait,
	
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
	*/
	
	// DISPLAY ALL ERRORS AND WARNINGS
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	/*
	========================================
	Configuration file saver
	========================================
	*/
	
	function boolStr($val){
		if($val){return "true";}else{return "false";}
	}
	
	// RESOLVE BOOLEANS
	$headerDisplayTitle = 					boolStr(isset($_POST["headerDisplayTitle"]));
	$headerDisplaySubTitle = 				boolStr(isset($_POST["headerDisplaySubTitle"]));
	$headerDisplaySubSubTitle = 			boolStr(isset($_POST["headerDisplaySubSubTitle"]));
	$headerDisplayCourse = 					boolStr(isset($_POST["headerDisplayCourse"]));
	$headerDisplayForum = 					boolStr(isset($_POST["headerDisplayForum"]));
	$headerDisplayRatingPeriod = 			boolStr(isset($_POST["headerDisplayRatingPeriod"]));
	$headerDisplayCourseParticipants = 		boolStr(isset($_POST["headerDisplayCourseParticipants"]));
	$headerDisplayFeedback = 				boolStr(isset($_POST["headerDisplayFeedback"]));
	$headerDisplayPostsPerStudent = 		boolStr(isset($_POST["headerDisplayPostsPerStudent"]));
	$headerDisplayPostsPerDiscussion = 		boolStr(isset($_POST["headerDisplayPostsPerDiscussion"]));
	$headerDisplayTransactivity = 			boolStr(isset($_POST["headerDisplayTransactivity"]));
	
	$showDays =		boolStr(isset($_POST["showDays"]));
	$showDayLine = 	boolStr(isset($_POST["showDayLine"]));
	
	$groupPost = 	boolStr(isset($_POST["groupPost"]));
	$groupInfo = 	boolStr(isset($_POST["groupInfo"]));

	$studentNameUpperCase = boolStr(isset($_POST["studentNameUpperCase"]));
	$studentBorder = boolStr(isset($_POST["studentBorder"]));
	$studentSmartBorderColor = boolStr(isset($_POST["studentSmartBorderColor"]));
	$studentRounded = boolStr(isset($_POST["studentRounded"]));
	
	$arrows = boolStr(isset($_POST["arrows"]));
	$arrowColors = boolStr(isset($_POST["arrowColors"]));
	$bezier = boolStr(isset($_POST["group1"]) && $_POST["group1"]=="bezier");

	// RESOLVE RESOLUTION
	$resolution = $_POST["resolution"];
	$headerTitleSize = $resolution * $_POST["headerTitleSize"];
	$headerSubTitleSize = $resolution * $_POST["headerSubTitleSize"];
	$headerTextSize = $resolution * $_POST["headerTextSize"];
	
	$discussionRepliesFontSize = $resolution * $_POST["discussionRepliesFontSize"];
	$discussionNameFontSize = $resolution * $_POST["discussionNameFontSize"];
	
	$marginTop = abs($_POST["marginTop"] * $resolution);
	$marginBottom = abs($_POST["marginBottom"] * $resolution);
	$marginLeft = abs($_POST["marginLeft"] * $resolution);
	$marginRight = abs($_POST["marginRight"] * $resolution);
	
	$axisX = abs($_POST["axisX"] * $resolution);
	$axisXthick = abs($_POST["axisXthick"] * $resolution);
	$axisY = abs($_POST["axisY"] * $resolution);
	$axisYthick = abs($_POST["axisYthick"] * $resolution);
	
	$dayHeight = abs($_POST["dayHeight"] * $resolution);
	$dayLineThick = abs($_POST["dayLineThick"] * $resolution);
	$discussionLineThick = abs($_POST["discussionLineThick"] * $resolution);
	$dayFontSize = abs($_POST["dayFontSize"] * $resolution);
	
	$studentBorderThickness = abs($_POST["studentBorderThickness"] * $resolution);
	$studentRoundedRadius = abs($_POST["studentRoundedRadius"] * $resolution);
	$studentMarginVertical = abs($_POST["studentMarginVertical"] * $resolution);
	$studentMarginHorizontal = abs($_POST["studentMarginHorizontal"] * $resolution);
	$studentFontSize = abs($_POST["studentFontSize"] * $resolution);
	$studentSeparation = abs($_POST["studentSeparation"] * $resolution);
	$arrowThick = abs($_POST["arrowThick"] * $resolution);
	
	/*
	========================================
	Create config file
	========================================
	*/
	
	$config = "
	
		\$demo = false;
		\$resolution = $_POST[resolution];			// Resolution multiplier (For settings panel)
		\$rounding = $_POST[rounding];				// Rounding for stats

		\$background = 255,255,255;				// Color of the background
		\$colorX = 0,0,0;						// Color of the X axis
		\$colorY = 0,0,0;						// Color of the Y axis
		\$colorDiscussionLine = 200,200,200;	// Color of the vertical discussion separating line
		\$colorArrow = 0,0,0;					// Color of arrows/lines

		// White space between the graph and the side of the picture (in pixels)
		\$marginTop = $marginTop;
		\$marginBottom = $marginBottom;
		\$marginLeft = $marginLeft;
		\$marginRight = $marginRight;

		\$axisX = $axisX;				// Positions of the X axis in pixels
		\$axisY = $axisY;				// Positions of the Y axis in pixels
		\$axisXthick = $axisXthick;			// Thickness of X axis in pixels
		\$axisYthick = $axisYthick;			// Thickness of Y axis in pixels
		\$discussionLineThick = $discussionLineThick;	// Thickness of the discussion separating line
		\$displayDiscussionName = true;		// Display discussion name labels
		\$displayDiscussionReplies = true; // Display number of replies label
		\$discussionNameFontSize = $discussionNameFontSize;
		\$discussionNameFont = '$_POST[discussionNameFont]';
		\$discussionRepliesFontSize = $discussionRepliesFontSize;
		\$discussionRepliesFont = '$_POST[discussionRepliesFont]';
		\$groupPost = $groupPost;		// First post of discussion is Group Post?
		\$groupInfo = $groupInfo;		// Display group stats?

		\$arrows = $arrows;				// Use arrows instead of simple lines?
		\$arrowThick = $arrowThick;		// Thickness of arrows in pixels
		\$arrowColors = $arrowColors;	// Use colors for arrows?
		\$bezier = $bezier;				// Use bezier curves?

		\$firstDay = $_POST[firstDay];			// Number of the first Day
		\$showDays = $showDays;					// Display Day numbers?
		\$dayFont = \"$_POST[dayFont]\";		// Font to use for numbering
		\$dayFontSize = $dayFontSize;	// Font size for numbering
		\$dayHeight = $dayHeight;				// Day spacing in pixels
		\$dayLineThick = $dayLineThick;			// Day separating line thickness in pixels
		\$showDayLine = $showDayLine;			// Show a horizontal line to separate Days?

		\$colorDayNum = 0,0,0;					// Color of the Day numbers
		\$colorDayLine = 200,200,200;			// Color of the horizontal line separating Days
		\$colorDeadLine = 200,0,0;				// Color of the horizontal line for the Dead Line
		\$colorStartLine = 200,0,0;				// Color of the horizontal line for the Start Line

		\$studentNameDisplay = \"$_POST[studentNameDisplay]\";	// Can be: firstname, lastname, both, username
		\$studentNameUpperCase = $studentNameUpperCase;
		\$studentNameMaxChar = 12;
		\$studentBorder = $studentBorder;					// Use a border around the student blocks?
		\$studentBorderThickness = $studentBorderThickness;			// Thickness of the border around student blocks (in pixels)
		\$studentSmartBorderColor = $studentSmartBorderColor;		// If true, will use Black or White borders depending on student assigned color
		\$studentRounded = $studentRounded;				// Use rounded corners?
		\$studentRoundedRadius = $studentRoundedRadius;			// Rounded corner radius in pixels
		\$studentMarginVertical = $studentMarginVertical;			// Margins between text and top/bottom borders
		\$studentMarginHorizontal = $studentMarginHorizontal;			// Margins between text and left/right borders
		\$studentFont = \"$_POST[studentFont]\";				// Font to write Student's name
		\$studentFontSize = $studentFontSize;					// Font size
		\$studentSeparation = $studentSeparation;				// Space between students, in pixels
		\$displayProfilePics = true;			// Display profile pictures under the X axis

		\$colorStudentBorder = 0,0,0;			// Color of the student block border

		\$headerFont = \"$_POST[headerFont]\";

		\$headerTitleSize = $headerTitleSize;
		\$headerSubTitleSize = $headerSubTitleSize;
		\$headerSubSubTitleSize = $headerSubTitleSize;
		\$headerTextSize = $headerTextSize;

		\$colorTitle = 0,0,0;
		\$colorSubTitle = 0,0,0;
		\$colorSubSubTitle = 0,0,0;
		\$colorHeader = 0,0,0;

		\$headerTitle = \"$_POST[headerTitle]\";
		\$headerDisplayTitle = $headerDisplayTitle;
		\$headerSubTitle = \"$_POST[headerSubTitle]\";
		\$headerDisplaySubTitle = $headerDisplaySubTitle;
		\$headerSubSubTitle = \"$_POST[headerSubSubTitle]\";
		\$headerDisplaySubSubTitle = $headerDisplaySubSubTitle;

		\$headerDisplayCourse = $headerDisplayCourse;
		\$headerDisplayForum = $headerDisplayForum;
		\$headerDisplayRatingPeriod = $headerDisplayRatingPeriod;
		\$headerDisplayCourseParticipants = $headerDisplayCourseParticipants;
		\$headerDisplayFeedback = $headerDisplayFeedback;
		\$headerDisplayPostsPerStudent = $headerDisplayPostsPerStudent;
		\$headerDisplayPostsPerDiscussion = $headerDisplayPostsPerDiscussion;
		\$headerDisplayTransactivity = $headerDisplayTransactivity;
	
	";
	
	$userid = $USER->id;
	
	if(file_exists($CFG->dataroot."/participation_map/config/user$userid.bgs")){unlink($CFG->dataroot."/participation_map/config/user$userid.bgs");}
	
	$file = fopen($CFG->dataroot."/participation_map/config/user$userid.bgs", "w");
	fwrite($file, $config);
	fclose($file);


?>