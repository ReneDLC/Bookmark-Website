<?php
  require_once('assets/PHPfunctions/bookmark_fns.php');
  session_start();
 
  //create short variable names
  @$del_me = $_POST['del_me'];
  @$valid_user = $_SESSION['valid_user'];
 
  do_html_header_universal('form');
  try{
	active_session();
  }
  catch (Exception $e)
  {
	  user_message($e->getMessage());
  }
  if (!filled_out($_POST))
  {
	  user_message('You have not chosen any bookmarks to delete.
         Please try again.');
    user_mess_footer(true); 
    exit;
  }
  else 
  {
    if (count($del_me) >0)
    {
      foreach($del_me as $url)
      {  
        if (delete_bm($valid_user, $url))
			user_message('Deleted '.htmlspecialchars($url).'.<br />');
        else
			user_message('Could not delete '.htmlspecialchars($url).'.<br />');
	  }  
	  
    }
    else
		user_message('No bookmarks selected for deletion');
 
  }
  // get the bookmarks this user has saved

  do_html_footer_universal(true, 'form');
?>