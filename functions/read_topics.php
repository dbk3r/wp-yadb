
<?php
?>
<div class="yadb-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background-color:black;opacity:0.8;display:none">
</div>
<div class="topic-viewer" style="position:fixed;top:60px;left:50%;width:70%;height:70%;background-color:white;border:2px;text-align:leftr;margin-left: -35%;display:none;	overflow: auto;opacity:1;">
</div>
<div class="topic-edit" style="position:fixed;top:60px;left:50%;width:70%;height:70%;background-color:white;border:2px;text-align:leftr;margin-left: -35%;display:none;	overflow: auto;opacity:1;">
</div>
<div class="loader-image" style="position:fixed;top:50%;left:50%;width:100px;height:100px;background-color:white;border:2px;text-align:center;margin-top: -50px;margin-left: -100px;">
	<img  height="50" width="50" style="margin-top:25px" src="<?php echo plugins_url(); ?>/wp-yadb/img/ajax-loader.gif">
</div>

	<table class="wp_yadb_table">
<?php
function wpyadb_menu()
{
	global $current_user;


	#if($current_user->user_login)
	#{
?>
	<tr >
		<td colspan=6 style="text-align:right" class=wpyadb_menu></td>
	</tr>

<?php
	#}
}

function wpyadb_header()
{
?>


		<tr  >
			<th style=text-align:left>Topic</th><th>Category</th><th>Author</th><th>Replies</th><th>Views</th><th>Activity</th>
		</tr>
<script src="<?php echo plugins_url(); ?>/wp-yadb/js/jquery.nicescroll.min.js"></script>
<?php
}

function wpyadb_topics()
{
	print "<tr class=wp_yadb_row height=0><td colspan=6></td></tr>";

}


function wpyadb_footer()
{

?>
		<tr>
			<td colspan=6 style="text-align:center"> (c) by Denis Becker 2016</td>
		</tr>
	</table>
<?php

}

?>
