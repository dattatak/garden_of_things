<?php

if (isset($_GET['newValue'])) {
	echo update($_GET['newValue'],$_GET['pinNumber']);
}
function update($val,$pin){
	$db = new SQLite3('/var/www/sensors.db');
	$updateStmt = $db->prepare("UPDATE moistureSensors SET threshold=:val WHERE RJ45Socket=:id");
	$updateStmt->bindValue(':val',$val);
	$updateStmt->bindValue(':id',$pin);
	$updateStmt->execute();

	header('Location: threshold.php?control='.$pin);
}