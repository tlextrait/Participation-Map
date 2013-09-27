<?php
	$course = $_GET["course"];
?>

<a href="index.php?course=<?php echo $course ?>">Forums</a>
<a href="settings.php?course=<?php echo $course ?>">Settings</a>
<a href="about.php?course=<?php echo $course ?>">About</a>

<?php
	
	if(isUserAdmin()){
		if(!checkMoodleCompatibility()){
			$error = " Warning";
			$style = "color:red";
		}else{
			$error = "";
			$style = "";
		}
		echo "<a href='info.php?course=",$course,"' style='",$style,"'>?",$error,"</a>";
	}

?>