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
	
	public function postWeather($waterTemp, $airTemp, $humidity, $address){
		// Add Weather data from microcontroller to database
		$this->waterTemp = $waterTemp;
		$this->airTemp = $airTemp;
		$this->humidity = $humidity;
		$this->address = $address;
		
		$query = 'INSERT INTO `weather` (`id`, `dateTime`, `weaterTemp`, `airTemp`, `humidity`, `address`) VALUES (NULL, CURRENT_TIMESTAMP, ' . $this->waterTemp . ', ' . $this->airTemp . ', ' . $this->humidity . ', "' . $this->address . '")';
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		
		return 'Success!<br>';
	}
	
	public function getWeather(...$args){
		$start; $end;
		if(isset($args[1]) && isset($args[0])){
			// getWeather($start, $end);
			$start = '2018-08-06 00:00:00';
			$end = '2018-08-06 23:59:59';
			$query = 'SELECT * FROM `weather` WHERE `dateTime` BETWEEN "' . $start . '" AND "' . $end . '"';
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