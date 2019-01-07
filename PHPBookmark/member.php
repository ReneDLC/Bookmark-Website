<?php

// include function files for this application
require_once('assets/PHPfunctions/bookmark_fns.php');
session_start();

//create short variable names
@$username = $_POST['username'];
@$passwd = $_POST['passwd'];

if ($username && $passwd)
// they have just tried logging in
{
  try
  {
    login($username, $passwd);
    // if they are in the database register the user id
    $_SESSION['valid_user'] = $username;

  }
  catch(Exception $e)
  {
    // unsuccessful login
    do_html_header_universal('form');
	user_message('You could not be logged in. 
          You must be logged in to view this page');
    do_html_url('index.html', 'Login');
    do_html_footer_universal(false, 'form');
    exit;
  }      
}

if(!(isset($_SESSION['valid_user']))){
	do_html_header_universal('form');
	user_message('You are not logged in');
    do_html_url('index.html', 'Login');
    do_html_footer_universal(false, 'form');
	exit;
}
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
	
		<section class="row">
			<div class="column container">
				<section class="home-block col-2-4">
	<?php
				echo '<img class="home-img" src="assets/images/'.get_image($_SESSION['valid_user']).'" alt="temp-image">';

				echo '<h2 class="user-valid" >Logged in as '.$_SESSION['valid_user'].'</h2>';
	?>
					<nav class="home-menu">
						<ul>
							<li><a href="member.php">Home</a></li>
							<li><a href="add_bm_form.php">Add BM</a></li>
							<?php 
							echo "
							<li><a href='#' onClick='bm_table.submit();'>Delete BM</a></li>";
							?>
							<li><a href="user_settings_form.php">Profile Settings</a></li>
							<li><a href="recommend.php">Recommend URLs to me</a></li>
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</nav>
					
					<div class="member-info">
						<h3>PHPBookmark Simple Steps</h3>
						<ul>
							<li>Hit Add BM to add a Website</li>
							<li>To delete a bookmark, check the boxes next to the site, then press Delete BM</li>
							<li>Edit your Username, Password, or Image on Profile Setttings</li>
							<li>Check out what users like you have bookmarked with Recommend URLS To Me</li>
						</ul>
					
					</div>
				</section><!--
				--><section class="col-2-4">
<?php
				if ($url_array = get_user_urls($_SESSION['valid_user']))
					display_user_urls($url_array);
?>	
				</section>
			</div>
		</section>
<?php		

// give menu of options
 do_html_footer_universal();
?>


