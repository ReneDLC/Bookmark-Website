<?php
 require_once('assets/PHPfunctions/bookmark_fns.php');
 session_start();
 
  //create short variable name
  $new_url = $_POST['new_url'];
 
  do_html_header_universal('form');
  try
  {
   active_session();
    if (!filled_out($_POST))
    {
      throw new Exception('Form not completely filled out.');    
    }
    // check URL format
    if (strstr($new_url, 'http://')===false)
		$new_url = 'http://'.$new_url;

    // check URL is valid
    if (!(@fopen($new_url, 'r')))
      throw new Exception('Not a valid URL.');
    // try to add bm
    add_bm($new_url);
	user_message('Bookmark Added');

	do_html_URL('member.php','Back Home');
  }
  catch (Exception $e)
  {
	  user_message($e->getMessage());
  }
  do_html_footer_universal(true, 'form');
?>
