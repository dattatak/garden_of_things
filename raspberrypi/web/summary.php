<?php

function sensors($x) {

	$db = new SQLite3('/var/www/sensors.db');
	
	$resultarr = array();
	if ($x == 'temp'){
		$nameStmt = $db->prepare("SELECT sensorID FROM temperatureSensors");
		$result = $nameStmt->execute();
		


		for ($x = 0; $x <= $result->numColumns(); $x++){
			$res = $result->fetchArray();
			array_push($resultarr, $res[0]);
		}
		return $resultarr;
	}
	if ($x == 'moisture'){
		$nameStmt = $db->prepare("SELECT pinNumber FROM moistureSensors");
		$result = $nameStmt->execute();
		
		while($res = $result->fetchArray(SQLITE3_ASSOC)){
			if(!isset($res['pinNumber'])) continue;
	
			array_push($resultarr, $res['pinNumber']);
		}
		return $resultarr;
	}
}


function moisturePin() {
	
	$result = array();
	
	$db = new SQLite3('/var/www/sensors.db');
	$stmt = $db->prepare("SELECT RJ45Socket, rollingAverage, threshold FROM moistureSensors");
    $return = $stmt->execute();
    while($item = $return->fetchArray()){
        array_push($result, $item);
    }
	return($result);
}

function tempPin($x) {
	
	$result = array();
	
	$db = new SQLite3('/var/www/sensors.db');
	$stmt = $db->prepare("SELECT rollingAverage FROM temperatureSensors WHERE sensorID=:id");
	$stmt->bindValue(':id',$x);
	array_push($result, $stmt->execute()->fetchArray()[0]);

	
	//$thresholdStmt = $db->prepare("SELECT thresholdValue FROM controlPins WHERE associatedSensor=:id AND sensorType == 'moisture'");
	//$thresholdStmt->bindValue(':id',$x);
	//$results = $thresholdStmt->execute()->fetchArray();
	//array_push($result, $thresholdStmt->execute()->fetchArray()[0]);
	return($result);
}

$moistureArray = sensors("moisture");
$tempArray = sensors("temp");
?>


<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<script>
function updateThreshold(val, port){
	var xhttp;
	xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if (this.readyState == 4 && this.status == 200){
			document.getElementById("thresholdPin"+port).innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "updateThreshold.php?newValue="+val+"&pinNumber="+port, true);
	xhttp.send();
}

</script>

<style>
table, th, td {
    border: 1px solid black;
}
</style>
<body>
	<h1>
	Moisture Sensors
	</h1>
	<p></p>
	<table class="table table-striped">
		<tr>
			<th>Port Number</th>
			<th>Sensor Type</th>
			<th>Most Recent Value</th>
			<th>Threshold</th>
			<th>New Value</th>
		</tr>
		<?php 
        $values = moisturePin();
        for($x = 0; $x < count($moistureArray); $x++){
			$portNumber = $moistureArray[$x]+1;
			echo "<tr>
					<td>{$values[$x][0]}</td>
					<td>Moisture</td>
					<td>{$values[$x][1]}</td>
					<td id=\"thresholdPin{$portNumber}\">{$values[$x][2]}</td>
					<td><input type=\"text\" id=\"newValue{$portNumber}\"></input><button onClick=\"updateThreshold(document.getElementById('newValue{$portNumber}').value, {$portNumber})\">Ok!</button></td>
				</tr>";
		}?>
		</table>
		
	<h1>
	Temperature Sensors
	</h1>
	<p></p>
	<table class="table table-striped">
		<tr>
			<th>Port Number</th>
			<th>Sensor Type</th>
			<th>Most Recent Value</th>
		</tr>
		<?php for($x = 0; $x < count($tempArray); $x++){
			$value = tempPin($x);
			$portNumber = $tempArray[$x]+1;
			echo "<tr>
					<td>{$portNumber}</td>
					<td>Temperature</td>
					<td>{$value[0]}&deg;C</td>
				</tr>";
		}?>
</body>

</html>