<?php

$vidId = $_POST['vid_id'];

require("playerFunctions.php");
insertIntoFIFO($vidId);

?>