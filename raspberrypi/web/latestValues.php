<?php

if (isset($_GET['pin'])) {
	echo values($_GET['pin']);
}
function values($pin) {

	$db = new SQLite3('/var/www/sensors.db');
	
	$resultarr = array();
    $nameStmt = $db->prepare("SELECT sample FROM moistureValues WHERE pinNumber =:id ORDER BY rowid DESC LIMIT 30");
    $nameStmt->bindValue(':id', $pin);
    $result = $nameStmt->execute();
    
    
    echo json_encode($result->fetchArray(), JSON_FORCE_OBJECT);
}
?>
