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

	<script type="text/javascript">
		createBG();
		function createBG(){
			var xmlhttp;
			if(window.XMLHttpRequest){
				// IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}else{
				// IE6, IE5
				xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
			    	document.getElementById('bg_result').innerHTML=xmlhttp.responseText;
				}
			}
			
			var parameters = 'course=<?php echo $_GET["course"]; ?>&forum=<?php echo $_GET["forum"]; ?><?php if(isset($_GET["anonymous"])){echo "&anonymous=1";} ?><?php if(isset($_GET["partforum"])){echo "&partforum=1";} ?>';
			xmlhttp.open('POST','plot_ajax.php',true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.send(parameters);
		}
	</script>

	<div id="main" class="s1000">
		
		<?php require_once "includes/heading.inc"; ?>
		<?php require_once "includes/navbar1000.inc"; ?>
	
		<div class="block1000" id="bg_result">
			<center>
				<noscript>
					<span class="red">This page uses Ajax and requires Javascript. Please enable Javascript or <a href="plot_legacy.php?course=<?= $_GET['course'] ?>&forum=<?= $_GET['forum'] ?>">click here to plot without Javascript (anonymous graphs not supported here)</a>.</span><br/><br/>
				</noscript>
				
				<img src="images/Studifi-Spinner-50.gif" /><br/>
				<span class="suggestion">Plotting... this may take a few minutes</span>
			</center>
		</div>
	
		<?php require_once "includes/footer1000.inc"; ?>
	</div>
	
</body>
</html>