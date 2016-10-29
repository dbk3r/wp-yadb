


function ajaxExecute(formData) {
	return jQuery.ajax({
			url : WPURLS.yadburl + "/functions/dbexecute.php",
			type: "POST",
			data : formData,
			async: false,
			success: function(retData, textStatus, jqXHR)
			{

			},
			error: function (jqXHR, textStatus, errorThrown)
			{

			}
	}).responseText;
}

function close_viewer() {
	jQuery(".yadb-overlay").fadeOut();
	jQuery(".topic-viewer").empty();
}

function addSavedTopic(dat) {
	var newTopic = jQuery('<tr class=wp_yadb_row onclick="loadTopicContent(this,\'' + dat.uuid + '\')"; onmouseover="rowOver(this,\'.5\',\'#dddddd\')"; onmouseout="rowOver(this,\'1\',\'transparent\')";>' +
					'<td style=text-align:left>' + dat.desc + '<br><small>' + dat.date + '</small></td>' +
					'<td>' + dat.category + '</td>' +
					'<td>' + dat.user + '</td>' +
					'<td>0</td>' +
					'<td>0</td>' +
					'<td align=right><small></small></td>' +
					'</tr>');
	newTopic.hide();
	jQuery(".wp_yadb_row").first().before(newTopic);
	newTopic.fadeIn(500);
}

function wpyadb_save_topic(wpyadb_id, wpyadb_user, wpyadb_category, wpyadb_desc, wpyadb_content) {
	if(isEmpty(wpyadb_id)) {
		// create new DB-Entry
		var formData = {action:"newTopic",reply:"0",user:wpyadb_user,category:wpyadb_category,desc:wpyadb_desc,content:wpyadb_content};
		var retVal = ajaxExecute(formData);
		var data =  jQuery.parseJSON(retVal);
		addSavedTopic(data);
	} else {
		// update DB-Entry
		var formData = {action:"updateTopic",yid:wpyadb_id,category:wpyadb_category,content:wpyadb_content};
		var retVal = ajaxExecute(formData);
		var data =  jQuery.parseJSON(retVal);

		//alert(data.status);
	}

}

function pin_topic(uuid,yadb_id,value) {
	if(value == "0") {
		var formData = {action:"pinTopic",value:"0",yid:yadb_id};
		retval = ajaxExecute(formData);
		var data =  jQuery.parseJSON(retval);

		jQuery("#pin_button").attr("src",WPURLS.yadburl + "/img/pin-16.png");
		jQuery("#pin_button").attr("onclick","pin_topic('"+ uuid+"','"+ yadb_id+"','1')");
		jQuery("#pin_button").attr("title","pin topic");
	}
	else {
		var formData = {action:"pinTopic",value:"1",yid:yadb_id};
		retval = ajaxExecute(formData);
		var data =  jQuery.parseJSON(retval);

		jQuery("#pin_button").attr("src",WPURLS.yadburl + "/img/pinned-16.png");
		jQuery("#pin_button").attr("onclick","pin_topic('"+ uuid+"','"+ yadb_id+"','0')");
		jQuery("#pin_button").attr("title","unpin topic");
	}

}

function comment_topic(yadb_uuid, yadb_id, yadb_user) {
	var editor = tinymce.get('comment-topic-text');
	content = editor.getContent({format : 'raw'});
	var formData = {action:"commentTopic",reply:"1",user:yadb_user,uuid:yadb_uuid,content:content};
	var retVal = ajaxExecute(formData);
	var data =  jQuery.parseJSON(retVal);
	loadTopicContent('',data.uuid);

}


function save_topic(yadb_id) {
	var editor = tinymce.get('wp-yadb_edit-topic');
	content = editor.getContent({format : 'raw'});
	wpyadb_save_topic(yadb_id ,"",jQuery('#topic_category').text(), "", content);
	jQuery("#read_btn_set").show();
	jQuery("#edit_btn_set").hide();
	jQuery("#btn_reply").show();
	jQuery("#postTextContainer").html(content);

}

function cancel_edit_topic(yadb_id) {
	jQuery("#read_btn_set").show();
	jQuery("#edit_btn_set").hide();
	jQuery("#postTextContainer").html(jQuery("#wp-yadb_edit-topic").val());
}

function edit_topic(yadb_id) {
		jQuery("#postTextContainer").html("<textarea id='wp-yadb_edit-topic' name='wp-yadb_edit-topic'>" + jQuery("#postTextContainer").html() +"</textarea>");
		jQuery("#read_btn_set").hide();
		jQuery("#edit_btn_set").show();
		tinymce.remove("textarea#wp-yadb_edit-topic");
		init_editor('textarea#wp-yadb_edit-topic','200');

}

function init_editor(editor_selector,height) {
	tinymce.init({
		selector: editor_selector,
		height:	height,
		menubar:	false,
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools codesample'
		],
		toolbar: "insertfile undo redo | bold italic | forecolor backcolor | emoticons | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | link image media | codesample"
	});
}


