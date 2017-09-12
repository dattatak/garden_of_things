<?php

if (isset($_GET['control'])) {
	echo pin($_GET['control']);
    exit();
}
function pin($x) {
	$db = new SQLite3('/var/www/sensors.db');
	$nameStmt = $db->prepare("SELECT RJ45Socket FROM moistureSensors WHERE pinNumber=:id");
	$nameStmt->bindValue(':id',$x);
	$name = $nameStmt->execute()->fetchArray();

	$stmt = $db->prepare("SELECT threshold FROM moistureSensors WHERE RJ45Socket=:id");
	$stmt->bindValue(':id',$x);
	$results = $stmt->execute()->fetchArray();

	$arr = array($name[0] => $results[0]);

	echo $results[0];
}
?>

<html>
<body>
    <form action="updateThreshold.php" method="get">
        <br>
        Change Threshold Value: 
        <input type="text" name="newValue"><br>
        Pin:
        <input type="text" name="pinNumber" value=<?php echo $_GET['control']?>><br><br>
        
        <input type = "submit" value="Submit">
    </form>
</body>
