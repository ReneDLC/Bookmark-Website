<?php
  require_once("assets/PHPfunctions/bookmark_fns.php");
  do_html_header_universal('form');

  // creating short variable name
  @$username = $_POST['username'];

  try
  {
    $password = reset_password($username);
    notify_password($username, $password);
    echo 'Your new password has been emailed to you.<br />';
  }
  catch (Exception $e)
  {
	  user_message('Your password could not be reset - please try again later');
  }
  do_html_url('index.html', 'Log In');
  do_html_footer_universal(false, 'form');
?>
