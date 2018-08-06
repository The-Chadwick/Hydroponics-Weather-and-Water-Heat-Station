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
	if(!isset($_POST['macAddress']) || !isset($_POST['waterTemp']) || !isset($_POST['airTemp']) ||  !isset($_POST['humidity'])) {
		echo 'Missing Data';
	} else{
		$clean = array();
		foreach($_POST as $key => $value){
			$value = trim($value);
			$value = stripslashes($value);
			$value = htmlspecialchars($value);
			$clean[$key] = $value;
		}
		if($clean['macAddress'] == 'AC-22-0B-75-B8-3C'){
			// checks valid mac address
			$macAddress = $clean['macAddress'];
		} else echo 'error 1<br/>' . $clean['macAddress'];
		if($clean['waterTemp'] >= 0 && $clean['waterTemp'] <= 150){
			// checks if temperature is within reasonable range
			$waterTemp = $clean['waterTemp'];	
		} else echo 'error 2<br>';
		if($clean['airTemp'] >= 0 && $clean['airTemp'] <= 150){
			// checks if temperature is within reasonable range
			$airTemp = $clean['airTemp'];
		} else echo 'error 3<br>';
		if($clean['humidity'] >= 0 && $clean['humidity'] <= 100){
			// checks if humidity is within reasonable range
			$humidity = $clean['humidity'];
		} else echo 'error 4<br>';
	}
	
	echo $weather->postWeather($waterTemp, $airTemp, $humidity);
} elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
	// If a get request is recieved
	echo json_encode($weather->getWeather());
}

?>