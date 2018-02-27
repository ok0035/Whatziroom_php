<?php

class ClassDB
{
	public function ClassDB(){}

	public function getConn(){
	   		$dbid = "root";
    		$dbpw = "hellmoney4$";
    		$dbname = "whatziroom";
    		$dbhost = "localhost";
   			$conn = mysqli_connect($dbhost, $dbid, $dbpw, $dbname);
				mysqli_set_charset($conn,'utf8');
   			return $conn;
   	}
}

?>
