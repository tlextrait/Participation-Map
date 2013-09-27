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

	function isUserAdmin(){
		$context = get_context_instance(CONTEXT_SYSTEM);
		return has_capability("moodle/site:config", $context) || has_capability("moodle/site:doanything", $context);
	}
	
	function isUserInstructor($courseid){
		$context = get_context_instance(CONTEXT_COURSE, $courseid);
		return has_capability("moodle/course:update", $context);
	}
	
	function getMoodleVersion(){
		// Get Moodle Version
		include("../version.php");
		if($release==""){$release="?";}
		// Isolate version # (remove build #)
		$release = explode(" (", $release);
		$release = $release[0];
		return $release;
	}
	
	function getMoodleBuild(){
		// Get Moodle Version
		include("../version.php");
		if($release==""){$release="?";}
		// Isolate build # (remove version #)
		$release = explode("Build: ", $release);
		$release = $release[1];
		$release = str_replace(")", "", $release);
		return $release;
	}

	function getSQLVersion() {
	   $output = shell_exec('mysql -V');
	   if($output){
		 	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	   		return $version[0];
	   }else{
		 	return "?";
	   }
	}

	function getBGVersion(){
		$file = fopen("includes/ver.txt", "r");
		$ver = fread($file, 1000);
		fclose($file);
		
		$ver = explode("|", $ver);
		return $ver[0];
	}
	
	function getBGIntVersion(){
		$file = fopen("includes/ver.txt", "r");
		$ver = fread($file, 200);
		fclose($file);
		
		$ver = explode("|", $ver);
		return $ver[1];
	}
	
	function getUpdateVer(){
		try{
			copy("http://bushgrapher.org/updater/ver.txt", "includes/update_ver.txt");
			
			$file = fopen("includes/update_ver.txt", "r");
			$ver = fread($file, 200);
			fclose($file);
			
			$ver = explode("|", $ver);
			return $ver;
		}catch(Exception $e){
			return "0";
		}
	}
	
	function getUpdateInfo(){
		try{
			copy("http://bushgrapher.org/updater/info.txt", "includes/update_info.txt");
			
			$file = fopen("includes/update_info.txt", "r");
			$info = fread($file, 200);
			fclose($file);
			
			return $info;
		}catch(Exception $e){
			return "0";
		}
	}
	
	function checkMoodleCompatibility(){
		$version = substr(getMoodleVersion(), 0, 3);
		switch($version){
			case "1.9":
			case "2.0":
			case "2.1":
			case "2.2":
				return true;
				break;
			default:
				return false;
				break;
		}
	}
	
	function dirSize($directory) {
	    $size = 0;
	    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
	        $size+=$file->getSize();
	    }
	    return $size;
	}
	
	function convertFileSize($size){

		if($size < 100){
			$size.=" B";
		}else{
			$size /= 1024;
			if($size < 900){
				if($size < 10){$size = round($size, 1);}else{$size = round($size, 0);}
				$size.=" KB";
			}else{
				$size /= 1024;
				if($size < 10){$size = round($size, 1);}else{$size = round($size, 0);}
				$size = round($size, 1);
				$size.=" MB";
			}
		}

		return $size;
	}
	
	function deleteDirectory($dir){
		if(!file_exists($dir)){return true;}
		if(!is_dir($dir) || is_link($dir)){return unlink($dir);}
		foreach(scandir($dir) as $item) {
		    if ($item == '.' || $item == '..') continue;
		    if (!deleteDirectory($dir . "/" . $item)) {
		        chmod($dir . "/" . $item, 0777);
		        if (!deleteDirectory($dir . "/" . $item)) return false;
		    };
		}
		return rmdir($dir);
	}
	
	function fileRead($path){
		$file = fopen($path, "r");
		$value = fread($file, 100000);
		fclose($file);
		
		return $value;
	}
	
	function fileWrite($path, $value){
		$file = fopen($path, "w+");
		fwrite($file, $value);
		fclose($file);
		
		return true;
	}

?>