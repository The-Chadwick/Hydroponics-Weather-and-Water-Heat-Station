<?php

//echo "Data Recieved: \n\t"
//	. "Water Temperature = " . $_POST['waterTemp'] . "\n\t"
//	. "Air Temperature = " . $_POST['airTemp'] . "\n\t"
//	. "Humidity = " . $_POST['humidity'] . "\n\t"
//	. "Mac Adress = " . $_POST['address'] . "\n\t";

include_once('../config/Database.php');
include_once('../models/Weather.php');

$database = new Database();
$db = $database->connect();
$weather = new Weather($db);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// If a post request is recieved
	
	// validation
	$macAddress = $waterTemp = $airTemp = $humidity = '';
	if(empty($_POST['macAddress']) || empty($_POST['waterTemp']) || empty($_POST['airTemp']) ||  empty($_POST['humidity'])) {
		echo 'error';
	} else{
		$clean = array();
		foreach($_POST as $key => $value){
			$value = trim($value);
			$value = stripslashes($value);
			$value = htmlspecialchars($value);
			$clean[$key] = $value;
		}
		if(preg_match('/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/', $clean['macAddress']) == 1){
			// checks valid mac address
			$macAddress = $clean['macAddress'];
		} else echo 'error';
		if($clean['waterTemp'] >= 0 && $clean['waterTemp'] <= 150){
			// checks if temperature is within reasonable range
			$waterTemp = $clean['waterTemp'];	
		} else echo 'error';
		if($clean['airTemp'] >= 0 && $clean['airTemp'] <= 150){
			// checks if temperature is within reasonable range
			$airTemp = $clean['airTemp'];
		} else echo 'error';
		if($clean['humidity'] >= 0 && $clean['humidity'] <= 100){
			// checks if humidity is within reasonable range
			$humidity = $clean['humidity'];
		} else echo 'error';
	}
	
	echo $weather->postWeather($waterTemp, $airTemp, $humidity);
} elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
	// If a get request is recieved
	echo json_encode($weather->getWeather());
}

echo 'test';
?>