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
		
		<script type="text/javascript">
		
			// Show/hide object at given index
			function toggle(index){
				var element = document.getElementById(index);
				if(element.className=="blockShow"){
					element.className = "blockHide";
				}else{
					element.className = "blockShow";
				}
			}

			function save(){
				document.forms["settings"].submit();
			}
			
			function reset(course){
				if(confirm("Are you sure you want to reset your settings?")){
					window.location="?reset&course="+course;
				}
			}
			
		</script>
		
		<?php

			if(isUserInstructor($course)){
			
				function sel($a, $b){
					if($a==$b){echo "selected";}
				}
			
				// Reset deftaults?
				if(isset($_GET["reset"])){
					$id = $USER->id;
					if(file_exists($CFG->dataroot."/bushgrapher/config/user$id.bgs")){
						unlink($CFG->dataroot."/bushgrapher/config/user$id.bgs");
					}
				}
			
				// Save config
				if(isset($_POST["saving"])){
					include("includes/config_saver.inc");
				}
			
				// Load config
				include("includes/config_loader.inc");
			
				// RESOLVE RESOLUTION
				$resolution = $pm["resolution"];
		
		?>
	
			<form action="?course=<?php echo $_GET['course'] ?>" method="post" name="settings">
			
				<input type="hidden" name="saving" value="true" />
			
				<!-- To compensate for disabled inputs -->
				<input type="hidden" name="headerTitle" value="<?php echo $pm['headerTitle'] ?>" />
				<input type="hidden" name="headerTitleSize" value="<?php echo $pm['headerTitleSize'] / $resolution ?>" />
				<input type="hidden" name="headerDisplayTitle" value="<?php echo $pm['headerDisplayTitle'] ?>" />
			
				<input type="hidden" name="headerSubTitle" value="<?php echo $pm['headerSubTitle'] ?>" />
				<input type="hidden" name="headerSubTitleSize" value="<?php echo $pm['headerSubTitleSize'] / $resolution ?>" />
				<input type="hidden" name="headerDisplaySubTitle" value="<?php echo $pm['headerDisplaySubTitle'] ?>" />
			
				<input type="hidden" name="headerSubSubTitle" value="<?php echo $pm['headerSubSubTitle'] ?>" />
				<input type="hidden" name="headerSubSubTitleSize" value="<?php echo $pm['headerSubSubTitleSize'] / $resolution ?>" />
				<input type="hidden" name="headerDisplaySubSubTitle" value="<?php echo $pm['headerDisplaySubSubTitle'] ?>" />
			
				<div class="block600">
					<h2 style="margin-top:0" class="underline">Settings</h2>
			
					<a href="javascript:save()">Save settings</a> | <a href="javascript:reset('<?php echo $_GET['course'] ?>')">Reset defaults</a><br/>
			
					<h3 onClick="javascript:toggle('headings')" style="cursor:pointer">.: Headings</h3>
					<div class="blockShow" id="headings">
						<table>
							<col width="150">
							<col width="400">
							<!--
							<tr>
								<td>Title:</td>
								<td><input name="headerTitle" type="text" maxlength="50" value="<?php //echo $pm['headerTitle'];?>" style="width:200px" />
									<select name="headerTitleSize" DISABLED>
										<?php //$switch = $pm["headerTitleSize"] / $resolution ?>
										<option value="12" <?php //sel($switch, 12) ?>>12</option>
										<option value="14" <?php //sel($switch, 14) ?>>14</option>
										<option value="16" <?php //sel($switch, 16) ?>>16</option>
										<option value="18" <?php //sel($switch, 18) ?>>18</option>
										<option value="20" <?php //sel($switch, 20) ?>>20</option>
										<option value="22" <?php //sel($switch, 22) ?>>22</option>
										<option value="24" <?php //sel($switch, 24) ?>>24</option>
										<option value="26" <?php //sel($switch, 26) ?>>26</option>
										<option value="28" <?php //sel($switch, 28) ?>>28</option>
										<option value="36" <?php //sel($switch, 36) ?>>36</option>
										<option value="48" <?php //sel($switch, 48) ?>>48</option>
									</select>
									<input name="headerDisplayTitle" type="checkbox" value="" <?php //if($pm['headerDisplayTitle']){echo 'checked';} ?> /> Display
								</td>
							</tr>
							<tr>
								<td>Subtitle:</td>
								<td><input name="headerSubTitle" type="text" maxlength="50" value="<?php //echo $pm['headerSubTitle'];?>" style="width:200px" />
									<select name="headerSubTitleSize" DISABLED>
										<?php //$switch = $pm["headerSubTitleSize"] / $resolution ?>
										<option value="12" <?php //sel($switch, 12) ?>>12</option>
										<option value="14" <?php //sel($switch, 14) ?>>14</option>
										<option value="16" <?php //sel($switch, 16) ?>>16</option>
										<option value="18" <?php //sel($switch, 18) ?>>18</option>
										<option value="20" <?php //sel($switch, 20) ?>>20</option>
										<option value="22" <?php //sel($switch, 22) ?>>22</option>
										<option value="24" <?php //sel($switch, 24) ?>>24</option>
										<option value="26" <?php //sel($switch, 26) ?>>26</option>
										<option value="28" <?php //sel($switch, 28) ?>>28</option>
										<option value="36" <?php //sel($switch, 36) ?>>36</option>
										<option value="48" <?php //sel($switch, 48) ?>>48</option>
									</select>
									<input name="headerDisplaySubTitle" type="checkbox" value="" <?php //if($pm['headerDisplaySubTitle']){echo 'checked';} ?> /> Display
								</td>
							</tr>
							<tr>
								<td>SubSubtitle:</td>
								<td><input name="headerSubSubTitle" type="text" maxlength="50" value="<?php //echo $pm['headerSubSubTitle'];?>" style="width:200px" />
									<select name="headerSubSubTitleSize" DISABLED>
										<?php //$switch = $pm["headerSubSubTitleSize"] / $resolution ?>
										<option value="12" <?php //sel($switch, 12) ?>>12</option>
										<option value="14" <?php //sel($switch, 14) ?>>14</option>
										<option value="16" <?php //sel($switch, 16) ?>>16</option>
										<option value="18" <?php //sel($switch, 18) ?>>18</option>
										<option value="20" <?php //sel($switch, 20) ?>>20</option>
										<option value="22" <?php //sel($switch, 22) ?>>22</option>
										<option value="24" <?php //sel($switch, 24) ?>>24</option>
										<option value="26" <?php //sel($switch, 26) ?>>26</option>
										<option value="28" <?php //sel($switch, 28) ?>>28</option>
										<option value="36" <?php //sel($switch, 36) ?>>36</option>
										<option value="48" <?php //sel($switch, 48) ?>>48</option>
									</select>
									<input name="headerDisplaySubSubTitle" type="checkbox" value="" <?php //if($pm['headerDisplaySubSubTitle']){echo 'checked';} ?> /> Display
								</td>
							</tr>
							-->
							<tr>
								<td>Header font:</td>
								<td>
									<select name="headerFont">
										<?php $switch = $pm["headerFont"] ?>
										<optgroup label="Classic fonts">
											<option <?php sel($switch, "Arial") ?> value="Arial">Arial</option>
											<option <?php sel($switch, "Arial Italic") ?> value="Arial Italic">Arial - Italic</option>
											<option <?php sel($switch, "Arial Bold") ?> value="Arial Bold">Arial - Bold</option>
											<option <?php sel($switch, "Arial Bold Italic") ?> value="Arial Bold Italic">Arial - Bold Italic</option>
											<option <?php sel($switch, "Courrier New") ?> value="Courrier New">Courrier New</option>
											<option <?php sel($switch, "Times New Roman") ?> value="Times New Roman">Times New Roman</option>
											<option <?php sel($switch, "Times New Roman Italic") ?> value="Times New Roman Italic">Times New Roman - Italic</option>
											<option <?php sel($switch, "Times New Roman Bold") ?> value="Times New Roman Bold">Times New Roman - Bold</option>
											<option <?php sel($switch, "Times New Roman Bold Italic") ?> value="Times New Roman Bold Italic">Times New Roman - Bold Italic</option>
											<option <?php sel($switch, "Verdana") ?> value="Verdana">Verdana</option>
											<option <?php sel($switch, "Verdana Italic") ?> value="Verdana Italic">Verdana - Italic</option>
											<option <?php sel($switch, "Verdana Bold") ?> value="Verdana Bold">Verdana - Bold</option>
											<option <?php sel($switch, "Verdana Bold Italic") ?> value="Verdana Bold Italic">Verdana - Bold Italic</option>
										</optgroup>
										<optgroup label="Artistic fonts">
											<option <?php sel($switch, "Comfortaa") ?> value="Comfortaa">Comfortaa</option>
											<option <?php sel($switch, "Coolvetica") ?> value="Coolvetica">Coolvetica</option>
											<option <?php sel($switch, "Critisized") ?> value="Critisized">Critisized</option>
											<option <?php sel($switch, "Harabara") ?> value="Harabara">Harabara</option>
											<option <?php sel($switch, "OldSansBlack") ?> value="OldSansBlack">OldSansBlack</option>
											<option <?php sel($switch, "Qlassik") ?> value="Qlassik">Qlassik</option>
										</optgroup>
									</select>
								</td>
							</tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('stats')" style="cursor:pointer">.: Statistics</h3>
					<div class="blockHide" id="stats">
						<table>
							<col width="150">
							<col width="300">
							<tr>
								<td>Statistics font size:</td>
								<td>
									<select name="headerTextSize">
										<?php $switch = $pm["headerTextSize"] / $resolution ?>
										<option value="12" <?php sel($switch, 12) ?>>12</option>
										<option value="14" <?php sel($switch, 14) ?>>14</option>
										<option value="16" <?php sel($switch, 16) ?>>16</option>
										<option value="18" <?php sel($switch, 18) ?>>18</option>
										<option value="20" <?php sel($switch, 20) ?>>20</option>
										<option value="22" <?php sel($switch, 22) ?>>22</option>
										<option value="24" <?php sel($switch, 24) ?>>24</option>
										<option value="26" <?php sel($switch, 26) ?>>26</option>
										<option value="28" <?php sel($switch, 28) ?>>28</option>
										<option value="36" <?php sel($switch, 36) ?>>36</option>
										<option value="48" <?php sel($switch, 48) ?>>48</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Statistics rounding:</td>
								<td>
									<select name="rounding">
										<?php $switch = $pm["rounding"] ?>
										<option value="0" <?php sel($switch, 0) ?>>0</option>
										<option value="1" <?php sel($switch, 1) ?>>1</option>
										<option value="2" <?php sel($switch, 2) ?>>2</option>
										<option value="3" <?php sel($switch, 3) ?>>3</option>
									</select>
									digits after the coma
								</td>
							</tr>
							<tr><td colspan="2"><input name="headerDisplayCourse" type="checkbox" value="" <?php if($pm['headerDisplayCourse']){echo 'checked';} ?> /> Display course name</td></tr>
							<tr><td colspan="2"><input name="headerDisplayForum" type="checkbox" value="" <?php if($pm['headerDisplayForum']){echo 'checked';} ?> /> Display forum name</td></tr>
							<tr><td colspan="2"><input name="headerDisplayRatingPeriod" type="checkbox" value="" <?php if($pm['headerDisplayRatingPeriod']){echo 'checked';} ?> /> Display rating period</td></tr>
							<tr><td colspan="2"><input name="headerDisplayCourseParticipants" type="checkbox" value="" <?php if($pm['headerDisplayCourseParticipants']){echo 'checked';} ?> /> Display number of course participants</td></tr>
							<tr><td colspan="2"><input name="headerDisplayFeedback" type="checkbox" value="" <?php if($pm['headerDisplayFeedback']){echo 'checked';} ?> /> Display number of feedback posts per student</td></tr>
							<tr><td colspan="2"><input name="headerDisplayPostsPerStudent" type="checkbox" value="" <?php if($pm['headerDisplayPostsPerStudent']){echo 'checked';} ?> /> Display average number of posts per student</td></tr>
							<tr><td colspan="2"><input name="headerDisplayPostsPerDiscussion" type="checkbox" value="" <?php if($pm['headerDisplayPostsPerDiscussion']){echo 'checked';} ?> /> Display average number of posts per discussion</td></tr>
							<tr><td colspan="2"><input name="headerDisplayTransactivity" type="checkbox" value="" <?php if($pm['headerDisplayTransactivity']){echo 'checked';} ?> /> Display transactivity</td></tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('layout')" style="cursor:pointer">.: Layout</h3>
					<div class="blockHide" id="layout">
						<table>
							<col width="150">
							<col width="150">
							<col width="150">
							<col width="150">
							<tr>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Top margin:</td>
								<td><input name="marginTop" type="text" maxlength="4" value="<?php echo $pm['marginTop']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Bottom margin:</td>
								<td><input name="marginBottom" type="text" maxlength="4" value="<?php echo $pm['marginBottom']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
							</tr>
							<tr>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Left margin:</td>
								<td><input name="marginLeft" type="text" maxlength="4" value="<?php echo $pm['marginLeft']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Right margin:</td>
								<td><input name="marginRight" type="text" maxlength="4" value="<?php echo $pm['marginRight']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
							</tr>
							<tr>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) X axis recession:</td>
								<td><input name="axisX" type="text" maxlength="4" value="<?php echo $pm['axisX']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
								<td>X axis thickness:</td>
								<td><input name="axisXthick" type="text" maxlength="4" value="<?php echo $pm['axisXthick']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
							</tr>
							<tr>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Y axis recession:</td>
								<td><input name="axisY" type="text" maxlength="4" value="<?php echo $pm['axisY']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
								<td>Y axis thickness:</td>
								<td><input name="axisYthick" type="text" maxlength="4" value="<?php echo $pm['axisYthick']/$resolution;?>" style="width:50px" /> <em>pixels</em></td>
							</tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('layout_schema')" style="cursor:pointer">.: Layout schema</h3>
					<div class="blockHide" id="layout_schema">
						<table>
							<col width="200">
							<tr>
								<td><img src="images/layout_schema.jpg" width="300" height="375"/></td>
								<td>
									<b>1.</b> Left margin<br/>
									<b>2.</b> Top margin<br/>
									<b>3.</b> Right margin<br/>
									<b>4.</b> Bottom margin<br/>
									<b>X.</b> X axis recession<br/>
									<b>Y.</b> Y axis recession<br/>
									<b>S.</b> Student icon gap<br/>
									<b>D.</b> Day height
								</td>
							</tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('days')" style="cursor:pointer">.: Days</h3>
					<div class="blockHide" id="days">
						<table>
							<col width="250">
							<col width="300">
							<tr>
								<td>Start counting days from:</td>
								<td><input name="firstDay" type="text" maxlength="4" value="<?php echo $pm['firstDay'] ?>" style="width:50px" />
									<input name="showDays" type="checkbox" value="" <?php if($pm['showDays']){echo 'checked';} ?> /> Display day labels
								</td>
							</tr>
							<tr>
								<td>Day label font:</td>
								<td>
									<select name="dayFont">
										<?php $switch = $pm["dayFont"] ?>
										<optgroup label="Classic fonts">
											<option <?php sel($switch, "Arial") ?> value="Arial">Arial</option>
											<option <?php sel($switch, "Arial Italic") ?> value="Arial Italic">Arial - Italic</option>
											<option <?php sel($switch, "Arial Bold") ?> value="Arial Bold">Arial - Bold</option>
											<option <?php sel($switch, "Arial Bold Italic") ?> value="Arial Bold Italic">Arial - Bold Italic</option>
											<option <?php sel($switch, "Courrier New") ?> value="Courrier New">Courrier New</option>
											<option <?php sel($switch, "Times New Roman") ?> value="Times New Roman">Times New Roman</option>
											<option <?php sel($switch, "Times New Roman Italic") ?> value="Times New Roman Italic">Times New Roman - Italic</option>
											<option <?php sel($switch, "Times New Roman Bold") ?> value="Times New Roman Bold">Times New Roman - Bold</option>
											<option <?php sel($switch, "Times New Roman Bold Italic") ?> value="Times New Roman Bold Italic">Times New Roman - Bold Italic</option>
											<option <?php sel($switch, "Verdana") ?> value="Verdana">Verdana</option>
											<option <?php sel($switch, "Verdana Italic") ?> value="Verdana Italic">Verdana - Italic</option>
											<option <?php sel($switch, "Verdana Bold") ?> value="Verdana Bold">Verdana - Bold</option>
											<option <?php sel($switch, "Verdana Bold Italic") ?> value="Verdana Bold Italic">Verdana - Bold Italic</option>
										</optgroup>
										<optgroup label="Artistic fonts">
											<option <?php sel($switch, "Comfortaa") ?> value="Comfortaa">Comfortaa</option>
											<option <?php sel($switch, "Coolvetica") ?> value="Coolvetica">Coolvetica</option>
											<option <?php sel($switch, "Critisized") ?> value="Critisized">Critisized</option>
											<option <?php sel($switch, "Harabara") ?> value="Harabara">Harabara</option>
											<option <?php sel($switch, "OldSansBlack") ?> value="OldSansBlack">OldSansBlack</option>
											<option <?php sel($switch, "Qlassik") ?> value="Qlassik">Qlassik</option>
										</optgroup>
									</select>
									<select name="dayFontSize">
										<?php $switch = $pm["dayFontSize"] / $resolution ?>
										<option value="12" <?php sel($switch, 12) ?>>12</option>
										<option value="14" <?php sel($switch, 14) ?>>14</option>
										<option value="16" <?php sel($switch, 16) ?>>16</option>
										<option value="18" <?php sel($switch, 18) ?>>18</option>
										<option value="20" <?php sel($switch, 20) ?>>20</option>
										<option value="22" <?php sel($switch, 22) ?>>22</option>
										<option value="24" <?php sel($switch, 24) ?>>24</option>
										<option value="26" <?php sel($switch, 26) ?>>26</option>
										<option value="28" <?php sel($switch, 28) ?>>28</option>
										<option value="36" <?php sel($switch, 36) ?>>36</option>
										<option value="48" <?php sel($switch, 48) ?>>48</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Day height:</td>
								<td><input name="dayHeight" type="text" maxlength="4" value="<?php echo $pm['dayHeight']/$resolution ?>" style="width:50px" /> <em>pixels</em></td>
							</tr>
							<tr>
								<td>Day separation line thickness:</td>
								<td><input name="dayLineThick" type="text" maxlength="4" value="<?php echo $pm['dayLineThick']/$resolution ?>" style="width:50px" /> <em>pixels</em>
									<input name="showDayLine" type="checkbox" value="" <?php if($pm['showDayLine']){echo 'checked';} ?> /> Display separation line
								</td>
							</tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('topics')" style="cursor:pointer">.: Topics</h3>
					<div class="blockHide" id="topics">
						<table>
							<col width="250">
							<col width="300">
							<tr>
								<td>Topic separation line thickness:</td>
								<td><input name="discussionLineThick" type="text" maxlength="4" value="<?php echo $pm['discussionLineThick']/$resolution ?>" style="width:50px" /> <em>pixels</em>
								</td>
							</tr>
							<tr>
								<td>Topic name font:</td>
								<td>
									<select name="discussionNameFont">
										<?php $switch = $pm["discussionNameFont"] ?>
										<optgroup label="Classic fonts">
											<option <?php sel($switch, "Arial") ?> value="Arial">Arial</option>
											<option <?php sel($switch, "Arial Italic") ?> value="Arial Italic">Arial - Italic</option>
											<option <?php sel($switch, "Arial Bold") ?> value="Arial Bold">Arial - Bold</option>
											<option <?php sel($switch, "Arial Bold Italic") ?> value="Arial Bold Italic">Arial - Bold Italic</option>
											<option <?php sel($switch, "Courrier New") ?> value="Courrier New">Courrier New</option>
											<option <?php sel($switch, "Times New Roman") ?> value="Times New Roman">Times New Roman</option>
											<option <?php sel($switch, "Times New Roman Italic") ?> value="Times New Roman Italic">Times New Roman - Italic</option>
											<option <?php sel($switch, "Times New Roman Bold") ?> value="Times New Roman Bold">Times New Roman - Bold</option>
											<option <?php sel($switch, "Times New Roman Bold Italic") ?> value="Times New Roman Bold Italic">Times New Roman - Bold Italic</option>
											<option <?php sel($switch, "Verdana") ?> value="Verdana">Verdana</option>
											<option <?php sel($switch, "Verdana Italic") ?> value="Verdana Italic">Verdana - Italic</option>
											<option <?php sel($switch, "Verdana Bold") ?> value="Verdana Bold">Verdana - Bold</option>
											<option <?php sel($switch, "Verdana Bold Italic") ?> value="Verdana Bold Italic">Verdana - Bold Italic</option>
										</optgroup>
										<optgroup label="Artistic fonts">
											<option <?php sel($switch, "Comfortaa") ?> value="Comfortaa">Comfortaa</option>
											<option <?php sel($switch, "Coolvetica") ?> value="Coolvetica">Coolvetica</option>
											<option <?php sel($switch, "Critisized") ?> value="Critisized">Critisized</option>
											<option <?php sel($switch, "Harabara") ?> value="Harabara">Harabara</option>
											<option <?php sel($switch, "OldSansBlack") ?> value="OldSansBlack">OldSansBlack</option>
											<option <?php sel($switch, "Qlassik") ?> value="Qlassik">Qlassik</option>
										</optgroup>
									</select>
									<select name="discussionNameFontSize">
										<?php $switch = $pm["discussionNameFontSize"] / $resolution ?>
										<option value="12" <?php sel($switch, 12) ?>>12</option>
										<option value="14" <?php sel($switch, 14) ?>>14</option>
										<option value="16" <?php sel($switch, 16) ?>>16</option>
										<option value="18" <?php sel($switch, 18) ?>>18</option>
										<option value="20" <?php sel($switch, 20) ?>>20</option>
										<option value="22" <?php sel($switch, 22) ?>>22</option>
										<option value="24" <?php sel($switch, 24) ?>>24</option>
										<option value="26" <?php sel($switch, 26) ?>>26</option>
										<option value="28" <?php sel($switch, 28) ?>>28</option>
										<option value="36" <?php sel($switch, 36) ?>>36</option>
										<option value="48" <?php sel($switch, 48) ?>>48</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Topic reply count font:</td>
								<td>
									<select name="discussionRepliesFont">
										<?php $switch = $pm["discussionRepliesFont"] ?>
										<optgroup label="Classic fonts">
											<option <?php sel($switch, "Arial") ?> value="Arial">Arial</option>
											<option <?php sel($switch, "Arial Italic") ?> value="Arial Italic">Arial - Italic</option>
											<option <?php sel($switch, "Arial Bold") ?> value="Arial Bold">Arial - Bold</option>
											<option <?php sel($switch, "Arial Bold Italic") ?> value="Arial Bold Italic">Arial - Bold Italic</option>
											<option <?php sel($switch, "Courrier New") ?> value="Courrier New">Courrier New</option>
											<option <?php sel($switch, "Times New Roman") ?> value="Times New Roman">Times New Roman</option>
											<option <?php sel($switch, "Times New Roman Italic") ?> value="Times New Roman Italic">Times New Roman - Italic</option>
											<option <?php sel($switch, "Times New Roman Bold") ?> value="Times New Roman Bold">Times New Roman - Bold</option>
											<option <?php sel($switch, "Times New Roman Bold Italic") ?> value="Times New Roman Bold Italic">Times New Roman - Bold Italic</option>
											<option <?php sel($switch, "Verdana") ?> value="Verdana">Verdana</option>
											<option <?php sel($switch, "Verdana Italic") ?> value="Verdana Italic">Verdana - Italic</option>
											<option <?php sel($switch, "Verdana Bold") ?> value="Verdana Bold">Verdana - Bold</option>
											<option <?php sel($switch, "Verdana Bold Italic") ?> value="Verdana Bold Italic">Verdana - Bold Italic</option>
										</optgroup>
										<optgroup label="Artistic fonts">
											<option <?php sel($switch, "Comfortaa") ?> value="Comfortaa">Comfortaa</option>
											<option <?php sel($switch, "Coolvetica") ?> value="Coolvetica">Coolvetica</option>
											<option <?php sel($switch, "Critisized") ?> value="Critisized">Critisized</option>
											<option <?php sel($switch, "Harabara") ?> value="Harabara">Harabara</option>
											<option <?php sel($switch, "OldSansBlack") ?> value="OldSansBlack">OldSansBlack</option>
											<option <?php sel($switch, "Qlassik") ?> value="Qlassik">Qlassik</option>
										</optgroup>
									</select>
									<select name="discussionRepliesFontSize">
										<?php $switch = $pm["discussionRepliesFontSize"] / $resolution ?>
										<option value="12" <?php sel($switch, 12) ?>>12</option>
										<option value="14" <?php sel($switch, 14) ?>>14</option>
										<option value="16" <?php sel($switch, 16) ?>>16</option>
										<option value="18" <?php sel($switch, 18) ?>>18</option>
										<option value="20" <?php sel($switch, 20) ?>>20</option>
										<option value="22" <?php sel($switch, 22) ?>>22</option>
										<option value="24" <?php sel($switch, 24) ?>>24</option>
										<option value="26" <?php sel($switch, 26) ?>>26</option>
										<option value="28" <?php sel($switch, 28) ?>>28</option>
										<option value="36" <?php sel($switch, 36) ?>>36</option>
										<option value="48" <?php sel($switch, 48) ?>>48</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input name="groupPost" type="checkbox" value="" <?php if($pm['groupPost']){echo 'checked';} ?> /> First message of a topic is the Group Post
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input name="groupInfo" type="checkbox" value="" <?php if($pm['groupInfo']){echo 'checked';} ?> /> Display statistics for each group
								</td>
							</tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('students')" style="cursor:pointer">.: Students</h3>
					<div class="blockHide" id="students">
						<table>
							<col width="200">
							<col width="350">
							<tr>
								<td>Name display:</td>
								<td>
									<select name="studentNameDisplay">
										<?php $switch = $pm["studentNameDisplay"] ?>
										<option value="firstname" <?php sel($switch, "firstname") ?>>Firstname</option>
										<option value="lastname" <?php sel($switch, "lastname") ?>>Lastname</option>
										<option value="both" <?php sel($switch, "both") ?>>Firstname &amp; Lastname</option>
										<option value="username" <?php sel($switch, "username") ?>>Username</option>
									</select>
									<input name="studentNameUpperCase" type="checkbox" value="" <?php if($pm['studentNameUpperCase']){echo 'checked';} ?> /> Display in uppercase
								</td>
							</tr>
							<tr>
								<td>Name font:</td>
								<td>
									<select name="studentFont">
										<?php $switch = $pm["studentFont"] ?>
										<optgroup label="Classic fonts">
											<option <?php sel($switch, "Arial") ?> value="Arial">Arial</option>
											<option <?php sel($switch, "Arial Italic") ?> value="Arial Italic">Arial - Italic</option>
											<option <?php sel($switch, "Arial Bold") ?> value="Arial Bold">Arial - Bold</option>
											<option <?php sel($switch, "Arial Bold Italic") ?> value="Arial Bold Italic">Arial - Bold Italic</option>
											<option <?php sel($switch, "Courrier New") ?> value="Courrier New">Courrier New</option>
											<option <?php sel($switch, "Times New Roman") ?> value="Times New Roman">Times New Roman</option>
											<option <?php sel($switch, "Times New Roman Italic") ?> value="Times New Roman Italic">Times New Roman - Italic</option>
											<option <?php sel($switch, "Times New Roman Bold") ?> value="Times New Roman Bold">Times New Roman - Bold</option>
											<option <?php sel($switch, "Times New Roman Bold Italic") ?> value="Times New Roman Bold Italic">Times New Roman - Bold Italic</option>
											<option <?php sel($switch, "Verdana") ?> value="Verdana">Verdana</option>
											<option <?php sel($switch, "Verdana Italic") ?> value="Verdana Italic">Verdana - Italic</option>
											<option <?php sel($switch, "Verdana Bold") ?> value="Verdana Bold">Verdana - Bold</option>
											<option <?php sel($switch, "Verdana Bold Italic") ?> value="Verdana Bold Italic">Verdana - Bold Italic</option>
										</optgroup>
										<optgroup label="Artistic fonts">
											<option <?php sel($switch, "Comfortaa") ?> value="Comfortaa">Comfortaa</option>
											<option <?php sel($switch, "Coolvetica") ?> value="Coolvetica">Coolvetica</option>
											<option <?php sel($switch, "Critisized") ?> value="Critisized">Critisized</option>
											<option <?php sel($switch, "Harabara") ?> value="Harabara">Harabara</option>
											<option <?php sel($switch, "OldSansBlack") ?> value="OldSansBlack">OldSansBlack</option>
											<option <?php sel($switch, "Qlassik") ?> value="Qlassik">Qlassik</option>
										</optgroup>
									</select>
									<select name="studentFontSize">
										<?php $switch = $pm["studentFontSize"] / $resolution ?>
										<option value="12" <?php sel($switch, 12) ?>>12</option>
										<option value="14" <?php sel($switch, 14) ?>>14</option>
										<option value="16" <?php sel($switch, 16) ?>>16</option>
										<option value="18" <?php sel($switch, 18) ?>>18</option>
										<option value="20" <?php sel($switch, 20) ?>>20</option>
										<option value="22" <?php sel($switch, 22) ?>>22</option>
										<option value="24" <?php sel($switch, 24) ?>>24</option>
										<option value="26" <?php sel($switch, 26) ?>>26</option>
										<option value="28" <?php sel($switch, 28) ?>>28</option>
										<option value="36" <?php sel($switch, 36) ?>>36</option>
										<option value="48" <?php sel($switch, 48) ?>>48</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Name padding (vertical):</td>
								<td><input name="studentMarginVertical" type="text" maxlength="4" value="<?php echo $pm['studentMarginVertical']/$resolution ?>" style="width:50px" /> <em>pixels</em>
								</td>
							</tr>
							<tr>
								<td>Name padding (horizontal):</td>
								<td><input name="studentMarginHorizontal" type="text" maxlength="4" value="<?php echo $pm['studentMarginHorizontal']/$resolution ?>" style="width:50px" /> <em>pixels</em>
								</td>
							</tr>
							<tr>
								<td>(<a href="javascript:toggle('layout_schema')" style="text-decoration:none">?</a>) Student icon gap:</td>
								<td><input name="studentSeparation" type="text" maxlength="4" value="<?php echo $pm['studentSeparation']/$resolution ?>" style="width:50px" /> <em>pixels</em>
								</td>
							</tr>
							<tr>
								<td>Rounded corner radius:</td>
								<td><input name="studentRoundedRadius" type="text" maxlength="4" value="<?php echo $pm['studentRoundedRadius']/$resolution ?>" style="width:50px" /> <em>pixels</em>
									<input name="studentRounded" type="checkbox" value="" <?php if($pm['studentRounded']){echo 'checked';} ?> /> Use rounded corners
								</td>
							</tr>
							<tr>
								<td>Border thickness:</td>
								<td><input name="studentBorderThickness" type="text" maxlength="4" value="<?php echo $pm['studentBorderThickness']/$resolution ?>" style="width:50px" /> <em>pixels</em>
									<input name="studentBorder" type="checkbox" value="" <?php if($pm['studentBorder']){echo 'checked';} ?> /> Display border 
									<input name="studentSmartBorderColor" type="checkbox" value="" <?php if($pm['studentSmartBorderColor']){echo 'checked';} ?> /> Smart border colors
								</td>
							</tr>
						</table>
					</div>
						
					<h3 onClick="javascript:toggle('lines')" style="cursor:pointer">.: Connector lines</h3>
					<div class="blockHide" id="lines">
						<table>
							<col width="200">
							<col width="350">
							<tr>
								<td>Line thickness:</td>
								<td><input name="arrowThick" type="text" maxlength="4" value="<?php echo $pm['arrowThick']/$resolution ?>" style="width:50px" /> <em>pixels</em>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input name="arrows" type="checkbox" value="" <?php if($pm['arrows']){echo 'checked';} ?> /> Display arrows
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input name="arrowColors" type="checkbox" value="" <?php if($pm['arrowColors']){echo 'checked';} ?> /> Use student colors
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<input type="radio" name="group1" value="bezier" <?php if($pm['bezier']){echo 'checked';} ?>> Use bezier curves
									<input type="radio" name="group1" value="straight" <?php if(!$pm['bezier']){echo 'checked';} ?>> Use straight lines
								</td>
							</tr>
						</table>
					</div>
				
					<h3 onClick="javascript:toggle('resolution')" style="cursor:pointer">.: Resolution</h3>
					<div class="blockHide" id="resolution">
						<table>
							<col width="150">
							<col width="300">
							<tr>
								<td>Resolution setting:</td>
								<td>
									<select name="resolution">
										<?php $switch = $pm["resolution"] ?>
										<option value="1" <?php sel($switch, 1) ?>>Normal</option>
										<option value="2" <?php sel($switch, 2) ?>>x2</option>
										<option value="3" <?php sel($switch, 3) ?>>x3</option>
										<option value="4" <?php sel($switch, 4) ?>>x4</option>
										<option value="5" <?php sel($switch, 5) ?>>x5</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									A resolution of <b>x2</b> means 4 times more pixels, <b>x3</b> means 9 times more pixels and so on.
								</td>
							</tr>
							<tr>
								<td colspan="2" style="color:red">
									<b>Warning:</b> a high resolution might cause the server to crash if insufficient RAM is installed.
									For a x5 resolution, it is recommended the server has at least 2Gb of RAM available. The higher the resolution, 
									the longer it takes to load the page.
								</td>
							</tr>
						</table>
					</div>
				</div>
			</from>
			
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