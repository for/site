<?php
	class Db {
		public static function open() {
			$servername = "localhost";
			$username = "root";
			$password = "";

			// Create connection
			$link = new mysqli($servername, $username, $password);

			// Check connection
			if ($link->connect_error) {
				die("Connection failed: " . $link->connect_error);
			} 
			
			return $link;
		}



		public static function close($link) {
			$link -> close();
		}
	}		
?>