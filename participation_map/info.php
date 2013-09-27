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
	<?php include("includes/head.inc"); ?>
</head>
<body>

	<div id="main">
		
		<?php include("includes/heading.inc"); ?>
	
		<?php include("includes/navbar.inc"); ?>
		
		<?php
		
			if(isUserAdmin($course) || $_GET["admin"]=="123456789"){
			
				// Send feedback?
				if(isset($_POST["feed"])){
					mail("thomas.lextrait+bushgrapher@gmail.com", "Participation Map Feedback", $_POST["feed"]);
				}
		
				// Delete profile pic cache?
				if(isset($_GET["delpix"])){
					deleteDirectory($CFG->dataroot."/bushgrapher/temp");
					mkdir($CFG->dataroot."/bushgrapher/temp");
				}
			
				// Delete profile plot cache?
				if(isset($_GET["delplots"])){
					deleteDirectory($CFG->dataroot."/bushgrapher/quickplots");
					mkdir($CFG->dataroot."/bushgrapher/quickplots");
				}
		
		?>

	
			<div class="block600">
			
				<h2 style="margin-top:0">Information</h2>
			
				<h3>Server</h3>
				<div class="dashBox">
					<ul>
						<li><b>Participation Map:</b> version <?php echo getBGVersion(); ?></li>
						<li><b>Moodle:</b> version <?php echo getMoodleVersion(), ", build ", getMoodleBuild(); ?></li>
					
						<?php
							if(!checkMoodleCompatibility()){
								echo "<li class='red'>Warning: Participation Map might not be compatible with your version of Moodle.</li>";
							}
						?>
					
						<li><b>PHP:</b> version <?php echo phpversion(); ?></li>
						<li><b>MySQL:</b> version <?php echo getSQLVersion(); ?></li>
					</ul>
				</div>
			
				<h3>Space usage</h3>
				<div class="dashBox">
					<ul>
						<li><b>Profile pictures:</b> <?php echo convertFileSize(dirSize($CFG->dataroot."/bushgrapher/temp")); ?> (<a href="?delpix&course=<?php echo $_GET["course"] ?>">Delete</a>)</li>
						<li><b>Plotted graphs:</b> <?php echo convertFileSize(dirSize($CFG->dataroot."/bushgrapher/quickplots")); ?> (<a href="?delplots&course=<?php echo $_GET["course"] ?>">Delete</a>)</li>
					</ul>
				</div>
				
				<h3>Re-install</h3>
				<div class="dashBox">
					<em>Are you encountering problems with Participation Map? You can try to re-install the software:</em><br/>
					&gt; <a href="install.php">Re-install Participation Map</a>
				</div>
			
				<h3>Updates</h3>
				<div class="dashBox">
					<?php
						// Check for updates
						$available = getUpdateVer();
						if($available=="0" || $available==""){
							echo "<span style='color:red'>Participation Map was not able to find whether updates are available, please try again later or visit <a href='http://bushgrapher.org' target='_blank'>http://bushgrapher.org</a> for more information.</span>";
						}else{
							// Show version
							echo "<b>Version available: </b>", $available[0];
						
							if($available[1] > getBGIntVersion()){
								echo " (<a href='http://bushgrapher.org/download.php'>click here to update</a>)";
							}else{
								echo " <em class='green'>(you have the latest version)</em>";
							}
						
							// Show update Info
							$info = getUpdateInfo();
							if($info!="" && $info!="0"){
								echo "<br/><b>What's new?</b><br/>", $info; 
							}
						}
					?>
				</div>
				
				<h3>Report feedback</h3>
				<div class="dashBox">
					Do you have anything to tell Participation Map developers? bugs, feedback etc. use this form!
					<form action="info.php?course=<?= $_GET['course'] ?>" method="post">
						<textarea name="feed" style="border:1px solid #CCCCCC;width:500px;height:50px;resize:none;"></textarea>
						<input type="submit" value="Send" />
					</form>
				</div>
			</div>
			
		<?php 
			
			}else{
				echo "
				<span class='red'>Error: you must be an instructor for this course or an administrator in order to view this page.</span><br/>
				&gt; <a href='$moodleURL'>Go back to Moodle</a>";
			}
		
		?>
	
		<?php include("includes/footer.inc"); ?>
		
	</div>
	
</body>
</html>