<?php

class Weather {
	// DB Connection
	private $conn;
	
	public $dateTime;
	public $waterTemp;
	public $airTemp;
	public $humidity;
	
	// Constructor
	public function __construct($db){
		$this->conn = $db;
		$this->dateTime = date('Y-m-d H:i:s');
	}
	
	public function postWeather($waterTemp, $airTemp, $humidity){
		// Add Weather data from microcontroller to database
		$this->waterTemp = $waterTemp; $this->airTemp = $airTemp; $this->humidity = $humidity;
		$query = 'INSERT INTO `weather` (`id`, `dateTime`, `weaterTemp`, `airTemp`, `humidity`) VALUES (NULL, CURRENT_TIMESTAMP, ' . $this->waterTemp . ', ' . $this->airTemp . ', ' . $this->humidity . ')';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return 'sall goodman';
	}
	
	public function getWeather(){
		// If no variables, select the last 28 days worth of data
		echo date('Y-m-d H:i:s');
		$query = 'SELECT * FROM `weather` WHERE `dateTime` BETWEEN "2018-07-26 00:00:00.000000" AND "2018-07-26 23:59:59.999999"';
	}
}

include_once('../config/Database.php');

$database = new Database();
$db = $database->connect();
$weatherTest = new Weather($db);
echo $weatherTest->postWeather(65,95,30) . '<br>';
echo $weatherTest->getWeather();
	
?>