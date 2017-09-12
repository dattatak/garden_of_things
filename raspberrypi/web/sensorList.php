<?php

if (isset($_GET['type'])) {
	echo sensors($_GET['type']);
}
function sensors($x) {

	$db = new SQLite3('sensors.db');
	
	$resultarr = array();
	if ($x == 'temp'){
		$nameStmt = $db->prepare("SELECT sensorID FROM Sensors");
		$result = $nameStmt->execute();
		


		for ($x = 0; $x <= $result->numColumns(); $x++){
			$res = $result->fetchArray();
			array_push($resultarr, $res[0]);
		}
		echo json_encode($resultarr, JSON_FORCE_OBJECT);
	}
	if ($x == 'moisture'){
		$nameStmt = $db->prepare("SELECT pinNumber FROM moistureSensors");
		$result = $nameStmt->execute();
		
		while($res = $result->fetchArray(SQLITE3_ASSOC)){
			if(!isset($res['pinNumber'])) continue;
	
			array_push($resultarr, $res['pinNumber']);
		}

		echo json_encode($resultarr, JSON_FORCE_OBJECT);
	}

//	echo print_r($ids, True);
//	echo "\n";
//	foreach ($ids as $id){
//		echo $id;
//	}
	//$arr = array($name[0] => $results[0]);

	//echo json_encode($arr, JSON_FORCE_OBJECT);
}
?>
