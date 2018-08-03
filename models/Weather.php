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
		$start; $end;
		if(isset($args[1]) && isset($args[0])){
			// getWeather($start, $end);
			echo 'Fetching data from ' . $args[0] . ' to ' . $args[1] . '.';
			$query = 'SELECT * FROM `weather` WHERE `dateTime` BETWEEN "2018-07-26 00:00:00.000000" AND "2018-08-1 23:59:59.999999"';
		} else if(isset($args[0]) && !isset($args[1])){
			// getWeather($start);
			echo 'Fetching data from ' . $args[0] . ' onwards.';
		} else{
			// getWeather();
			$query = 'SELECT * FROM `weather`';
		}
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		// converts to json for return
		$data = array();
		$data['weather'] = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$data['weather'][] = $row;
		}
		
		return $data;
	}
}

	
?>