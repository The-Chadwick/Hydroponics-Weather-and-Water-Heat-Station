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
		
		return true;
	}
	
	public function getWeather(...$args){
		if(isset($args[0])){
			echo "there is at least one argument";
		} else{
			echo "there are no arguments";
		}
		$query = 'SELECT * FROM `weather` WHERE `dateTime` BETWEEN "2018-07-26 00:00:00.000000" AND "2018-08-1 23:59:59.999999"';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return $stmt;
	}
}

include_once('../config/Database.php');

$database = new Database();
$db = $database->connect();
$weatherTest = new Weather($db);
echo $weatherTest->postWeather(65,95,30) . '<br>';
$weatherTest->getWeather('test');
$weatherTest->getWeather();
	
?>