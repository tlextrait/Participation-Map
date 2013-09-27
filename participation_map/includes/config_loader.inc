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

	/*
	========================================
	Configuration file loader
	========================================
	*/
	
	// Is there a config file for current logged user?
	$userid = $USER->id;
	if(!file_exists($CFG->dataroot."/participation_map/config/user$userid.bgs")){
		copy("config/default.bgs", $CFG->dataroot."/participation_map/config/user$userid.bgs");
	}
	
	$file = fopen($CFG->dataroot."/participation_map/config/user$userid.bgs", "r");
	$config = fread($file, 10000000);
	fclose($file);
	
	$config = explode("\n", $config);
	
	$pm = array();
	
	for($i=0; $i < count($config); $i++){
		
		$line = trim($config[$i]);
		if(str_starts($line, "$")){
			
			$line = str_replace("$", "", $line);
			$line = explode("=", $line);
			$var = trim($line[0]);
			$value = explode(";", $line[1]);
			$value = trim($value[0]);
			
			if(strstr($value, '"') || strstr($value, "'")){
				// Its a string
				$value = str_replace('"', '', $value);
				$value = str_replace("'", "", $value);
				$pm[$var] = $value;
			}else if(strstr($value, "true") || strstr($value, "false")){
				// Boolean
				if($value=="true"){
					$pm[$var] = true;
				}else{
					$pm[$var] = false;
				}
			}else if(strstr($value, ",")){
				// Its a color
				$color = explode(",", $value);
				$pm[$var] = array("R" => trim($color[0]), "G" => trim($color[1]), "B" => trim($color[2]));
			}else{
				// Its a number
				$pm[$var] = $value;
			}
		}
	}

	
	/** 
	* Checks if a string starts with given text
	* @param $string, string to be checked
	* @param $check, check if string starts with this
	* @return boolean
	*/
	function str_starts($string, $check){
		$string = trim($string); // clear up returns "\n" and spaces
		$check = trim($check);
		if(substr($string, 0, strlen($check))==$check){
			return true;
		}else{
			return false;
		}
	}
	
	/** 
	* Checks if a string ends with given text
	* @param $string, string to be checked
	* @param $check, check if string ends with this
	* @return boolean
	*/
	function str_ends($string, $check){
		$string = trim($string); // clear up returns "\n" and spaces
		$check = trim($check);
		if(substr($string, strlen($string)-strlen($check), strlen($check))==$check){
			return true;
		}else{
			return false;
		}
	}

?>