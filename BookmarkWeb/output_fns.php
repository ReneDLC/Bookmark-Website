<?php
	
	//pre-condition: Function will take a Title that should be presented to the user and will print it out in an HTML tag. 
	//post-condition: The Header of each WebPage will all have the same font type and size, and will have the same format for their Header. The do_html_heading() function will be called only if there was a title sent by the user, otherwise, only the empty header will be shown.
	function do_html_header($title)
	{
	  // print an HTML header
?>
  <html>
  <head>
    <title><?php echo $title;?></title>
    <style>
      body { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      li, td { font-family: Arial, Helvetica, sans-serif; font-size: 13px }
      hr { color: #3333cc; width=300; text-align=left}
      a { color: #000000 }
    </style>
  </head>
  <body>
  <img src="bookmark.gif" alt="PHPbookmark logo" border=0
       align=left valign=bottom height = 55 width = 57 />
  <h1>&nbsp;PHPbookmark</h1>
  <hr />
<?php
	if($title)
		do_html_heading($title);
	}
	
	//pre-condition: This will basically finish off any body and html tag
	//post-condition: Now, any other function can just have HTML tags and doesn't need to worry about closing the tags.
	function do_html_footer()
	{
	  // print an HTML footer
?>
  </body>
  </html>
<?php
	}

	//pre-condition: This is called within the do_html_header() function to print out the Title sent by the user
	//post-condition: The Title is echoed to the user in the proper Header format with the <h2> tag.
	function do_html_heading($heading)
	{
	  // print heading
?>
  <h2><?php echo $heading;?></h2>
<?php
	}

	//pre-condition: Takes in a PHP file and the name you want the user to call it.
	//post-condition: A link will be created that links to the PHP file that was sent, and it will be named the $name variable
	function do_html_URL($url, $name)
	{
	  // output URL as link and br
?>
  <br /><a href="<?php echo $url;?>"><?php echo $name;?></a><br />
<?php
	}

	//pre-condition: The info function will be print out the info of the WebPage in a list format
	//post-condition: The <u1> tag is an unordered list of <l1> list items
	function display_site_info()
	{
	  // display some marketing info
?>
  <ul>
  <li>Store your bookmarks online with us!</li>
  <li>See what other users use!</li>
  <li>Share your favorite links with others!</li>
  </ul>
<?php
	}

	//pre-condition: This will be the form with which the User can make an account, log in to their account, or reset their password
	//post-condition: First a <a> link tag will allow the user to register for an account. The name and password will be sent to the 'member.php' file when the Submit button is pressed. Finally, the form will also contain a link to the Forgot you password screen located in 'forgot_form.php'
	function display_login_form()
	{
?>
  <a href='register_form.php'>Not a member?</a>
  <form method='post' action='member.php'>
  <table bgcolor='#cccccc'>
   <tr>
     <td colspan=2>Members log in here:</td>
   <tr>
     <td>Username:</td>
     <td><input type='text' name='username'></td></tr>
   <tr>
     <td>Password:</td>
     <td><input type='password' name='passwd'></td></tr>
   <tr>
     <td colspan=2 align='center'>
     <input type='submit' value='Log in'></td></tr>
   <tr>
     <td colspan=2><a href='forgot_form.php'>Forgot your password?</a></td>
   </tr>
 </table></form>
<?php
	}

	//pre-condition: When the user wants to register to become a user, this function is called. 
	//post-condition: The form allows the user to type their Email, Username, Password, and then Confirm Password. Once the submit button is hit, the user is sent to 'register_new.php'.
	function display_registration_form()
	{
?>
 <form method='post' action='register_new.php'>
 <table bgcolor='#cccccc'>
   <tr>
     <td>Email address:</td>
     <td><input type='text' name='email' size=30 maxlength=100></td></tr>
   <tr>
     <td>Preferred username <br />(max 16 chars):</td>
     <td valign='top'><input type='text' name='username'
                     size=16 maxlength=16></td></tr>
   <tr>
     <td>Password <br />(between 6 and 16 chars):</td>
     <td valign='top'><input type='password' name='passwd'
                     size=16 maxlength=16></td></tr>
   <tr>
     <td>Confirm password:</td>
     <td><input type='password' name='passwd2' size=16 maxlength=16></td></tr>
   <tr>
     <td colspan=2 align='center'>
     <input type='submit' value='Register'></td></tr>
 </table></form>
<?php 
	}

	//pre-condition: All of the URL's that the User has updated to his/her account would be displayed when calling on this function. This function takes an array of the URL's that the user has chosen, which we get from the database. 
	//post-condition: If the URL's are going to be displayed, then the global variable will be set to true. The only options available here are viewing the bookmarks or deleting them by checking them off. The HTML form will toggle the colors of the boxes, and make the Bookmarks into links that the user can click. If the URL is checked, it may be deleted.
	function display_user_urls($url_array)
	{
	  // display the table of URLs

	  // set global variable, so we can test later if this is on the page
	  global $bm_table;
	  $bm_table = true;
?>
  <br />
  <form name='bm_table' action='delete_bms.php' method='post'>
  <table width=300 cellpadding=2 cellspacing=0>
<?php
  $color = "#cccccc";
  echo "<tr bgcolor='$color'><td><strong>Bookmark</strong></td>";
  echo "<td><strong>Delete?</strong></td></tr>";
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      // remember to call htmlspecialchars() when we are displaying user data
      echo "<tr bgcolor='$color'><td><a href=\"$url\">".htmlspecialchars($url)."</a></td>";
      echo "<td><input type='checkbox' name=\"del_me[]\"
             value=\"$url\"></td>";
      echo "</tr>"; 
    }
  }
  else
    echo "<tr><td>No bookmarks on record</td></tr>";
?>
  </table> 
  </form>
<?php
	}

	//pre-condition: At the bottom of the Users Home Page, there are links to different things the user can do when they log in.
	//post-condition: The Home, Add MB, Change Password, Recommend URLs to me and Logout buttons all take the user to PHP files. The Delete Button has two features, when the user is on the Delete Button screen, clicking this button will delete the Bookmark that is checked, otherwise it would be plain.
	function display_user_menu()
	{
	  // display the menu options on this page
?>
<hr />
<a href="member.php">Home</a> &nbsp;|&nbsp;
<a href="add_bm_form.php">Add BM</a> &nbsp;|&nbsp; 
<?php
  // only offer the delete option if bookmark table is on this page
  global $bm_table;
  if($bm_table==true)
    echo "<a href='#' onClick='bm_table.submit();'>Delete BM</a>&nbsp;|&nbsp;"; 
  else
    echo "<font color='#cccccc'>Delete BM</font>&nbsp;|&nbsp;"; 
?>
<a href="change_passwd_form.php">Change password</a>
<br />
<a href="recommend.php">Recommend URLs to me</a> &nbsp;|&nbsp;
<a href="logout.php">Logout</a> 
<hr />

<?php
	}

	//pre-condition: This function is used so that the user can add a bookmark to their list of bookmarks
	//post-condition: When the user places their bookmark, they are sent to the 'add_bms.php' file. There is a max length to their bookmark.
	function display_add_bm_form()
	{
	  // display the form for people to ener a new bookmark in
?>
<form name='bm_table' action='add_bms.php' method='post'>
	<table width=250 cellpadding=2 cellspacing=0 bgcolor='#cccccc'>
		<tr>
			<td>New BM:</td>
			<td>
				<input type='text' name='new_url'  value="http://"
                        size=30 maxlength=255>
			</td>
		</tr>
		<tr>
			<td colspan=2 align='center'>
				<input type='submit' value='Add bookmark'>
			</td>
		</tr>
	</table>
</form>
<?php
	}

	//pre-condition: When the user wants to change their password, they will be send to this screen.
	//post-condition: Once the user puts in their credentials, they are forwarded to the 'change_passwd.php' file.
	function display_password_form()
	{
	  // display html change password form
?>
   <br />
   <form action='change_passwd.php' method='post'>
   <table width=250 cellpadding=2 cellspacing=0 bgcolor='#cccccc'>
   <tr><td>Old password:</td>
       <td><input type='password' name='old_passwd' size=16 maxlength=16></td>
   </tr>
   <tr><td>New password:</td>
       <td><input type='password' name='new_passwd' size=16 maxlength=16></td>
   </tr>
   <tr><td>Repeat new password:</td>
       <td><input type='password' name='new_passwd2' size=16 maxlength=16></td>
   </tr>
   <tr><td colspan=2 align='center'><input type='submit' value='Change password'>
   </td></tr>
   </table>
   <br />
<?php
	};

	//pre-condition: When the goes to the Forgot Password link, they will need this function
	//post-condition: The script asks the user for their username, and if it is correct, the databse will give them a temporary password and it will be emailed to them.
	function display_forgot_form()
	{
	  // display HTML form to reset and email password
?>
   <br />
   <form action='forgot_passwd.php' method='post'>
   <table width=250 cellpadding=2 cellspacing=0 bgcolor='#cccccc'>
   <tr><td>Enter your username</td>
       <td><input type='text' name='username' size=16 maxlength=16></td>
   </tr>
   <tr><td colspan=2 align='center'><input type='submit' value='Change password'>
   </td></tr>
   </table>
   <br />
<?php
	};

	//pre-condition: This function takes in an array of bookmark recomendations similar to showing all of the user's bookmarks function
	//post-condition: If the array sent to the function is valid and set, the contents will be printed out in a table format. Each bookmark is also a link to the actual URL. The difference between this and the other bookmark function is that you can't delete the recommendations.
	function display_recommended_urls($url_array)
	{
	  // similar output to display_user_urls
	  // instead of displaying the users bookmarks, display recomendation
?>
  <br />
  <table width=300 cellpadding=2 cellspacing=0>
<?php
  $color = "#cccccc";
  echo "<tr bgcolor=$color><td><strong>Recommendations</strong></td></tr>";
  if (is_array($url_array) && count($url_array)>0)
  {
    foreach ($url_array as $url)
    {
      if ($color == "#cccccc")
        $color = "#ffffff";
      else
        $color = "#cccccc";
      echo "<tr bgcolor='$color'><td><a href=\"$url\">".htmlspecialchars($url)."</a></td></tr>";
    }
  }
  else
    echo "<tr><td>No recommendations for you today.</td></tr>";
?>
  </table>
<?php
};

?>
