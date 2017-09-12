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
    
    $nrows = 0;
    $result->reset();
    while ($result->fetchArray())
        $nrows++;
    $result->reset();

    for ($x = 0; $x <= $nrows; $x++){
        $res = $result->fetchArray();
        array_push($resultarr, $res[0]);
    }
    echo json_encode($resultarr);
}
?>
