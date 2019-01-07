<?php
// include function files for this application
require_once('assets/PHPfunctions/bookmark_fns.php');
session_start();
//inner_html_header();
do_html_header_universal();
?>		
		<section class="row">
			<div class="column container">
				<form name="bm_table" method="post" action="add_bms.php">
					<fieldset class="register-group">
<?php
					active_session();
?>
						<label>
							New BM:
							<input type="text" name="new_url" value="http://" size=30 maxlength=255></input>
						</label>
						<input class="btn btn-default" type="submit" value="Add bookmark"></input>
					</fieldset>
				</form>
			</div>
		</section>
<?php

do_html_footer_universal(true);
?>


