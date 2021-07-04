<?php
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
        exit();
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
            echo "Data Recieved: \n\t"
                . "Water Temperature = " . $_POST['waterTemp'] . "\n\t"
                    . "Air Temperature = " . $_POST['airTemp'] . "\n\t"
                        . "Humidity = " . $_POST['humidity'] . "\n\t";
        }
        else {
            echo $message;
            exit();
        }

            // send text message to each person in charge if temps get too hot
        if ($waterTemp > 85) {
            // plug any phone number and domain here for alerts
            $contactArray = array(
                '3852108547@vtext.com',
                '8015979532@vtext.com'
            );

            // text message body for hot temperatures
            $textMessage = "WARNING: Temperatures are too hot\nCurrent Temperature: " + $waterTemp;

            // loops through contact array to send text message to each contact
            foreach ($contactArray as $value) {
                mail($value, '', $textMessage);
            }
        } elseif ($waterTemp < 60) {
            // text message body for cold temperatures
            $textMessage = "WARNING: Temperatures are too cold\nCurrent Temperature: " + $waterTemp;

            // loops through contact array to send text message to each contact
            foreach ($contactArray as $value) {
                mail($value, '', $textMessage);
            }
        }
    }
} else if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // If a get request is recieved
    if(isset($_GET['start']) && isset($_GET['end'])){
        // returns weather data from date range
        //		$startDate = new DateTime($_GET['start']);
        //		$endDate = new DateTime($_GET['end']);
        //
        //		echo json_encode($weather->getWeather($startDate, $endDate));
        
        $startDate = DateTime::createFormFormat('')
        
        echo json_encode($weather->getWeather($_GET['start'], $_GET['end']));
    } elseif(isset($_GET['startDate']) && !isset($_GET['endDate'])){
        // returns weather data between start time and now
        $startDate = new DateTime($_GET['startDate']);
        
        
        echo json_encode($weather->getWeather($startDate));
    } else echo json_encode($weather->getWeather()); // returns last 30 days of data
}
?>
