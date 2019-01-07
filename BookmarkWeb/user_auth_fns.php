<?php
	require_once('db_fns.php');
	
	//pre-condition: takes in the $username, $email, and $password that the user entered when registering for an account
	//post-condition: will throw an exception if database could not be reached, if the username entered is already in the database, and if the user was not allowed to enter their username into the database. Will return true if no exception is reached.
	function register($username, $email, $password){		
		$conn = db_connect();
		
		//search for username in database
		$result = $conn->query("select * from user where username='$username'");
		if(!$result)
			throw new Exception('Could not execute query');
		if($result->num_rows > 0)
			throw new Exception('That username is taken - go back and choose another');
		
		//insert username into database
		$result = $conn->query("insert into user values 
        ('$username', sha1('$password'), '$email')");
		
		if(!$result)
			throw new Exception('Could not register you in database - please try again later');
		
		return true;
	}
	
	//pre-condition: takes in username and password when the user is in the login page. 
	//post-condition: Throws an exception when the query could not go through and when the query goes through but the username was not found in the database. Returns true when the username was found.
	function login($username, $password){

		$conn = db_connect();
		
		//searches for username AND password in database
		$result = $conn->query("select * from user where
		username = '$username'
		and passwd = sha1('$password')");
		
		if(!$result)
			throw new Exception('Could not log you in.');
		if($result->num_rows > 0)
			return true;
		else
			throw new Exception('Could not log you in');
	}
	
	//pre-condition: When somebody is logged in, the cookie $_SESSION['valid_user'] will be filled, once the session ends, that variable will lose its value
	//post-condition: If the user is loged in, the user will be told his/her username. If not logged in, functions within output_fns.php will be called on to print the Login screen. 
	function check_valid_user(){
		if(isset($_SESSION['valid_user'])){
			echo 'Logged in as '.$_SESSION['valid_user'].'.';
			echo '<br />';
		}
		else{
			//they are not logged in
			echo 'You are not logged in.<br />';
			do_html_url('login.php', 'Login');
			do_html_footer();
			exit;
		}
	}
	
	//pre-condition: This function will take the username linked to the User, the old password that is still in the database, and a new password that will be swapped with the old password.
	//post-condition: The authenticity of the user will first be checked. Next, a query will be made where the 'user' table is updated and the new password will replace the old password. If querry doesn't work, an exception is thrown, else it will return true;
	function change_password($username, $old_password, $new_password){
		login($username, $old_password);
		$conn = db_connect();
		$result = $con->query("update user
         set passwd = 
		 sha1('$new_password')
         where username = 
		 '$username'");
		 
		 if(!$result)
			 throw new Exception('Password could not be changed');
		else
			return true; //changed successfully
	}
	
	//pre-condition: This function receives two parameters: a min word count and a max word count. This is used to create a random word as a temporary password when the user forgets their password and resets it.
	//post-condition: A word will be randomly searched through a directory and once the proper properties are applied, it will be returned to the user.
	function get_random_word($min_length, $max_length){

		$word = '';
		//Path to Random Words 
		$dictionary = '/usr/dict/words'; //the ispell dictionary
		$fp = @fopen($dictionary, 'r');
		
		//file not found
		if(!$fp)
			return false;
		
		$size = filesize($dictionary);
		
		//go to a random location in dictionary
		//srand() is calling on the seed for rand() to use
		srand((double) microtime() * 1000000);
		$rand_location = rand(0, $size);
		fseek($fp, $rand_location);
		
		//get the next whole word of the right length in the file
		while(strlen($word) < $min_length || strlen($word) > $max_length || strstr($word, "'")){
			if(feof($fp))
				fseek($fp, 0);
				//if at end, go to start
			$word = fgets($fp, 80);
			//skip first word as it could be partial
			$word = fgets($fp, 80);
			//the potential password
		};
		$word = trim($word); //trim the trailing \n from fgets
		return $word;
	}
	
	//pre-condition: All the user needs to reset their password is to send the reset_password function
	//post-condition: An exception will be thrown if: the random word received an error, if the query did not go through.The query is updating the user table and setting the password of that username to a random String. If it goes through, return true.
	function reset_password($username){

		$new_password = get_random_word(6, 13);
		
		if($new_password == false)
			throw new Exception('Could not generate new password');

		srand((double) microtime() * 1000000);
		$rand_number = rand(0, 999);
		$new_password .= $rand_number;
		
		//set user's password to this in database or return false
		$conn = db_connect();
		$result - $conn->query("update user set
		passwd = 
		sha1($new_password')
        where username =
		'$username'");
		if(!$result)
			throw new Exception('Could not change password'); //not changed
		else
			return $new_password; //changed successfully
	}
	
	//pre-condition: This function is called on when the user's password has been reset. It will receive a username and password, the password being the temporary one.
	//post-condition: An exception will be thrown if: the query to search for an email address within the username was not found or the query did not go through to the database, and if the mail function does not work. Returns true if the email was sent. 
	function notify_password($username, $password){
		//notify the user that their password has been changed
		$conn = db_connect();
		$result = $conn->query("select email from user
        where username=
		'$username'");
		
		if(!$result){
			throw new Exception('Could not find email address');
		}
		else if($result->num_rows == 0){
			throw new Exception('Could not find email address.');	//username not in db
		}
		else{
			$row = $result->fetch_object();
			$email = $row->email;
			$from = "From: support@phpbookmark \r\n";
			$mesg = "Your PHPBookmark password has been changed to $password \r\n"
            ."Please change it next time you log in. \r\n";
      
      if (mail($email, 'PHPBookmark login information', $mesg, $from))
        return true;      
      else
        throw new Exception('Could not send email.');
	}
}
?>