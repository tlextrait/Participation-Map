<!--
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
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php require_once "includes/head.inc"; ?>
</head>
<body>

	<div id="main" class="s1000">
		
		<?php require_once "includes/heading.inc"; ?>
		
		<?php require_once "includes/navbar1000.inc"; ?>
	
		<div class="block1000">
			<?php
				if(isUserInstructor($course)){
					if(isset($_GET["course"]) && is_numeric($_GET["course"])){
						if(isset($_GET["forum"]) && is_numeric($_GET["forum"])){
						
							// RAM limit to 4Gb
							ini_set("memory_limit","4000M");
						
							// Set max execution time to 300 seconds
							set_time_limit(300); 
					
							// DISPLAY ALL ERRORS AND WARNINGS
							error_reporting(E_ALL);
							ini_set('display_errors', '1');
						
							// Load config
							require_once "includes/config_loader.inc";
					
							// Copy vars
							$course = $_GET["course"];
							$forum = $_GET["forum"];					
					
							/*
							===================================
							Get Forum
							===================================
							*/
							require_once "mysql/login.inc";
							$table = $prefix."forum";
							$query = mysqli_query($CFG->con, "SELECT assesstimestart, assesstimefinish, name FROM $table WHERE id=$forum");
							$query = mysqli_fetch_array($query);
					
							if(!$query){
								echo "
								<h2 class='red'>Error</h2>
								<span class='red'>Error: it was impossible to find this forum in the database. Please try another forum.</span><br/>
								&gt; <a href='index.php?course=$course'>Go back to Forums</a>";
							}else{
						
								// Find start/end date of Forum
								if(is_numeric($query["assesstimestart"]) && $query["assesstimestart"] > 1){
									$time_start = $query["assesstimestart"];
									$time_end = $query["assesstimefinish"];
								
									$NO_ASSESS_PERIOD = false;
								}else{
								
									$NO_ASSESS_PERIOD = true;
								
									$time_end = 0;
									$time_start = 0;
								
									// Find discussion ID's of this forum
									$table = $prefix."forum_discussions";
									$queryD = mysqli_query($CFG->con, "SELECT id FROM $table WHERE forum=$forum");
									while($tDisc = mysqli_fetch_array($queryD)){
								
										$disc = $tDisc["id"];
									
										// Find end time
										$table = $prefix."forum_posts";
										$query3 = mysqli_fetch_row(mysqli_query($CFG->con, "SELECT MAX(created) FROM $table WHERE discussion=$disc"));
								 
										if($query3[0] > $time_end){$time_end = $query3[0];}
																
										// Find start time
										$table = $prefix."forum_posts";
										$query4 = mysqli_fetch_row(mysqli_query($CFG->con, "SELECT MIN(created) FROM $table WHERE discussion=$disc"));
								
										if($time_start == 0){$time_start = $query4[0];}
										else if($query4[0] < $time_start){$time_start = $query4[0];}
									}
								}
							
								if(isset($_GET["start"])){$time_start=$_GET["start"];}
								if(isset($_GET["end"])){$time_end=$_GET["end"];}
							
								$days = round(($time_end-$time_start)/3600/24);
							
								// Can we count the days?
								if($days > 0){
								
									// Count users in the course
									$table = $prefix."course_display";
									$result = mysqli_query($CFG->con, "SELECT id FROM $table WHERE course=$course;");
									$totalStudents = mysqli_num_rows($result);

									// Store entire forum
									$cForum = new BS_Forum();
									$cForum->id = $forum;
									$cForum->course = $course;

									/*
									===================================
									Prepare Axis recessions
									===================================
									*/

									// AXIS X recession
									$axisX = $pm["axisX"];

									// Add height for student profile pic and its margin from X axis
									$axisX += $pm["studentSeparation"];

									// Add height for student name label and its margin from profile picture
									$heightS = BushGraph::getTextHeight("Sample Name", $pm["studentFontSize"], $pm["studentFont"]);
									$axisX += 1 * $pm["studentSeparation"] / 6 + $heightS;

									// Add height for forum name label
									$heightF = BushGraph::getTextHeight("Sample Name", $pm["discussionNameFontSize"], $pm["discussionNameFont"]);
									$axisX += 2 * $pm["studentSeparation"] / 6 + $heightF;

									// Number of replies label
									$heightR = BushGraph::getTextHeight("(0 replies)", $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
									$axisX += $heightR + $heightF/2;
								
									// Group stats labels
									if($pm["groupInfo"]){
										$axisX += 4*$heightR + 3*$heightR/2 + 1.5*$heightR; // height for 4 labels; height for 3 spacers; height for top spacer
									}

									// AXIS Y recession
									$axisY = $pm["axisY"];

									// Compute maximum day label width
									$dayWidth = BushGraph::getTextWidth("Day ".$days, $pm["dayFontSize"], $pm["dayFont"]);
									$axisY += 2*$dayWidth;

									/* ======================================= */

									// Get discussions
									$table = $prefix."forum_discussions";
									$query = mysqli_query($CFG->con, "SELECT id, name, firstpost, userid FROM $table WHERE forum=$forum");
									while($tDisc = mysqli_fetch_array($query)){

										// Create discussion
										$cDisc = new BS_Discussion($tDisc["name"]);
										$cDisc->id = $tDisc["id"];
										$cDisc->firstPost = $tDisc["firstpost"];
										$cDisc->userid = $tDisc["userid"];
										$discID = $cDisc->id;

										// Find all posts of that discussion
										$table = $prefix."forum_posts";
										$query2 = mysqli_query($CFG->con, "SELECT id, discussion, parent, userid, created, subject, message, totalscore FROM $table WHERE discussion=$discID");
										while($tPost = mysqli_fetch_array($query2)){

											// Create post
											$cPost = new BS_Post();
											$cPost->id = $tPost["id"];
											$cPost->parent = $tPost["parent"];
											$cPost->author = $tPost["userid"];
											$cPost->created = $tPost["created"];
											$cPost->subject = $tPost["subject"];
											$cPost->message = $tPost["message"];
											$cPost->score = $tPost["totalscore"];
										
											if($cPost->parent==0 && $pm["groupPost"]){
												$cPost->created = $time_start;
												$cPost->author = "disc".$tPost["discussion"];
											}
										
											// Fix time created
											if($cPost->created > $time_end){$cPost->created = $time_end + 3600*12;} // goes one half day after deadline
											if($cPost->created < $time_start){$cPost->created = $time_start;}

											// Add to discussion & forum
											$cDisc->addPost($cPost);
											$cForum->addPostStat($cPost);

											// Is author registered with discussion?
											if(!$cDisc->hasStudentId($cPost->author)){
											
												if($cPost->parent != 0 || !$pm["groupPost"]){
													// Get Student
													$author = $cPost->author;
													$table = $prefix."user";
													$query3 = mysqli_query($CFG->con, "SELECT firstname, lastname, username, idnumber, email, department FROM $table WHERE id=$author");
													$tStudent = mysqli_fetch_array($query3);

													// Create student
													// (Assign student color and border color after plot is created)
													if($pm["studentNameDisplay"]=="both"){
														$name = $tStudent["firstname"]." ".$tStudent["lastname"];
													}else if($pm["studentNameDisplay"]=="firstname"){
														$name = $tStudent["firstname"];
													}else if($pm["studentNameDisplay"]=="lastname"){
														$name = $tStudent["lastname"];
													}else if($pm["studentNameDisplay"]=="username"){
														$name = $tStudent["username"];
													}else{
														$name = $tStudent["firstname"];
													}

													if($pm["studentNameUpperCase"]){
														$name = strToUpper($name);
													}

													$cStudent = new BS_Student($name);
													$cStudent->id = $author;
													$cStudent->xLocation = ($cForum->studentCols+1+$cForum->dCount) * $pm["studentSeparation"] + $pm["marginLeft"] + $axisY;
													$cStudent->discussion = $cDisc->id;
													$cStudent->showBorder = $pm["studentBorder"];
													$cStudent->borderThick = $pm["studentBorderThickness"];
													$cStudent->smartBorderColor = $pm["studentSmartBorderColor"];
													$cStudent->rounded = $pm["studentRounded"];
													$cStudent->radius = $pm["studentRoundedRadius"];
													$cStudent->font = $pm["studentFont"];
													$cStudent->fontSize = $pm["studentFontSize"];
													$cStudent->marginVertical = $pm["studentMarginVertical"];
													$cStudent->marginHorizontal = $pm["studentMarginHorizontal"];
												
													// Add to total student columns
													$cForum->studentCols++;					

													// Add student to global list?
													if(!$cForum->hasStudent($cStudent->id)){
														$cStudent->spectrumIndex = $cForum->totalStudents+1;
														$cForum->addSpectrum($cStudent->spectrumIndex);
														$cForum->addStudent($cStudent->id);
													}else{
														$cStudent->spectrumIndex = $cForum->getSpectrumFromId($cStudent->id);
													}

													// Add student to discussion
													$cDisc->addStudent($cStudent);
												}
											}
										}

										// Add discussion to Forum
										$cForum->addDiscussion($cDisc);
									}

									/*
									======================================================================
									Prepare headers
									======================================================================
									*/

									$headerHeight = 0;
									$textH = BushGraph::getTextHeight("Regular Text", $pm["headerTextSize"], $pm["headerFont"]);
									$titles = false;

									// Title
									if($pm["headerDisplayTitle"]){
										$titleH = BushGraph::getTextHeight($pm["headerTitle"], $pm["headerTitleSize"], $pm["headerFont"]);
										$headerHeight += $titleH + round($titleH / 3);
										$titles = true;
									}

									// Subtitle
									if($pm["headerDisplaySubTitle"]){
										$subTitleH = BushGraph::getTextHeight($pm["headerSubTitle"], $pm["headerSubTitleSize"], $pm["headerFont"]);
										$headerHeight += $subTitleH + round($subTitleH / 2);
										$titles = true;
									}

									// SubSubtitle
									if($pm["headerDisplaySubSubTitle"]){
										$subsubTitleH = BushGraph::getTextHeight($pm["headerSubSubTitle"], $pm["headerSubSubTitleSize"], $pm["headerFont"]);
										$headerHeight += $subsubTitleH + round($subsubTitleH / 2);
										$titles = true;
									}

									$statText = $headerHeight;

									// Stats text
									if($pm["headerDisplayCourse"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayForum"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayRatingPeriod"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayCourseParticipants"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayFeedback"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayPostsPerStudent"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayPostsPerDiscussion"]){$headerHeight += $textH + $textH/2;}
									if($pm["headerDisplayTransactivity"]){$headerHeight += $textH + $textH/2;}

									// Skip a line after titles if stats are displayed
									if($statText != $headerHeight && $titles = true){
										$headerHeight += $textH;
									}

									// Add some margin if any text is to be written
									if($headerHeight > 0){
										$headerHeight += $pm["marginTop"];
									}
								
									/*
									===================================
									Space between Start Line and X Axis
									===================================
									*/
									if($pm["groupPost"]){
										$spacer =  3 * (2 * $pm["studentMarginVertical"] + BushGraph::getTextHeight("Group Post", $pm["studentFontSize"], $pm["studentFont"]));
									}else{
										$spacer = 0;
									}
								
									/*
									===================================
									Compute sizes of the graph
									===================================
									*/
									$height = $headerHeight + ($days+1) * $pm["dayHeight"] + $pm["marginTop"] + $pm["marginBottom"] + $axisX + $spacer;
									$width = ($cForum->studentCols+$cForum->dCount)*$pm["studentSeparation"] + $pm["marginLeft"] + $pm["marginRight"] + $axisY;

									/*
									===================================
									NEW BUSHGRAPH
									===================================
									*/
									define("WIDTH", $width);
								    define("HEIGHT", $height);
									$plot = new BushGraph(WIDTH, HEIGHT);

									/*
									===================================
									Create colors
									===================================
									*/
									$colors = array();
									$colors["white"] = 			$plot->defineColor(255, 255, 255);
									$colors["black"] = 			$plot->defineColor(0, 0, 0);
									$colors["red"] = 			$plot->defineColor(255, 0, 0);
									$colors["back"] = 			$plot->defineColor($pm["background"]["R"], $pm["background"]["G"], $pm["background"]["B"]);
									$colors["Y"] = 				$plot->defineColor($pm["colorY"]["R"], $pm["colorY"]["G"], $pm["colorY"]["B"]);
									$colors["X"] = 				$plot->defineColor($pm["colorX"]["R"], $pm["colorX"]["G"], $pm["colorX"]["B"]);
									$colors["dayNum"] = 		$plot->defineColor($pm["colorDayNum"]["R"], $pm["colorDayNum"]["G"], $pm["colorDayNum"]["B"]);
									$colors["dayLine"] =		$plot->defineColor($pm["colorDayLine"]["R"], $pm["colorDayLine"]["G"], $pm["colorDayLine"]["B"]);
									$colors["deadLine"] =		$plot->defineColor($pm["colorDeadLine"]["R"], $pm["colorDeadLine"]["G"], $pm["colorDeadLine"]["B"]);
									$colors["startLine"] =		$plot->defineColor($pm["colorStartLine"]["R"], $pm["colorStartLine"]["G"], $pm["colorStartLine"]["B"]);
									$colors["arrow"] =			$plot->defineColor($pm["colorArrow"]["R"], $pm["colorArrow"]["G"], $pm["colorArrow"]["B"]);
									$colors["studentBorder"] = 	$plot->defineColor($pm["colorStudentBorder"]["R"], $pm["colorStudentBorder"]["G"], $pm["colorStudentBorder"]["B"]);
									$colors["discussionLine"] = $plot->defineColor($pm["colorDiscussionLine"]["R"], $pm["colorDiscussionLine"]["G"], $pm["colorDiscussionLine"]["B"]);
									$colors["title"] = 			$plot->defineColor($pm["colorTitle"]["R"], $pm["colorTitle"]["G"], $pm["colorTitle"]["B"]);
									$colors["subtitle"] = 		$plot->defineColor($pm["colorSubTitle"]["R"], $pm["colorSubTitle"]["G"], $pm["colorSubTitle"]["B"]);
									$colors["subsubtitle"] = 	$plot->defineColor($pm["colorSubSubTitle"]["R"], $pm["colorSubSubTitle"]["G"], $pm["colorSubSubTitle"]["B"]);
									$colors["header"] = 		$plot->defineColor($pm["colorHeader"]["R"], $pm["colorHeader"]["G"], $pm["colorHeader"]["B"]);

									// Background
									$plot->setBackground($colors["back"]);

									// Draw axis
									$plot->drawLine($pm["marginLeft"]+$axisY, HEIGHT-$pm["marginBottom"], $pm["marginLeft"]+$axisY, $pm["marginTop"]+$headerHeight, $colors["Y"], $pm["axisYthick"]);
									$plot->drawLine($pm["marginLeft"], HEIGHT-$pm["marginBottom"]-$axisX, WIDTH-$pm["marginRight"], HEIGHT-$pm["marginBottom"]-$axisX, $colors["X"], $pm["axisXthick"]);

									// Draw day lines and write day labels
									for($i=1; $i<=$days; $i++){
										// Draw the Day Line
										$yLine = $i * $pm["dayHeight"] + $pm["marginTop"] + $headerHeight;
										if($i==1){
											// Deadline
											$plot->drawLine($pm["marginLeft"], $yLine, WIDTH-$pm["marginRight"], $yLine, $colors["deadLine"], $pm["dayLineThick"]);
										}else{
											if($pm["showDayLine"]){
												// Any other day
												$plot->drawLine($pm["marginLeft"], $yLine, WIDTH-$pm["marginRight"], $yLine, $colors["dayLine"], $pm["dayLineThick"]);
											}
										}
										// Write Day number

										if($pm["showDays"]){
											$cDay = $days - $i + $pm["firstDay"];
											$dayBox = $plot->getTextHeight("Day $cDay", $pm["dayFontSize"], $pm["dayFont"]);
											$plot->write("Day $cDay", $pm["dayFontSize"], $pm["dayFont"], $colors["black"], $pm["marginLeft"], $yLine + $pm["dayHeight"]/2 + $dayBox/2);
										}
									}
								
									if($pm["groupPost"]){
										// Draw Start Line
										$yLine = $i * $pm["dayHeight"] + $pm["marginTop"] + $headerHeight;
										$plot->drawLine($pm["marginLeft"], $yLine, WIDTH-$pm["marginRight"], $yLine, $colors["startLine"], $pm["dayLineThick"]);
									}

									/*
									======================================================================
									DRAW STUDENTS and ARROWS
									======================================================================
									*/

									// Count width, for drawing discussion separators
									$widthCounter = $pm["marginLeft"] + $axisY;

									// Loop through discussions
									for($i=0; $i<$cForum->dCount; $i++){
										if($i>0){
											// Draw a vertical line to end previous conversation
											$prevD = $cForum->discussions[$i-1];
											$widthCounter += ($prevD->studentCount+1)*$pm["studentSeparation"];
											$plot->drawLine($widthCounter, HEIGHT-$pm["marginBottom"], $widthCounter, $pm["marginTop"]+$headerHeight, $colors["discussionLine"], $pm["discussionLineThick"]);
										}

										$cDisc = $cForum->discussions[$i];

										// DRAW ALL POSTS OF CURRENT DISCUSSION
										while($cDisc->postCount > 0){
											$post = $cDisc->popPost();
											$postY = (($time_end - $post->created) / 3600 / 24 + 1) * $pm["dayHeight"] + $pm["marginTop"] + $headerHeight;
											$stu = $cDisc->getStudentByID($post->author);
										
											// Responding to a post?
											if($post->parent != 0){
												$stu->color = $plot->getColorFromSpectrum($stu->spectrumIndex, $cForum->totalStudents);
												$studentId = $stu->id;
											
												// Get parent post
												$parentPost = $cDisc->getPostByID($post->parent);
											
												if($parentPost!=false){
													if($parentPost->parent!=0 || !$pm["groupPost"]){
														// Get author of parent post
														$parentPostAuthor = $cDisc->getStudentByID($parentPost->author);
														// Get X and Y position of parent post
														$parentPostX = $parentPostAuthor->xLocation;
														$parentPostY = (($time_end - $parentPost->created) / 3600 / 24 + 1) * $pm["dayHeight"] + $pm["marginTop"] + $headerHeight;
													}else{
														// This is a group post, special student is created
														$tStudent = new BS_Student("Group Post");
														$tStudent->id = "disc".$cDisc->id;
														$tStudent->xLocation = $widthCounter + $pm["studentSeparation"]*($cDisc->studentCount+1)/2;
														$tStudent->discussion = 0;
														$tStudent->showBorder = $pm["studentBorder"];
														$tStudent->borderThick = $pm["studentBorderThickness"];
														$tStudent->smartBorderColor = $pm["studentSmartBorderColor"];
														$tStudent->rounded = $pm["studentRounded"];
														$tStudent->radius = $pm["studentRoundedRadius"];
														$tStudent->font = $pm["studentFont"];
														$tStudent->fontSize = $pm["studentFontSize"];
														$tStudent->marginVertical = $pm["studentMarginVertical"];
														$tStudent->marginHorizontal = $pm["studentMarginHorizontal"];
														// Get author of parent post
														$parentPostAuthor = $tStudent;
														// Get X and Y position of parent post
														$parentPostX = $parentPostAuthor->xLocation;
														$parentPostY = HEIGHT - $pm["marginBottom"] - $axisX - $spacer/2;
													}
												
													// Add post feedback stat?
													if($parentPostAuthor->id != $stu->id){
														$cForum->addPostFeedback($post);
														$cDisc->addPostFeedback($post);
													}
												
													if($pm["arrows"]){
														$end = $plot->getArrowCut($stu->xLocation, $postY, $parentPostX, $parentPostY, $parentPostAuthor);

														if($pm["bezier"]){
															if($pm["arrowColors"]){
																if($parentPost->author==0){
																	$plot->drawBezier($stu->xLocation, $postY, $stu->xLocation, $end["Y"], $end["X"], $end["Y"], $stu->xLocation, $end["Y"], $stu->color, $pm["arrowThick"], $pm["arrows"]);
																}else{
																	$plot->drawBezier($stu->xLocation, $postY, $end["X"], $postY, $end["X"], $end["Y"], $end["X"], $postY, $stu->color, $pm["arrowThick"], $pm["arrows"]);
																}
															}else{
																if($parentPost->author==0){
																	$plot->drawBezier($stu->xLocation, $postY, $stu->xLocation, $end["Y"], $end["X"], $end["Y"], $stu->xLocation, $end["Y"], $colors["black"], $pm["arrowThick"], $pm["arrows"]);
																}else{
																	$plot->drawBezier($stu->xLocation, $postY, $end["X"], $postY, $end["X"], $end["Y"], $end["X"], $postY, $colors["black"], $pm["arrowThick"], $pm["arrows"]);
																}
															}
														}else{
															if($pm["arrowColors"]){
																$plot->drawArrow($stu->xLocation, $postY, $end["X"], $end["Y"], $stu->color, $pm["arrowThick"]);
															}else{
																$plot->drawArrow($stu->xLocation, $postY, $end["X"], $end["Y"], $colors["arrow"], $pm["arrowThick"]);
															}
														}
													}else{
														if($pm["bezier"]){
															if($pm["arrowColors"]){
																if($parentPost->author==0){
																	$plot->drawBezier($stu->xLocation, $postY, $stu->xLocation, $parentPostY, $parentPostX, $parentPostY, $stu->xLocation, $parentPostY, $stu->color, $pm["arrowThick"], $pm["arrows"]);
																}else{
																	$plot->drawBezier($stu->xLocation, $postY, $parentPostX, $postY, $parentPostX, $parentPostY, $parentPostX, $postY, $stu->color, $pm["arrowThick"], $pm["arrows"]);
																}
															}else{
																if($parentPost->author==0){
																	$plot->drawBezier($stu->xLocation, $postY, $stu->xLocation, $parentPostY, $parentPostX, $parentPostY, $stu->xLocation, $parentPostY, $colors["black"], $pm["arrowThick"], $pm["arrows"]);
																}else{
																	$plot->drawBezier($stu->xLocation, $postY, $parentPostX, $postY, $parentPostX, $parentPostY, $parentPostX, $postY, $colors["black"], $pm["arrowThick"], $pm["arrows"]);
																}
															}
														}else{
															if($pm["arrowColors"]){
																$plot->drawLine($stu->xLocation, $postY, $parentPostX, $parentPostY, $stu->color, $pm["arrowThick"]);
															}else{
																$plot->drawLine($stu->xLocation, $postY, $parentPostX, $parentPostY, $colors["arrow"], $pm["arrowThick"]);
															}	
														}
													}
												}
											}else if($pm["groupPost"]){
												// This is a group post, special student is created
												$stu = new BS_Student("Group Post");
												$stu->id = "grouppost";
												$stu->xLocation = $widthCounter + $pm["studentSeparation"]*($cDisc->studentCount+1)/2;
												$stu->discussion = 0;
												$stu->showBorder = $pm["studentBorder"];
												$stu->borderThick = $pm["studentBorderThickness"];
												$stu->smartBorderColor = $pm["studentSmartBorderColor"];
												$stu->rounded = $pm["studentRounded"];
												$stu->radius = $pm["studentRoundedRadius"];
												$stu->font = $pm["studentFont"];
												$stu->fontSize = $pm["studentFontSize"];
												$stu->marginVertical = $pm["studentMarginVertical"];
												$stu->marginHorizontal = $pm["studentMarginHorizontal"];
												$stu->color = $colors["white"];
												$postY = HEIGHT - $pm["marginBottom"] - $axisX - $spacer/2;
											}
										
											// Max length of student name
											if(strlen($stu->name) > $pm["studentNameMaxChar"] && $stu->id!="grouppost"){
												if(strstr($stu->name, " ")){
													$name = explode(" ", $stu->name);
													unset($name[count($name)-1]);
													$stu->name = implode($name, " ");
												}
												if(strlen($stu->name) > $pm["studentNameMaxChar"]){
													$stu->name = substr($stu->name, 0, $pm["studentNameMaxChar"]-2)."...";
												}
											}

											// Draw student's post
											$plot->drawStudent($postY, $stu);

											// Draw student's profile pic?
											if($pm["displayProfilePics"]  && $stu->id!="grouppost" && !$stu->picDrawn){
											
												// Fetch profile picture
												try{
													if($CFG->slasharguments){
														// Moodle server allows slash arguments
														if(!copy("$moodleURL/user/pix.php/$studentId/f1.jpg", $CFG->dataroot."/bushgrapher/temp/$studentId.jpg")){
															copy("$moodleURL/user/pix.php?file=$studentId/f1.jpg", $CFG->dataroot."/bushgrapher/temp/$studentId.jpg");
														}
													}else{
														// Moodle server forbids slash arguments
														copy("$moodleURL/user/pix.php?file=$studentId/f1.jpg", $CFG->dataroot."/bushgrapher/temp/$studentId.jpg");
													}
												}catch (Exception $e){
												
												}
											
												$profilePic = @ImageCreateFromJPEG($CFG->dataroot."/bushgrapher/temp/$studentId.jpg") or // Read JPEG Image
												$profilePic = @ImageCreateFromPNG($CFG->dataroot."/bushgrapher/temp/$studentId.jpg") or // or PNG Image
												$profilePic = @ImageCreateFromGIF($CFG->dataroot."/bushgrapher/temp/$studentId.jpg") or // or GIF Image
												$profilePic = false; // If image is not JPEG, PNG, or GIF

												if($profilePic!=false){
													// Compute X, Y and Size of profile pic
													$y = HEIGHT - $pm["marginBottom"] - $axisX + $pm["studentSeparation"] / 3;
													$x = $stu->xLocation - $pm["studentSeparation"] / 3;
													$size = 2 * $pm["studentSeparation"] / 3; // size is 2/3 of the student separation

													// Insert picture
													$plot->insert($profilePic, $x, $y, $size, $size);

													// Compute X and Y of student name label
													$height = BushGraph::getTextHeight($stu->name, $pm["studentFontSize"], $pm["studentFont"]);
													$y = HEIGHT - $pm["marginBottom"] - $axisX + 7 * $pm["studentSeparation"] / 6 + $height;
													$width = BushGraph::getTextWidth($stu->name, $pm["studentFontSize"], $pm["studentFont"]);
													$x = $stu->xLocation - $width / 2;
													$plot->write($stu->name, $pm["studentFontSize"], $pm["studentFont"], $colors["black"], $x, $y);

													// Mark as drawn
													$stu->picDrawn = true;
												}
											}
										}
									
										/*
										-----------------------------------------
										Write discussion name?
										-----------------------------------------
										*/
										if(!$cDisc->nameDisplayed){

											$DiscNameHeight = BushGraph::getTextHeight($cDisc->name, $pm["discussionNameFontSize"], $pm["discussionNameFont"]);
											$DiscNameWidth = BushGraph::getTextWidth($cDisc->name, $pm["discussionNameFontSize"], $pm["discussionNameFont"]);

											if($pm["displayDiscussionName"]){
												// Name of forum
												$y = HEIGHT - $pm["marginBottom"] - $axisX + 9 * $pm["studentSeparation"] / 6 + $DiscNameHeight;
												$x = $widthCounter + (($cDisc->studentCount + 1) * $pm["studentSeparation"]) / 2 - $DiscNameWidth/2;
												$plot->write($cDisc->name, $pm["discussionNameFontSize"], $pm["discussionNameFont"], $colors["black"], $x, $y);
											}

											if($pm["displayDiscussionReplies"]){
												// Number of replies
												$replies = $cDisc->totalFeedbacks." replies";
												$heightR = BushGraph::getTextHeight($replies, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$widthR = BushGraph::getTextWidth($replies, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$y = HEIGHT - $pm["marginBottom"] - $axisX + 9 * $pm["studentSeparation"] / 6 + $heightR + 3*$DiscNameHeight/2;
												$x = $widthCounter + (($cDisc->studentCount + 1) * $pm["studentSeparation"]) / 2 - $widthR/2;
												$plot->write($replies, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"], $colors["black"], $x, $y);
											}
										
											if($pm["groupInfo"]){
												// Group stats
											
												// Participants
												$participants = "Participants: ".$cDisc->studentCount;
												$ParticipantsHeight = BushGraph::getTextHeight($participants, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$ParticipantsWidth = BushGraph::getTextWidth($participants, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$y = HEIGHT - $pm["marginBottom"] - $axisX + 9 * $pm["studentSeparation"] / 6 + $heightR + 3*$DiscNameHeight/2 + $heightR + 1.5*$ParticipantsHeight;
												$x = $widthCounter + (($cDisc->studentCount + 1) * $pm["studentSeparation"]) / 2 - $ParticipantsWidth/2;
												$plot->write($participants, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"], $colors["black"], $x, $y);
											
												// Production
												if($cDisc->studentCount > 0){
													$DiscProduction = $cDisc->totalPosts / $cDisc->studentCount;
													$prod = "Production: ".round($DiscProduction, $pm["rounding"]);
												}else{
													$DiscProduction = 0;
													$prod = "Production: 0";
												}
												$ProdWidth = BushGraph::getTextWidth($prod, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$y = HEIGHT - $pm["marginBottom"] - $axisX + 9 * $pm["studentSeparation"] / 6 + $heightR + 3*$DiscNameHeight/2 + $heightR + 3*$ParticipantsHeight;
												$x = $widthCounter + (($cDisc->studentCount + 1) * $pm["studentSeparation"]) / 2 - $ProdWidth/2;
												$plot->write($prod, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"], $colors["black"], $x, $y);
											
												// Interactivity
												if($cDisc->studentCount>0){
													$DiscInteractivity = $cDisc->totalFeedbacks/$cDisc->studentCount;
												}else{
													$DiscInteractivity = 0;
												}
												$inter = "Interactivity: ".round($DiscInteractivity, $pm["rounding"]);
												$InterWidth = BushGraph::getTextWidth($inter, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$y = HEIGHT - $pm["marginBottom"] - $axisX + 9 * $pm["studentSeparation"] / 6 + $heightR + 3*$DiscNameHeight/2 + $heightR + 4.5*$ParticipantsHeight;
												$x = $widthCounter + (($cDisc->studentCount + 1) * $pm["studentSeparation"]) / 2 - $InterWidth/2;
												$plot->write($inter, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"], $colors["black"], $x, $y);
											
												// Transactivity
												$DiscTransactivity = $DiscInteractivity * $DiscProduction;
												$trans = "Transactivity: ".round($DiscTransactivity, $pm["rounding"]);
												$TransWidth = BushGraph::getTextWidth($trans, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"]);
												$y = HEIGHT - $pm["marginBottom"] - $axisX + 9 * $pm["studentSeparation"] / 6 + $heightR + 3*$DiscNameHeight/2 + $heightR + 6*$ParticipantsHeight;
												$x = $widthCounter + (($cDisc->studentCount + 1) * $pm["studentSeparation"]) / 2 - $TransWidth/2;
												$plot->write($trans, $pm["discussionRepliesFontSize"], $pm["discussionRepliesFont"], $colors["black"], $x, $y);
											}										

											// Mark as displayed
											$cDisc->nameDisplayed = true;
										}

									}

									/*
									======================================================================
									Create headers
									======================================================================
									*/

									$textPointer = $pm["marginTop"];
									$titles = false;

									// TITLE
									if($pm["headerDisplayTitle"]){
										$textPointer += $titleH;
										$plot->write($pm["headerTitle"], $pm["headerTitleSize"], $pm["headerFont"], $colors["title"], $pm["marginLeft"], $textPointer);
										$textPointer += round($titleH / 3);
										$titles = true;
									}

									// SUBTITLE
									if($pm["headerDisplaySubTitle"]){
										$textPointer += $subTitleH;
										$plot->write($pm["headerSubTitle"], $pm["headerSubTitleSize"], $pm["headerFont"], $colors["subtitle"], $pm["marginLeft"], $textPointer);
										$textPointer += round($subTitleH / 2);
										$titles = true;
									}

									// SUBSUBTITLE
									if($pm["headerDisplaySubSubTitle"]){
										$textPointer += $subsubTitleH;
										$plot->write($pm["headerSubSubTitle"], $pm["headerSubSubTitleSize"], $pm["headerFont"], $colors["subsubtitle"], $pm["marginLeft"], $textPointer);
										$textPointer += round($subsubTitleH / 2);
										$titles = true;
									}

									if($titles){$textPointer += $textH;} //add textH to skip a line

									// COURSE NAME
									if($pm["headerDisplayCourse"]){

										$table = $prefix."course";
										$course = mysqli_query($CFG->con, "SELECT fullname, shortname FROM $table WHERE id=$course");
										$course = mysqli_fetch_array($course);

										$textPointer += $textH;
										if($course["shortname"]!=""){$text = "Course: ".$course["shortname"].", ";}else{$text="Course: ";}
										$text .= $course["fullname"];

										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// FORUM NAME
									if($pm["headerDisplayForum"]){

										$table = $prefix."forum";
										$forums = mysqli_query($CFG->con, "SELECT name FROM $table WHERE id=$forum;");
										$forums = mysqli_fetch_array($forums);

										$textPointer += $textH;
										$text = "Forum: ".$forums["name"];
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// RATING PERIOD
									if($pm["headerDisplayRatingPeriod"]){

										$time_start = date("F j, Y", $time_start);
										$time_end = date("F j, Y", $time_end);

										$textPointer += $textH;
										$text = "Ratings restricted to dates From: $time_start to $time_end";
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// COURSE PARTICIPANTS
									if($pm["headerDisplayCourseParticipants"]){
										$textPointer += $textH;
										$text = "Number of course participants: ".$cForum->totalStudents;
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// PRODUCTION
									if($cForum->totalStudents > 0){
										$production = round($cForum->totalPosts / $cForum->totalStudents, $pm["rounding"]);
									}else{
										$production = 0;
									}
									if($pm["headerDisplayFeedback"]){
										$textPointer += $textH;
										$text = "Average number of posts per student (production) = ".$production;
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// INTERACTIVITY
									if($cForum->totalStudents > 0){
										$interactivity = round($cForum->totalFeedbacks / $cForum->totalStudents, $pm["rounding"]);
									}else{
										$interactivity = 0;
									}
									if($pm["headerDisplayPostsPerStudent"]){
										$textPointer += $textH;
										$text = "Average number of feedback posts per student (interactivity) = ".$interactivity;
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// GROUP ACTIVITY
									$groupActivity = round($cForum->totalPosts / $cForum->dCount, $pm["rounding"]);
									if($pm["headerDisplayPostsPerDiscussion"]){
										$textPointer += $textH;
										$text = "Average number of posts per discussion topic (group activity) = ".$groupActivity;
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									// TRANSACTIVITY
									$transactivity = round($interactivity * $production, $pm["rounding"]);
									if($pm["headerDisplayTransactivity"]){
										$textPointer += $textH;
										$text = "Transactivity score (production x interactivity) = ".$transactivity;
										$plot->write($text, $pm["headerTextSize"], $pm["headerFont"], $colors["header"], $pm["marginLeft"], $textPointer);
										$textPointer += round($textH / 2);
									}

									if($pm["demo"]){
										$plot->write("DEMO", 500, "Arial Bold", $colors["red"], 200, 700);
									}


									/*
									======================================================================
									Save picture
									======================================================================
									*/

									$course = $_GET["course"];
									$forum = $_GET["forum"];
									$filename = $CFG->dataroot."/bushgrapher/quickplots/c".$course."f".$forum.".png";
									$filenameThumb = $CFG->dataroot."/bushgrapher/quickplots/c".$course."f".$forum."-thumb.png";

									$plot->outputPNG($filename);
									$plot->destroy();
									unset($plot);

									/*
									======================================================================
									Create thumbnail
									======================================================================
									*/

									// New dimensions
									$height = (HEIGHT * 800) / WIDTH;
									$width = 800;

									$image_info = getimagesize($filename);
									$image_type = $image_info[2];
									if($image_type == IMAGETYPE_JPEG){
									   $image = imagecreatefromjpeg($filename);
									}else if($image_type == IMAGETYPE_GIF){
									   $image = imagecreatefromgif($filename);
									}else if($image_type == IMAGETYPE_PNG){
									   $image = imagecreatefrompng($filename);
									}

									$new_image = imagecreatetruecolor($width, $height);
									imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, WIDTH, HEIGHT);
									$image = $new_image;

									imagepng($image, $filenameThumb); 

									/*
									======================================================================
									Display picture
									======================================================================
									*/
								
									echo "<center>\n";

									if($NO_ASSESS_PERIOD){
										echo "<span class='red'>Warning, this forum doesn't have an assessment period. The start and end dates shown in the graph have been estimated.</span><br/>";
									}
								
									echo "
										<a href='file.php?file=c",$course,"f",$forum,".png'>Download full size image</a><br/><br/>
										<a href='file.php?file=c",$course,"f",$forum,".png'>
											<img src='file.php?file=c",$course,"f",$forum,"-thumb.png' style='border:1px solid black' height='",$height,"' width='",$width,"' />
										</a>
									</center>
									";
								}else{
									echo "
									<h2 class='red'>Error</h2>
									<span class='red'>Error: it was impossible to determine the amount of days the Forum was assessed for. Please try
									another forum.</span><br/>
									&gt; <a href='index.php?course=",$course,"'>Go back to Forums</a><br/><br/>
								
									<h2 class='red'>Troubleshooting</h2>
									While rare, this problem can be caused by several factors. Your forum doesn't have a set assessment period and the 
									Participation Map was unable to guess your assessment period from the message timestamps. You may try the following:
									<ul>
										<li>Go into the forum's settings and set an assessment period</li>
										<li>Post a new message in the forum and try again</li>
										<li>Make sure you have the latest version of Participation Map</li>
										<li>You can force Participation Map to plot the graph with a period of 2000 to 2012 by <a href='?course=$course&forum=$forum&start=946684800&end=1325376000'>clicking here</a>.</li>
									</ul>
									";
								
									if(checkMoodleCompatibility()){
										echo "<br/><span class='red'>Warning: Participation Map doesn't seem compatible with your installation of Moodle, this might be the cause of this error.</span>";
									}
								
									echo "
									<h2 class='red'>Supplement information</h2>
									Here is information from your server that may help Participation Map developers fix this issue. Please send an email to <a href='mailto:thomas.lextrait@gmail.com'>thomas.lextrait@gmail.com</a> with the information below.
									<br/><br/>
								
									<div style='display:block;border:1px dashed #CCCCCC;margin:0;padding:5px;width:570px;overflow:auto'>";
								
									// Find end time
									$table = $prefix."forum_posts";
									$query3 = mysqli_fetch_row(mysqli_query($CFG->con, "SELECT MAX(created) FROM $table WHERE discussion=$disc"));
									echo "SELECT MAX(created) FROM $table WHERE discussion=$disc", "<br/>";
									print_r($query3);
									echo "<br/>";
															
									// Find start time
									$table = $prefix."forum_posts";
									$query4 = mysqli_fetch_row(mysqli_query($CFG->con, "SELECT MIN(created) FROM $table WHERE discussion=$disc"));
									echo "SELECT MIN(created) FROM $table WHERE discussion=$disc", "<br/>";
									print_r($query4);
									echo "<br/>";
								
									$table = $prefix."forum_posts";
									$query5 = mysqli_query($CFG->con, "DESC $table");
									echo "DESC $table", "<br/>";
									while($row = mysqli_fetch_row($query5)){
										print_r($row);
										echo "<br/>";
									}
								
								
									echo "</div>";
								}
							}
						}else{
							echo "
							<h2 class='red'>Error</h2>
							<span class='red'>Error: it was impossible to determine for which forum you want to create a BushGraph. 
							Please use the link from your settings menu in your course page.</span><br/>
							&gt; <a href='index.php?course=",$course,"'>Go back to Forums</a>";
						}
					}else{
						echo "
						<h2 class='red'>Error</h2>
						<span class='red'>Error: it was impossible to determine for which course you want to create a BushGraph. 
						Please use the link from your settings menu in your course page.</span><br/>
						&gt; <a href='",$moodleURL,"'>Go back to Moodle</a>";
					}
				}else{
					echo "
					<span class='red'>Error: you must be an instructor for this course or an administrator in order to view this page.</span><br/>
					&gt; <a href='$moodleURL'>Go back to Moodle</a>";
				}
			?>
		</div>
	
		<?php require_once "includes/footer1000.inc"; ?>
	</div>
	
</body>
</html>
