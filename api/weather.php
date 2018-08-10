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
	$address = $waterTemp = $airTemp = $humidity = '';
	if(!isset($_POST['address']) || !isset($_POST['waterTemp']) || !isset($_POST['airTemp']) ||  !isset($_POST['humidity'])) {
		echo 'Missing Data';
	} else{
		$clean = array();
		$message = '';
		foreach($_POST as $key => $value){
			$value = trim($value);
			$value = stripslashes($value);
			$value = htmlspecialchars($value);
			$clean[$key] = $value;
		}
		if(preg_match('/([a-fA-F0-9]{2}[:|\-]?){6}/', $clean['address'])){
			// checks valid mac address
			$address = $clean['address'];
		} else $message = 'error 1<br/>';
		if($clean['waterTemp'] >= 0 && $clean['waterTemp'] <= 150){
			// checks if temperature is within reasonable range
			$waterTemp = $clean['waterTemp'];	
		} else $message = 'error 2<br>';
		if($clean['airTemp'] >= 0 && $clean['airTemp'] <= 150){
			// checks if temperature is within reasonable range
			$airTemp = $clean['airTemp'];
		} else $message = 'error 3<br>';
		if($clean['humidity'] >= 0 && $clean['humidity'] <= 100){
			// checks if humidity is within reasonable range
			$humidity = $clean['humidity'];
		} else $message = 'error 4<br>';
		
		// send to database or exit if there was an error from the data
		if($message == ''){
			$message = $weather->postWeather($waterTemp, $airTemp, $humidity, $address);
			echo $message;
		}
		else {
			echo $message;
			exit();
		}
		
		// send text message to each person in charge if temps get too hot
		if($waterTemp > 85){
			
		} elseif($waterTemp < 60){
			
		}
	}
} else if($_SERVER['REQUEST_METHOD'] == 'GET'){
	// If a get request is recieved
	echo json_encode($weather->getWeather());
}

?>