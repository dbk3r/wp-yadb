


function wpyadb_save_topic(wpyadb_id, wpyadb_user, wpyadb_category, wpyadb_desc, wpyadb_content) {
	if(isEmpty(wpyadb_id)) {
		// create new DB-Entry
		var formData = {reply:"0",user:wpyadb_user,category:wpyadb_category,desc:wpyadb_desc,content:wpyadb_content};

		jQuery.ajax({
		    url : WPURLS.yadburl + "/functions/save_topic.php",
		    type: "POST",
		    data : formData,
		    success: function(data, textStatus, jqXHR)
		    {
		    	var dat = jQuery.parseJSON(data);
		    	var newTopic = jQuery('<tr class=wp_yadb_row onclick="loadTopicContent(this,\'' + dat.uuid + '\')"; onmouseover="rowOver(this,\'.5\',\'#dddddd\')"; onmouseout="rowOver(this,\'1\',\'transparent\')";>' +
		    					'<td style=text-align:left>' + wpyadb_desc + '<br><small>' + dat.date + '</small></td>' +
		    					'<td>' + wpyadb_category + '</td>' +
		    					'<td>' + wpyadb_user + '</td>' +
		    					'<td>0</td>' +
		    					'<td>0</td>' +
		    					'<td align=right><small></small></td>' +
		    					'</tr>');
		    	newTopic.hide();
		    	jQuery(".wp_yadb_row").first().before(newTopic);

		    	newTopic.slideDown(1000);

		        },
		        error: function (jqXHR, textStatus, errorThrown)
		        {

		        }
		});

	} else {
		// update DB-Entry
		alert("user:" + wpyadb_user + "\ncategory: " + wpyadb_category + "\ndesc: " + wpyadb_desc + "\ncontent :\n\n" + wpyadb_content);
	}

}

function loadTopicContent(me,uuid) {
	//yadb_load_contents(track_page,"topic",me,uuid);

	if(jQuery("." + uuid).length) {
		jQuery("." + uuid).slideUp('slow').remove();
	}
	else {

		var formData = {content:"topic",uuid:uuid};
		jQuery('.loader-image').show(); //show loading animation
		jQuery.ajax({
			type:'POST',
			url:WPURLS.yadburl + '/functions/dynload.php',
			data:formData,
			beforeSend:function(data){
					jQuery('.loader-image').show();
			},
			success:function(data){

								jQuery('.loader-image').hide();
								if(data.trim().length == 0){
										//notify user if nothing to load
										jQuery('.loader-image').hide();
										return;
								}
								var rowSet = jQuery(data);
								rowSet.hide();
								jQuery(me).last().after(rowSet);
								jQuery(rowSet).slideDown('slow');
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

	jQuery(window).scroll(function(){
		if (jQuery(window).scrollTop() + jQuery(window).height() >= jQuery(document).height()){
			track_page++;
			yadb_load_contents(track_page,"topics","","");

		}
	});



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
							jQuery('.loader-image').show();
          },
          success:function(data){

										loading = false; //set loading flag off once the content is loaded
										jQuery('.loader-image').hide();
				            if(data.trim().length == 0){
				                //notify user if nothing to load
												jQuery('.loader-image').hide();
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
	  tinymce.get('wpyadb_new_edit').setContent('');
		//jQuery('#fr_editor').froalaEditor();
		jQuery('#wpyadb_topic_desc').val('');
	  jQuery(".wpyadb_Editor").slideDown();
	  jQuery('#wpyadb_topic_desc').focus();
	});

	jQuery(".btn_wpyadb-save-topic").click(function() {
		var editor = tinyMCE.get('wpyadb_new_edit');
		if (editor) {
		    // Ok, the active tab is Visual
		    content = editor.getContent();
		} else {
		    // The active tab is HTML, so just query the textarea
		    content = jQuery('#wpyadb_new_edit').val();
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
