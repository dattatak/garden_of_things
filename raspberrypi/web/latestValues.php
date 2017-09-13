<?php

if (isset($_GET['pin'])) {
	echo values($_GET['pin'], $_GET['t']);
}
function values($pin, $lim) {

	$db = new SQLite3('/var/www/sensors.db');
	
	$sampleArray = array();
    $timeArray = array();
    
    $nameStmt = $db->prepare("SELECT updateTime, sample FROM moistureValues WHERE pinNumber =:id ORDER BY rowid DESC LIMIT :limit");
    $nameStmt->bindValue(':id', $pin);
    $nameStmt->bindValue(':limit', $lim);
    $result = $nameStmt->execute();
    
    $nrows = 0;
    $result->reset();
    while ($result->fetchArray())
        $nrows++;
    $result->reset();

    for ($x = 0; $x <= $nrows-1; $x++){
        $res = $result->fetchArray();
        array_push($sampleArray, $res[1]);
        array_push($timeArray, $res[0]);
    }
    $resultarr = [$sampleArray, $timeArray];
    echo json_encode($resultarr);
}
?>
