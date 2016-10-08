
<?php
?>
	<table class="wp_yadb_table">
<?php
function wpyadb_menu()
{
	global $current_user;


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
			<th style=text-align:left>Topic</th><th>Category</th><th>Author</th><th>Replies</th><th>Views</th><th>Activity</th>
		</tr>

<?php
}

function wpyadb_topics()
{
	print "<tr class=wp_yadb_row><td colspan=6></td></tr>";

}

function wpyadb_loader()
{
?>

	<tr>
		<td colspan=6><img class="loader-image" src="<?php echo plugins_url(); ?>/wp-yadb/img/ajax-loader.gif"</td>
	</tr>

<?php
}

function wpyadb_footer()
{

?>
		<tr>
			<td colspan=6> (c) by Denis Becker 2016</td>
		</tr>
	</table>
<?php

}

?>
