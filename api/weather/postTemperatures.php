<?php

//echo "Data Recieved: \n\t"
//	. "Water Temperature = " . $_POST['waterTemp'] . "\n\t"
//	. "Air Temperature = " . $_POST['airTemp'] . "\n\t"
//	. "Humidity = " . $_POST['humidity'] . "\n\t"
//	. "Mac Adress = " . $_POST['address'] . "\n\t";

include_once('../../config/Database.php');
include_once('../../models/Weather.php');

$database = new Database();
$db = $database->connect();
$weatherTest = new Weather($db);
echo json_encode($weatherTest->getWeather());

?>