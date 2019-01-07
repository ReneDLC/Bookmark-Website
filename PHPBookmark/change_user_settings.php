<?php
  require_once('assets/PHPfunctions/bookmark_fns.php');
  session_start();
	do_html_header_universal('form');
	
	$usernm = false;
	
	if(!(@$_POST['old_username']) && !(@$_POST['old_passwd']))
		change_image();
	
	if(@$_POST['old_username'])
	{
		@$old_input = $_POST['old_username'];
		@$new_input = $_POST['passwd'];
		@$new_input2 = $_POST['new_username'];
		$usernm = true;
	}
		
	else if(@$_POST['old_passwd']){
		@$old_input = $_POST['old_passwd'];
		@$new_input = $_POST['new_passwd'];
		@$new_input2 = $_POST['new_passwd2'];
	}
	
	
	try
	{
		active_session();
		if (!filled_out($_POST))
		  throw new Exception('You have not filled out the form completely. Please try again.');
		if (!$usernm && $new_input!=$new_input2)
		   throw new Exception('Inputs entered were not the same.  Not changed.');
		if (strlen($new_input)>16 || strlen($new_input)<6)
		   throw new Exception('New setting must be between 6 and 16 characters.  Try again.');
		// attempt update
		if($usernm){
			change_username($_SESSION['valid_user'], $new_input, $new_input2);
			user_message('Username Changed');
		}
		else {
			change_password($_SESSION['valid_user'], $old_input, $new_input);
			user_message('Password Added');
		}
	}
	catch (Exception $e)
	{
		user_message($e->getMessage());
	}
	
 do_html_footer_universal(true, 'form');
 
 
 function change_image(){
	 
	try {
		if(@$_FILES['userfile']['error'] > 0)
		{
			user_message('Problem: ');
				switch($_FILES['userfile']['error'])
				{
					case 1: throw new Exception('File exceeded upload_max_filesize');	break;
					case 2: throw new Exception('File exceeded max_file_size');
					break;
					case 3:	throw new Exception('File only partially uploaded');
					break;
					case 4:	throw new Exception('No file uploaded');
					break;
				}
		}
		
		if(@$_FILES['userfile']['type'] != 'image/jpeg')
		{
			throw new Exception('Problem: file is not an image');
		}
		
		unlink('assets\\images\\'.get_image($_SESSION['valid_user']));
		update_image_name($_SESSION['valid_user'].'.JPG');
		$_FILES['userfile']['name'] = get_image($_SESSION['valid_user']);
		$upfile = 'assets\\images\\'.$_FILES['userfile']['name'];
		
		if(is_uploaded_file($_FILES['userfile']['tmp_name']))
		{
			if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $upfile))
			{
				throw new Exception('Problem: Could not move file to destination directory');
			}
		}
		else
		{
			throw new Exception('Problem: Possible file upload attack. Filename: ');
		}
	}
	
	catch (Exception $e)
	{
		user_message($e->getMessage());
		do_html_footer_universal(true, 'form');	
		exit;
	}
	
	user_message('File uploaded successfully<br><br>');
	do_html_footer_universal(true, 'form');	
	exit;
}
 
 
?>
