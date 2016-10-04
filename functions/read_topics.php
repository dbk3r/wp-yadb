
<?php


function wpyadb_hello ()
{
	global $current_user;
	echo "Hello: "; echo "[".$current_user->user_login."]";
}

function wpyadb_menu()
{
	global $current_user;
	
?>
	<table>
<?php	
	if($current_user->user_login)
	{
?>	
	<tr>
		<td colspan=6 style="text-align:right" class=wpyadb_menu></td>
	</tr>
	
<?php
	}
}

function wpyadb_header()
{
?>
		<tr>
			<th style=text-align:left>Topic</th><th>Category</th><th>Users</th><th>Replies</th><th>Views</th><th>Activity</th>
		</tr>

<?php
}

function wpyadb_topics()
{
	global $wpdb;
	$topics = $wpdb->get_results("SELECT * FROM wp_wpyadb order by time DESC LIMIT 25;");
	foreach($topics as $topic)
	{
		$Activity = "edit|delete";
		$uuid = $topic->uuid;
		echo "<tr class=wp_yadb_row>";
		echo "<td style=text-align:left>$topic->topic_text<br><small>$topic->time</small></td><td>$topic->categorie</td><td align=left>$Users</td><td>$Replies</td><td>$topic->views</td><td align=right><small>$Activity</smalL></td>";
		echo "</tr>";
		
	}
	

}

function wpyadb_footer()
{

?>
		<tr>
			<td colspan=6> (c) by Denis Becker</td>
		</tr>
	</table>
<?php

}

?>
