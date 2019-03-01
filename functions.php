<?php

function getSoundFiles(){
	$dir=scandir('sounds');
	unset($dir[0]); // remove .
	unset($dir[1]); // remove ..
	$ret=array();
	foreach($dir as $f){
		if(substr($f, -3)!='mp3'){
			continue;
		}
		array_push($ret, $f);
	}
	return $ret;
}

function insertButtons(){
	$nsfw = file("configurationFiles/nsfw.txt", FILE_IGNORE_NEW_LINES);
	foreach(getSoundFiles() as $f){
		$fname= substr($f, 0, strlen($f)-4); // 4 chars for '.mp3'
		if(in_array($f, $nsfw)){
			echo "<button class = \"nsfw\" title=\"NSFW\">{$fname}</button>";
		}else{
			echo "<button>{$fname}</button>";
		}
		
	}
}

function fileExists($fname){
	
	$flist=getSoundFiles();
	
	return in_array($fname, $flist);
}
?>
