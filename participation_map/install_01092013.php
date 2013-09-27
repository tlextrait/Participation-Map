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
	
		<div class="block600">
			
			<h2 style="margin-top:0">Participation Map installer</h2>

			<?php
			
				function installFilesMissing(){
					echo "<span class='red'>Some installation files are missing, please get the latest version of Participation Map on <a href='http://bushgrapher.org' target='_blank'>bushgrapher.org</a>.</span>";
				}
				
				function installError(){
					echo "<span class='red'>Sorry we were unable to install Participation Map automatically. This error is probably due to file permissions settings (Participation Map is unable to write or modify files on your server), please do a manual installation. For more information, visit <a href='http://bushgrapher.org' target='_blank'>bushgrapher.org</a>.</span>";
				}
				
				function noVersion(){
					echo "<span class='red'>Sorry the auto-installer is not compatible with your version of Moodle. You can also try performing a manual installation. Please visit <a href='http://bushgrapher.org' target='_blank'>bushgrapher.org</a> and make sure you have the latest version of Participation Map.</span>";
				}
				
				function moodMenu(){
					echo "- Moodle instructor menu updated.<br/>";
				}
				
				function success(){
					echo "<span class='green'>Participation Map has been installed successfully.<br/>
						  <em>If you need to reinstall Participation Map, you may simply come back to this page at any time.</em></span>";
				}
				
				if(isUserAdmin()){
					// Get moodle version
					$moodver = getMoodleVersion();
					// Short version
					$shortver = substr($moodver, 0, 3);

					try{

						if($shortver == "1.9"){

							// Check install files are here
							if(file_exists("install_files/block_admin.php")){

								// Get file perms
								$perms = substr(sprintf("%o", fileperms("../blocks/admin")), -4);
								chmod("../blocks/admin", 0777);
								echo "- Moodle file permissions set, ready for installation.<br/>";

								copy("../blocks/admin/block_admin.php", "../blocks/admin/block_admin.php~");
								echo "- <b>blocks/admin/block_admin.php</b> has been backed up.<br/>";
								copy("install_files/block_admin.php", "../blocks/admin/block_admin.php");
								moodMenu();
								
								// Install gif
								copy("install_files/mini_bushgrapher.gif", "../pix/i/mini_bushgrapher.gif");
								echo "- Participation Map menu icon installed.<br/>";
								
								// Create moodle data directory
								if(!file_exists($CFG->dataroot."/bushgrapher")){
									mkdir($CFG->dataroot."/bushgrapher");
									if(file_exists($CFG->dataroot."/bushgrapher")){
										echo "- Created Moodle Data directory for Participation Mapr.<br/>";
										mkdir($CFG->dataroot."/bushgrapher/config");
										mkdir($CFG->dataroot."/bushgrapher/quickplots");
										mkdir($CFG->dataroot."/bushgrapher/temp");
										// copy default settings into it
										copy("config/default.bgs", $CFG->dataroot."/bushgrapher/config/default.bgs");
									}else{
										echo "<span class='red'>- Failed creating Moodle Data directory for Participation Map.</span><br/>";
									}
								}
								echo "- Moodle Data directory for Participation Map already exists.<br/>";
								
								// Restore file perms
								chmod("../blocks/admin", $perms);
								echo "- Moodle file permissions restored.<br/>";
							}else{
								installFilesMissing();
							}

							success();

						}else if(
							$shortver == "2.0" || 
							$shortver == "2.1" || 
							$shortver == "2.2" || 
							$shortver == "2.3" || 
							$shortver == "2.4"){

							// Check install files are here
							if(file_exists("install_files/navigationlib.php")){

								// Get file perms
								$perms = substr(sprintf("%o", fileperms("../lib")), -4);
								chmod("../lib", 0705);
								echo "- Moodle file permissions set, ready for installation.<br/>";

								copy("../lib/navigationlib.php", "../lib/navigationlib.php~");
								echo "- <b>lib/navigationlib.php</b> has been backed up.<br/>";
								copy("install_files/navigationlib.php", "../lib/navigationlib.php");
								moodMenu();
								
								// Install gif
								copy("install_files/mini_bushgrapher.gif", "../pix/i/mini_bushgrapher.gif");
								echo "- Participation Map menu icon installed.<br/>";
								
								// Create moodle data directory
								if(!file_exists($CFG->dataroot."/bushgrapher")){
									mkdir($CFG->dataroot."/bushgrapher");
									if(file_exists($CFG->dataroot."/bushgrapher")){
										echo "- Created Moodle Data directory for Participation Map.<br/>";
										mkdir($CFG->dataroot."/bushgrapher/config");
										mkdir($CFG->dataroot."/bushgrapher/quickplots");
										mkdir($CFG->dataroot."/bushgrapher/temp");
										// copy default settings into it
										copy("config/default.bgs", $CFG->dataroot."/bushgrapher/config/default.bgs");
									}else{
										echo "<span class='red'>- Failed creating Moodle Data directory for Participation Map.</span><br/>";
									}
								}
								echo "- Moodle Data directory for Participation Map already exists.<br/>";

								// Restore file perms
								chmod("../lib", $perms);
								echo "- Moodle file permissions restored.<br/>";
							}else{
								installFilesMissing();
							}

							success();

						}else{noVersion();}

					}catch(Exception $ex){installError();}
				}else{
					echo "<span class='red'>Sorry only a Moodle administrator is allowed to install or re-install Participation Map.</span>";
				}
			
			?>

		</div>
	
		<?php include("includes/footer.inc"); ?>
		
	</div>
	
</body>
</html>