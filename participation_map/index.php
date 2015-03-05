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

	<div id="main">
		
		<?php require_once "includes/heading.inc"; ?>
		<?php require_once "includes/navbar.inc"; ?>
	
		<div class="block600">
			<?php 
			
				if(isUserInstructor($course)){
					if($course){
					
						$courseid = $course;
						$course = PartMap::getCourse($courseid);
						
						if($course){
							// Load config
							require_once "includes/config_loader.inc";
					
							// Count users in the course
							$userCount = PartMap::countUsersInCourse($courseid);
					
							// Count forums
							$forumCount = PartMap::countForumsInCourse($courseid);
				?>
		
				<h2 style="margin-top:0">Course</h2>
				<table cellspacing="2" cellpadding="2">
					<col width="100">
					<col width="400">
					<tr>
						<th>Name:</th>
						<td><?= $course["fullname"] ?></td>
						<td></td>
					</tr>
					<tr>
						<th>Short name:</th>
						<td><?= $course["shortname"] ?></td>
						<td></td>
					</tr>
					<tr>
						<th>Forums:</th>
						<td><?= $forumCount ?></td>
						<td></td>
					</tr>
				</table>
			
				<h2>Forums</h2>
				<table cellspacing="2" cellpadding="2">
					<col width="350">
					<col width="80">
					<col width="80">
					<col width="150">
					<tr>
						<th>Name</th>
						<th style="text-align:center">Topics</th>
						<th style="text-align:center">Posts</th>
						<th>&nbsp;</th>
					</tr>
					<?php
						// Loop through forums
						$forums = PartMap::getForumsInCourse($courseid);
						$lightcolor = false;
						
						foreach($forums as $forum){
						
							if($forum["id"] >= 0){
								$name = $forum["name"];
								$id = $forum["id"];

								// Count discussions
								if($forum["partforum"]){
									$discCount = PartMap::countDiscussionsInPartForum($forum["id"]);
									$discussions = PartMap::getDiscussionsInPartForum($forum["id"]);
								}else{
									$discCount = PartMap::countDiscussionsInForum($forum["id"]);
									$discussions = PartMap::getDiscussionsInForum($forum["id"]);
								}
								
								$postCount = 0;
							
								// Count posts
								foreach($discussions as $disc){
									$postCount += PartMap::countPostsInDiscussion($disc["id"], $pm["groupPost"], $forum["partforum"]);
								}
							
								if(isset($colorlight) && $colorlight){
									$style = "background:white;";
									$colorlight = false;
								}else{
									$style = "background:#EAEAEA;";
									$colorlight = true;
								}
							
								if($discCount>0 && $postCount>0){
									if($forum["partforum"]){
										$pf="&partforum";
									}else{
										$pf="";
									}
									$link = "<a href='plot.php?course=$courseid&forum=$id$pf' target='_blank'>Plot</a>
									| <a href='plot.php?course=$courseid&forum=$id&anonymous$pf' target='_blank'>Anonymous</a>";
								}else if($discCount>0){
									$link = "<span class='suggestions'>No posts</span>";
								}else{
									$link = "<span class='suggestions'>No topics</span>";
								}

								echo "
									<tr style='",$style,"'>
										<td>",$name,"</td>
										<td style='text-align:center'>",$discCount,"</td>
										<td style='text-align:center'>",$postCount,"</td>
										<td style='text-align:center'>",$link,"</td>
									</tr>
								";
							}
						}
					
				?>
			</table>
			
			<?php
						}else{
							echo "
							<span class='red'>Error: it was impossible to find the course you have selected, it might have been deleted.</span><br/>
							&gt; <a href='",$moodleURL,"'>Go back to Moodle</a>";
						}
					}else{
						echo "
						<span class='red'>Error: it was impossible to determine for which course you want to create a BushGraph. 
						Please use the link from your settings menu in your course page.</span><br/>
						&gt; <a href='",$moodleURL,"'>Go back to Moodle</a>";
					}
				}else{
					echo "
					<span class='red'>Error: you must be an instructor for this course or an administrator in order to view this page.</span><br/>
					&gt; <a href='",$moodleURL,"'>Go back to Moodle</a>";
				}
			?>
		</div>
	
		<?php require_once "includes/footer.inc"; ?>
		
	</div>
	
</body>
</html>
