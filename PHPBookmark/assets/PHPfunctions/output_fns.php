<?php
	/*pre-condition: Function will take a Title that should be presented
	to the user and will print it out in an HTML tag. 
	post-condition: The Header of each WebPage will all have the same font
	type and size, and will have the same format for their Header. The
	do_html_heading() function will be called only if there was a title
	sent by the user, otherwise, only the empty header will be shown.*/
	
	function do_html_header_universal($section = 'default'){
?>
		<!DOCTYPE html>
		<html lang="en">
	
		<head>
			<meta charset="utf-8">
			<title>PHPbookmark</title>
			<link rel="stylesheet" href="assets/stylesheets/main.css">
			<link href='https://fonts.googleapis.com/css?family=Lato:400,300,100' rel='stylesheet' type='text/css'>
		</head>
	
		<body>
			<header class="primary-header container group">
			
				<h1 class="logo">
					<a href="member.php">PHPbookmark</a>
				</h1>
				
				<div class="tagline">
					<h3>Save Your Bookmarks</h3>
					<a class ="btn btn-alt" href="register_form.html">Sign Up</a>
				</div>
			</header>
<?php		
		
		if($section == 'form'){
?>
		<section class="row">
			<div class="column container form-style">
				<form>
<?php
		}
	}
	
	function do_html_footer_universal($inner = false, $section = false){
		
		if ($section == 'form')
		{
?>	
				</form>
			</div>
		</section>
		<?php 
			if($inner)
				display_user_menu(); 	
		}

		else if($inner){
			display_user_menu(); 
		}
?>	

		<footer class="primary-footer container group">
			
			<small>PHPbookmark. 1234 Street Dr. Houston, TX 77011 (123) 456-7891<br> &copy; 2016 PHPbookmark. All rights reserverd. Report a problem.</small>
				
		</footer>
	</body>
	
</html>
<?php
	}
	
	/*pre-condition: Takes in a PHP file and the name you want the user to 
	call it.
	//post-condition: A link will be created that links to the PHP file 
	that was sent, and it will be named the $name variable*/
	function do_html_URL($url, $name)
	{
	  // output URL as link and br
?>
			<br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
	}

	/*pre-condition: All of the URL's that the User has updated to his/her
	account would be displayed when calling on this function. This
	function takes an array of the URL's that the user has chosen, which
	we get from the database.
	*/
	/*post-condition: If the URL's are going to be displayed, then the
	global variable will be set to true. The only options available here
	are viewing the bookmarks or deleting them by checking them off. The
	HTML form will toggle the colors of the boxes, and make the Bookmarks
	into links that the user can click. If the URL is checked, it may be
	deleted.*/
	function display_user_urls($url_array)
	{
	  // display the table of URLs

	  // set global variable, so we can test later if this is on the page
	  global $bm_table;
	  $bm_table = true;
?>
					<form class="book-table" name='bm_table' action='delete_bms.php' method='post'>
						<table>
							<thead >
								<th scope="row">
								Bookmarks
								</th>
								<th scope="row">
								Delete?
								</th>
							</thead>
							<tbody>
<?php
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      // remember to call htmlspecialchars() when we are displaying user data
	  echo "<tr>
				<th scope='row'>
					<a href=\"$url\">".htmlspecialchars($url)."</a>
				</th>";
      echo "	<td>
					<input type='checkbox' name=\"del_me[]\" value=\"$url\">
				</td>";
				
      echo "</tr>"; 
    }
  }
  else
    echo "<tr><td>No bookmarks on record</td></tr>";
?>
							</tbody>
						</table> 
					</form>
<?php
	}

	/*pre-condition: At the bottom of the Users Home Page, there are links 
	to different things the user can do when they log in.
	//post-condition: The Home, Add MB, Change Password, Recommend URLs to 
	me and Logout buttons all take the user to PHP files. The Delete 
	Button has two features, when the user is on the Delete Button screen, 
	clicking this button will delete the Bookmark that is checked, 
	otherwise it would be plain.*/
	function display_user_menu()
	{
	  // display the menu options on this page
?>
<nav class="user-menu">
	<ul>
		<li><a href="member.php">Home</a><li><!--
		--><li><a href="add_bm_form.php">Add BM</a><li><!--
		--><li><a href="user_settings_form.php">Profile Settings</a><li><!--
		--><li><a href="recommend.php">Recommend URLs to me</a><li><!--
		--><li><a href="logout.php">Logout</a> 
	</ul>
</nav>

<?php
	}
	
	/*
		Function will receive a query pointer and print out a table. There are different cases, one where we are printing out a whole table and one where we print out a search, both of them have to consider the priviledge level. In both cases we need the query to get the col-names from the table, so table name should be passed to this function. 
	*/
	function table_format($table_name, $query_pointer){
		$conn = db_connect();
		$test2 = "select ";
		
		$query_pointer = $conn->query("select column_name
								from information_schema.columns
								where table_schema='market'
								and table_name='$table_name'");				
		for($count = 0; $rows = $query_pointer->fetch_row(); ++$count)
				{	
					if($count == 0)
						$test2 .= lcfirst($rows[0]);
					else
						$test2 .= ", ".lcfirst($rows[0]);
				}
		$test2 .= " from $table_name";
		$result = $conn->query($test2);
		
		try{
			if ($result->num_rows > 0) {
					$test_array = array();
					
			
				for($count = 0; $rows = $query_pointer->fetch_row(); ++$count)
				{	
					$final = lcfirst($rows[0]);
					$test_array[$count] = $final;
				}
				
				while($row = $result->fetch_assoc()){
					echo "<tr>";
					foreach($test_array as $testing){
							echo "<td>";
							$test2 = $row["$testing"];
							//EDITABLE PAGES
							if($edit){
								echo '<input type="hidden" name="old[]" value="'.$test2.'"></input>';
							
								//echo "<input type='text' name='new' value='$test2' onkeypress=\"this.style.width = ((this.value.length + 1) * 8) + \" px\";\" ></input>";
?>
								<input id="txt" type="text" style="width:100px" onkeypress="this.style.width = ((this.value.length + 1) * 8) + 'px';" value= 
<?php								
								echo "'$test2'></input>";

								//echo '<input id="txt" type="text" onkeypress="this.style.width = ((this.value.length + 1) * 8) + "px";">';
								//echo "<input type='text' name='new[]' value='$test2' style='width: 100px;'></input>";
							}
							//END EDITABLE
							else
								echo $test2;
							
							echo "</td>";
					}
					if($edit)
						echo "<td>
						<button class='btn' type='submit' name='edit' value='edit'>Edit</button>
						<button class='btn' type='submit' name='delete' value='delete'>Delete</button>
						</td>";
					echo "</tr>";
				}		
			}
		}
		catch(Exception $e)
		{
			echo "<b>Error thrown</b>";
		}
		
	}
	
	function user_message($mess)
	{
?>
	<h2 class="exception-message"><?php echo $mess; ?></h2>
<?php
	}
	
	
	
?>
