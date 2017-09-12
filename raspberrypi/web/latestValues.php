<?php

if (isset($_GET['type'])) {
	echo values($_GET['type']);
}
function values($pin) {

	$db = new SQLite3('/var/www/sensors.db');
	
	$resultarr = array();
    $nameStmt = $db->prepare("SELECT sample FROM moistureValues WHERE pinNumber =:id ORDER BY rowid DESC LIMIT 30");
    $updateStmt->bindValue(':id', $pin);
    $result = $nameStmt->execute();

    for ($x = 0; $x <= $result->numColumns(); $x++){
        $res = $result->fetchArray();
        array_push($resultarr, $res[0]);
    }
    echo json_encode($resultarr, JSON_FORCE_OBJECT);
}
?>
