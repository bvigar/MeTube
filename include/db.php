<?php

class DB{
	/* Configuration for your database */
	const server = 'localhost';
	const username = 'username';
	const password = 'password';
	const database = 'MeTube';

	var $conn;
	var $result;

	public function doConnect(){
		$this->conn = new mysqli(self::server, self::username, self::password, self::database);
		if($this->conn->connect_errno){
			die("Database connection failed");
		}
	}

	public function getRowID() {
		$retVal = mysqli_insert_id($this->conn);
		return $retVal;
	}

	/* Runs a single query (not multi-query) on open DB connection */
	public function doQuery($sql){
		$this->result = $this->conn->query($sql);
		if($this->result === false){
			return false;
		} else{
			return true;
		}
	}

	/* Get number of rows returned from most recent query */
	public function getRowCount(){
		return $this->result->num_rows;
	}

	/* Get the next row in a result with multiple rows.
	   Returns a value that compares to false when none are left */
	public function getNextRow(){
		return $this->result->fetch_row();
	}

	/* Returns a human-readable string describing any error that
	   occured on the most recent action */
	public function getError(){
		return $this->conn->error;
	}

	/* Escapes a string for special characters to be used in a later
	   query. */
	public function escapeString($str){
		return $this->conn->real_escape_string($str);
	}

	/* Get the result of a query consisting of COUNT(*) */
	public function getCountResult(){
		$this->result->data_seek(0);
		$tmp = $this->result->fetch_row();
		return $tmp[0];
	}
}

/* Global DB object used throughout the application */
$db = new DB();
$db->doConnect();

?>
