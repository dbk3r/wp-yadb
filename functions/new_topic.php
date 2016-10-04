<?php

function wpyadb_new_topic()
{

	$settings = array('editor_class' => 'wpyadb_new_edit', 'media_buttons' => false , 'tinymce' => true, 'quicktags' => false );
	#$settings = array('editor_class' => 'wpyadb_new_edit', 'media_buttons' => false , 'tinymce' => true, 'paste_data_images' => true );
	global $current_user;
?>
	<tr>
		<td colspan="6">
			<div class="wpyadb_new_Topic_Header" style="display:none;width:100%">
				<table>
				<tr style="border:0px">
					<td>Topic</td>
					<td colspan="3" style="text-align:left">
						<input type="text" name="wpyadb_topic_desc" id="wpyadb_topic_desc" style="width:100%">
						<input type="hidden" name="wpyadb_username" id="wpyadb_username" value="<?php echo $current_user->user_login; ?>">
						<input type="hidden" name="wpyadb_uuid" id="wpyadb_uuid">
					</td>
					<td style="width:60px">Category</td>
					<td  style="text-align:left;width:100px">
						<?php
						 wp_dropdown_categories( array(
							'hide_empty'       => 0,
							'name'             => 'wpyadb_category',
							'orderby'          => 'name',
							'selected'         => $category->parent,
							'hierarchical'     => true
							) );
						?>
					</td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6">
			<div class="wpyadb_Editor" style="display:none">
				<div class="wrap">
					<?php  wp_editor($content, "wpyadb_new_edit",$settings); ?>
				</div>
			</div>
		</td>
	</tr>

<?php
}

?>
