<?php 

class Db {

	protected static $connection;

	public function connect() {

		if(!isset(self::$connection)) {
			$path='/etc/ccharacter/dbi_config.ini'; // echo $path;
			
			if (file_exists($path)) { 
				$config = parse_ini_file($path); //print_r($config);
				
				self::$connection = new mysqli($config['dbhost'],$config['username'],$config['password'],$config['dbname']);
				$_SESSION['sew']['which']=$config['prefix'];
				
			} else { self::$connection = false;  }
		} 
	
		if(self::$connection === false) {
			return false;
		}

		self::$connection->set_charset("utf8");
		
		return self::$connection;
	}
	

	public function query($query) {
		$connection = $this -> connect();
		if ($connection) {
			$result = $connection -> query($query);
			if($result === false) {
				error_log("ERROR: ". $connection->error,0);
				return false;
			}						
			return $result;
			
			mysqli_close($connection);
		}
	}
	
	public function select($query) {
		$connection = $this -> connect();
		if ($connection) {
			$rows = array();
			$result = $this -> query($query);
			if($result === false) {
				error_log("ERROR: ". $connection->error,0);
				return false;
			}
			while ($row = $result -> fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
			
			mysqli_close($connection);
		}
	}
	
	public function error() {
		$connection = $this -> connect();
		return $connection -> error;
	}
	
	public function quote($value) {
		$connection = $this -> connect();
		return "'" . $connection -> real_escape_string($value) . "'";
	}
}
