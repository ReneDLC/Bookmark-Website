<?php
	//post-condition: when called on, this function will connect to the mySQL database. The user 'bm_user' can only select, insert, update, and delete entries from the database.
	function db_connect(){
		$result = new mysqli('localhost', 'bm_user', 'password', 'bookmarks');
		if(!$result)
			throw new Exception('Could not connect to database server');
		else
			return $result;
	}
?>