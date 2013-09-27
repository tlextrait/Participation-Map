<script type="text/javascript">
	reportPartMapInstall();
	function reportPartMapInstall(){
		var xmlhttp;
		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
		}
		
		var MoodleVersion = encodeURIComponent("<?php echo getMoodleVersion() ?>");
		var MoodleBuild = encodeURIComponent("<?php echo getMoodleBuild() ?>");
		var SQLVer = encodeURIComponent("<?php echo getSQLVersion() ?>");
		var PMVer = encodeURIComponent("<?php echo getBGIntVersion() ?>");
		var MoodleURL = encodeURIComponent("<?php echo $CFG->wwwroot ?>");
		
		var parameters = "moodle_version="+MoodleVersion+"&moodle_build="+MoodleBuild+"&sql="+SQLVer+"&pm="+PMVer+"&moodle_url="+MoodleURL;
		xmlhttp.open('POST','http://participation_map.org/tracker.php',true);
		xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		xmlhttp.send(parameters);
	}
</script>
