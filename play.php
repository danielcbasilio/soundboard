<?php

require 'functions.php';
require 'vendor/autoload.php';

use wapmorgan\Mp3Info\Mp3Info;

$file=$_GET['file'];

// Let the filtering begin!

$file=str_replace('..', '', $file);
$file=str_replace('/', '', $file);
$file=str_replace('\'', '\\\'', $file);

// filtering done!

if(!fileExists($file)){
        die("die, you jerk");
}

$cmd="ps -aux | grep mpg321 | grep sounds";
//echo strlen(shell_exec($cmd));
if (strlen(shell_exec($cmd)) > 110) {
	die("alreadyPlaying");
}

$audio = new Mp3Info("sounds/$file", true);
$durationOverwride = file("configurationFiles/durationOverride.txt", FILE_IGNORE_NEW_LINES);
if($audio->duration > 10 && !in_array(hash_file("md5", "sounds/{$file}"), $durationOverwride)){
	die("too long, you fool");
}

if(!in_array($_SERVER['REMOTE_ADDR'], array('192.168.111.95', '192.168.111.97'))){
//	header('HTTP/1.0 403 Forbidden');
	die("badIp");
}

$forbidden = file("configurationFiles/forbidden.txt", FILE_IGNORE_NEW_LINES);
if(in_array(hash_file("md5", "sounds/{$file}"), $forbidden)){
	die("forbidden");
}

$cmd="mpg321 'sounds/{$file}' | at now & disown";

//echo $cmd;
//var_dump(shell_exec($cmd));
shell_exec($cmd);
die("success");


?>
