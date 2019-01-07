<?php
	require_once('db_fns.php');

	/*pre-condition:get all the bookmarks that user has placed in the 
	database, receiving only the username
	//post-condition: Function will return false if the query could not be 
	accomplied. Otherwise an array of URL's only will be inserted into 
	'url_array'*/
	function get_user_urls($username)
	{
	  $conn = db_connect();
	  $result = $conn->query( "select bm_URL
							  from bookmark
							  where username = '$username'");
	  if (!$result)
		return false; 

	  //create an array of the URLs 
	  $url_array = array();
	  for ($count = 1; $row = $result->fetch_row(); ++$count) 
	  {
		$url_array[$count] = $row[0];
	  }  
	  return $url_array;
	}
	 
	//pre-condition: Function receives the URL that the user typed in, which should have been verified by this function call
	//post-condition: Function will search for the URL in the database. If not found, it will be inserted into the bookmark table where the username and URL are required. Exceptions are thrown when query is not accepted. 
	function add_bm($new_url)
	{
		user_message("Attempting to add ".htmlspecialchars($new_url));
	  $valid_user = $_SESSION['valid_user'];
	  
	  $conn = db_connect();

	  // check not a repeat bookmark
	  $result = $conn->query("select * from bookmark
							 where username='$valid_user' 
							 and bm_URL='$new_url'");
	  if ($result && ($result->num_rows>0))
		throw new Exception('Bookmark already exists.');

	  // insert the new bookmark
	  if (!$conn->query( "insert into bookmark values
							  ('$valid_user', '$new_url')"))
		throw new Exception('Bookmark could not be inserted.'); 

	  return true;
	} 

	//pre-condition: This function receives a username and the URL that was clicked by the user. This function is accessed when the delete_bms.php file is opened
	//post-condition: Exception is thrown when query could not go through, and the query is simply deleting a column of the Bookmark table.
	function delete_bm($user, $url)
	{
	  // delete one URL from the database
	  $conn = db_connect();
	  // delete the bookmark
	  if (!$conn->query( "delete from bookmark 
						   where username='$user' and bm_url='$url'"))
		throw new Exception('Bookmark could not be deleted');
	  return true;  
	}
	
	//pre-condition: Function is used to create an array of URL's that more than one user has bookmarked. This is done through a more complex query. 
	//post-condition: Exception is thrown when query doesn't go through, or when there is no recomendations to give the user. Otherwise, the array is created an array is returned.
	function recommend_urls($valid_user, $popularity = 1)
	{
	  $conn = db_connect();
	
	  $query = "select bm_URL
			from bookmark
			where username in
			 (select distinct(b2.username)
					  from bookmark b1, bookmark b2
			  where b1.username='$valid_user'
					  and b1.username != b2.username
					  and b1.bm_URL = b2.bm_URL)
			and bm_URL not in
					  (select bm_URL
					   from bookmark
					   where username='$valid_user')
				group by bm_url
				having count(bm_url)>$popularity";
	 
	  if (!($result = $conn->query($query)))
		 throw new Exception('Could not find any bookmarks to recommend.');
	  if ($result->num_rows==0)
		 throw new Exception('Could not find any bookmarks to recommend.');

	  $urls = array();
	  // build an array of the relevant urls
	  for ($count=0; $row = $result->fetch_object(); $count++)
	  {
		 $urls[$count] = $row->bm_URL; 
	  }
								  
	  return $urls; 
	}
?>
