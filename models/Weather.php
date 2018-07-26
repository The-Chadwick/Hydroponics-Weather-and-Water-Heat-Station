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
		// mainly for use via the arduino weaether station
		$this->waterTemp = $waterTemp; $this->airTemp = $airTemp; $this->humidity = $humidity;
		$query = 'INSERT INTO `weather` (`dateTime`, `waterTemp`, `airTemp`, `humidity`) VALUES (' . $this->dateTime . ' , ' . $this->waterTemp . ' , ' . $this->airTemp . ' , ' . $this->humidity . ')';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return 'sall goodman';
	}
	
	public function getWeather(){
		
		$query = 'SELECT * FROM `weather WHERE `dateTime` ';
	}
}

include_once('../config/Database.php');

$db = new Database();
$db = $database->connect();
$weatherTest = new Weather($db);
$weatherTest->postWeather(65,95,30);
	
?>