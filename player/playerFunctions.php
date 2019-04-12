<?php

function page_title($url) {
    $fp = file_get_contents($url);
    if (!$fp) 
        return null;

    $res = preg_match("/<title>(.*)<\/title>/siU", $fp, $title_matches);
    if (!$res) 
        return null; 

    // Clean up title: remove EOL's and excessive whitespace.
    $title = preg_replace('/\s+/', ' ', $title_matches[1]);
    $title = trim($title);
    return $title;
}

$FIFO_DB_FILENAME = "fifo.db";
$YT_PREFIX = "https://www.youtube.com/watch?v=";

function fullUrl($vidId) {
    global $YT_PREFIX;
    return $YT_PREFIX . $vidId;
}

function insertQueue() {
    echo "<ol>";
    $ids = getFIFO();
    $cnt = 0;
    foreach ($ids as $vidId) {
        $full_url =  fullUrl($vidId);
        
        echo "<li>". $cnt++ . ": " . page_title($full_url) ."</li>";

    }
    echo "</ol>";
}

function getFIFO(){
    global $FIFO_DB_FILENAME;
    
    try {
        $DBH = new PDO("sqlite:${FIFO_DB_FILENAME}");

        $STH = $DBH->query('SELECT * FROM dp_fifo');

        $ids = [];
        while ($row = $STH->fetch(\PDO::FETCH_ASSOC)) {
            $ids[] = $row['vid_id'];
        }
        
        $DBH = NULL;
        return $ids;
    }
    catch (PDOException $e) {
        echo $e;
    }
    
    $DBH = NULL;
}

function insertIntoFIFO($vidId) {
    global $FIFO_DB_FILENAME;

    //if(!in_array($_SERVER['REMOTE_ADDR'], array('192.168.111.95', '192.168.111.97'))){
    //	header('HTTP/1.0 403 Forbidden');
    //    die("badIp");
    //}
    
    try {
        $DBH = new PDO("sqlite:${FIFO_DB_FILENAME}");
        
        $STH = $DBH->prepare('INSERT INTO dp_fifo (vid_id) VALUES (?)');
        $STH->execute(array($vidId));
        
        $DBH = NULL;
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    
    $DBH = NULL;
    
    $cmd="php playThatShizz.php | at now & disown";
    shell_exec($cmd);
}

function popFromFIFO($vidId) {
    global $FIFO_DB_FILENAME;
    
    try {
        $DBH = new PDO("sqlite:${FIFO_DB_FILENAME}");
        
        $STH = $DBH->prepare('DELETE FROM dp_fifo WHERE vid_id = ?');
        $STH->execute(array($vidId));
        
        $DBH = NULL;
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    
    $DBH = NULL;
}

function playFIFO() {
    $busyCheckCmd="ps -aux | grep mplayer | grep bestaudio";
    $str = shell_exec($busyCheckCmd);

    if (strlen($str) > 200) {
        return;
    }
    
    $reqs = getFIFO();
    while (sizeof($reqs)) {

        $full_url = fullUrl($reqs[0]);
        $cmd = "youtube-dl -f \"bestaudio/worstaudio\" -o - \"${full_url}\" | mplayer -cache 1024 -";
        shell_exec($cmd);

        popFromFIFO($reqs[0]);
        $reqs = getFIFO();
    }
}
?>