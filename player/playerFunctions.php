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

function queueJSON($code) {
    
    $json = array(
        "status" => $code,
        "items" => []
    );
    
    $vids = getFIFO();
    foreach ($vids as $vid) {
        $item = array(
            "title" => $vid["vidTitle"]
        );
        array_push($json["items"], $item);
    }

    return json_encode($json);
}

function getFIFO(){
    global $FIFO_DB_FILENAME;
    
    $vids = [];
    try {
        $DBH = new PDO("sqlite:${FIFO_DB_FILENAME}");

        $STH = $DBH->query('SELECT * FROM dp_fifo');

        while ($row = $STH->fetch(\PDO::FETCH_ASSOC)) {
            $vid = array(
                'vidId' => $row['vid_id'],
                'vidTitle' => $row['vid_title']
            );
            array_push($vids, $vid);
        }
    }
    catch (PDOException $e) {
        echo $e;
    }
    
    $DBH = NULL;
    return $vids;
}

function insertIntoFIFO($vidId) {
    global $FIFO_DB_FILENAME;

    if(!in_array($_SERVER['REMOTE_ADDR'], array('192.168.111.95', '192.168.111.97'))){
    //	header('HTTP/1.0 403 Forbidden');
        return;
    }

    if ($vidId == "") {
        echo queueJSON(1);
        return;
    }
    
    try {
        $DBH = new PDO("sqlite:${FIFO_DB_FILENAME}");

        $vidTitle = page_title(fullUrl($vidId));
        
        $STH = $DBH->prepare('INSERT INTO dp_fifo (vid_id, vid_title) VALUES (?, ?)');
        $STH->execute(array($vidId, $vidTitle));
        
        $DBH = NULL;
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
    
    $DBH = NULL;
    
    $cmd="php playThatShizz.php | at now & disown";
    shell_exec($cmd);

    $code = 0;
    echo queueJSON($code);
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

        $full_url = fullUrl($reqs[0]['vidId']);
        $cmd = "youtube-dl -f \"bestaudio/worstaudio\" -o - \"${full_url}\" | mplayer -cache 1024 -";
        shell_exec($cmd);

        popFromFIFO($reqs[0]['vidId']);
        $reqs = getFIFO();
    }
}
?>