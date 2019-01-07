<?php
  require_once('assets/PHPfunctions/bookmark_fns.php');
  session_start();
  do_html_header_universal();
  try
  { 
    active_session();
	$urls = recommend_urls($_SESSION['valid_user']);
?>
<section class="row">
	<div class="column container">
		<form class="book-table">
			<table>
				<thead>
					<th scope="row">
					Recommendations
					</th>
				</thead>
				<tbody>
<?php
 
  if (is_array($urls) && count($urls)>0)
  {
    foreach ($urls as $url)
    {
	   echo "<tr>
				<th scope='row'>
					<a href=\"$url\">".htmlspecialchars($url)."</a>
				</th>";				
      echo "</tr>";
    }
  }
  else
    echo "<tr><td>No recommendations for you today.</td></tr>";
?>
				</tbody>
			</table>
		</form> 
	</div>
</section>
<?php
  }
  catch (Exception $e)
  {
	  user_message($e->getMessage());
  }
  
	do_html_footer_universal(true);
?>

