<?php

$db = new SQLite3('sensors.db');

$resultarr = array();

$nameStmt = $db->prepare("SELECT pinNumber, controlName FROM controlPins");
$result = $nameStmt->execute();

while($res = $result->fetchArray(SQLITE3_ASSOC)){
	if(!isset($res['pinNumber'])) continue;
	$itemArr = array("PinNumber" => $res['pinNumber'], "Name" => $res['controlName']);
	
	array_push($resultarr, $itemArr);
}

echo json_encode($resultarr);

?>