function delete_topic(yadb_id,reply,uuid) {
	if (confirm('do you realy want to delete this topic?')) {

		var formData = {action:"deleteTopic",yadb_id:yadb_id,reply:reply,uuid:uuid};
		retval = ajaxExecute(formData);
		var data =  jQuery.parseJSON(retval);
		if(reply == '0')
		{
			jQuery(".yadb-overlay").fadeOut();
			jQuery(".topic-viewer").empty();
			jQuery("#"+yadb_id).remove();
		}
		else
		{
				jQuery("#"+yadb_id).remove();
		}
	}
}

function loadTopicContent(me,uuid) {
	jQuery(".wpyadb_new_Topic_Header").hide();
	jQuery(".wpyadb_Editor").slideUp();
	jQuery(".wpyadb_menu_save").hide();
	jQuery(".wpyadb_menu_new").show();

	close_viewer();
	if(jQuery("." + uuid).length) {
		jQuery("." + uuid).fadeOut('slow').remove();
	}
	else {

		var formData = {content:"topic",uuid:uuid};

		jQuery.ajax({
			type:'POST',
			url:WPURLS.yadburl + '/functions/dynload.php',
			data:formData,
			beforeSend:function(data){
					jQuery('.loader-image').hide().fadeIn();
			},
			success:function(data){

								jQuery('.loader-image').fadeOut();
								if(data.trim().length == 0){
										jQuery('.loader-image').fadeOut();

								}
								jQuery(".topic-viewer").append(data);
								if(me) { rowOver(me,'1','transparent'); }
								jQuery(".yadb-overlay").fadeIn();
								tinymce.remove("textarea#comment-topic-text");
								init_editor('#comment-topic-text', '100px');
			}
		});
	}
}

function rowOver(me,op,bcolor) {
	me.style.opacity=op;
	me.style.background=bcolor;
}

function mbtn_new() {
	jQuery(".wpyadb_menu").append('<div class="wpyadb_menu_new"><a style="cursor:pointer" class="btn_wpyadb-new-topic" >NEW TOPIC</a></div>');
}

function mbtn_save() {
	 jQuery(".wpyadb_menu").prepend('<div style="display:none" class="wpyadb_menu_save"><a style="cursor:pointer" class="btn_wpyadb-save-topic" >SAVE TOPIC</a></div>');
}

function isEmpty(str) {
    return (!str || 0 === str.length);
}


jQuery(document).ready(function() {
  mbtn_new();
	mbtn_save();

	var track_page = 1;
	var loading = false;
	yadb_load_contents(track_page,"topics","","");

	jQuery(document).on('keyup',function(evt) {
			if (evt.keyCode == 27) {
				 close_viewer();
			}
	});



	jQuery(window).scroll(function(){
		if (jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height()){
			track_page++;
			yadb_load_contents(track_page,"topics","","");

		}
	});
	init_editor('textarea#wp-yadb_new-topic','200');

	function yadb_load_contents(track_page,cont,topic,uuid){
    if(loading == false){
        loading = true;  //set loading flag on
				var formData = {page:track_page,content:cont,uuid:uuid};
        jQuery('.loader-image').show(); //show loading animation
				jQuery.ajax({
					type:'POST',
					url:WPURLS.yadburl + '/functions/dynload.php',
					data:formData,
					beforeSend:function(data){
							jQuery('.loader-image').hide().fadeIn();
          },
          success:function(data){

										loading = false; //set loading flag off once the content is loaded
										jQuery('.loader-image').fadeOut();
				            if(data.trim().length == 0){
				                //notify user if nothing to load
												jQuery('.loader-image').fadeOut();
				                return;
				            }
                    var rowSet = jQuery(data);
										rowSet.hide();
										if(cont == "topics") {
											jQuery(".wp_yadb_row").last().after(rowSet);
										}
										if(cont == "topic") {
											topic.last().after(rowSet);
										}
										rowSet.fadeIn(500);
          }
				});
    }
}

	jQuery(".btn_wpyadb-new-topic").click(function() {
		jQuery(".wpyadb_menu_save").show();
	  jQuery(".wpyadb_menu_new").hide();
	  jQuery(".wpyadb_new_Topic_Header").slideDown();
		jQuery('#wpyadb_topic_desc').val('');
		var editor = tinymce.get('wp-yadb_new-topic');
		editor.setContent("");

	  jQuery(".wpyadb_Editor").slideDown();
	  jQuery('#wpyadb_topic_desc').focus();
	});

	jQuery(".btn_wpyadb-save-topic").click(function() {
		var editor = tinymce.get('wp-yadb_new-topic');
		if (editor) {
		    // Ok, the active tab is Visual
		    content = editor.getContent();
		} else {
		    // The active tab is HTML, so just query the textarea
		    content = jQuery('#wp-yadb_new-topic').val();
		}

		if (jQuery('#wpyadb_topic_desc').val() && content)
		{
			jQuery(".wpyadb_menu_save").hide();
	    jQuery(".wpyadb_menu_new").show();

			wpyadb_save_topic(jQuery('#wpyadb_uuid').val() ,jQuery('#wpyadb_username').val(),jQuery('#wpyadb_category :selected').text(), jQuery('#wpyadb_topic_desc').val(), content);

			jQuery(".wpyadb_new_Topic_Header").hide();
	        jQuery(".wpyadb_Editor").slideUp();
      }
      else
      {
      	alert ("please fill out topic Description and the Topic-Content");
      }
    });
});
