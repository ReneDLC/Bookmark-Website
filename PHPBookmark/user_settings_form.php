<?php
// include function files for this application
require_once('assets/PHPfunctions/bookmark_fns.php');
session_start();

@$setting = $_POST['setting-choice'];


do_html_header_universal();
?>
		<section class="row">
			<div class="column container">
				<section class="home-block col-2-4">
	<?php
				active_session();
				echo '<h2 class="user-valid" >Profile Settings</h2>';
	?>
					<form class="profile-settings" name="settings" method="post" action="user_settings_form.php">
						<fieldset>
								<a href="member.php">Home</a><!--
								--><a href="user_settings_form.php">Current Settings</a><!--
								--><input name="setting-choice" type="submit" value="Change Username"></input><!--
								--><input name="setting-choice" type="submit" value="Change Password"></input><!--
								--><input name="setting-choice" type="submit" value="Change Image"></input>
						</fieldset>
					</form>
				</section><!--
				--><section class="col-2-4">
<?php
					if($setting == 'Change Username')
						display_changing_username();
					else if($setting == 'Change Password')
						display_changing_password();
					else if($setting == 'Change Image')
						display_changing_image();
					else{	
?>
					<form method="post" action="change_passwd.php">
						<fieldset class="setting-form">
							<h2>Welcome to the Profile Setting Screen</h2>
							<h3>Your current settings are: </h3>
<?php
					display_current_settings();
					}
?>	
						</fieldset>
					</form>
				</section>
				
			</div>
		</section>
<?php

do_html_footer_universal(true);
?>


