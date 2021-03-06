<?php

  require_once('../../../../wp-blog-header.php' );
  global $wpdb;
  global $current_user;

  $table_name = $wpdb->prefix . 'wpyadb';
  $yadb_url = plugins_url() . "/wp-yadb";

  if($_POST['content'] == "topic") {

    $sql = "SELECT * from " . $table_name . " where uuid='" . $_POST['uuid'] . "'";
    $yadb_url = plugins_url() . "/wp-yadb";
    $yadb_query =  $sql . " order by time ASC;";
    $topics = $wpdb->get_results($yadb_query);

    $output = '';
    if(!empty($topics)) {
      $output .= '<div class=' . $_POST['uuid'] . ' style="height:100%;"><table><tr style="background:#eeeeee;border-bottom-style:none;">';
      $output .= '<div onmouseover="rowOver(this,\'.5\',\'#dddddd\');" onmouseout="rowOver(this,\'1\',\'transparent\');" onClick="close_viewer();" style="background-color:white;border:solid 2px gray; width:100%; height:24px; cursor:pointer; display: flex; flex-flow: row nowrap; justify-content: space-between;">';
      $output .= '<div></div><div>CLOSE</div><div></div>';
      $output .= '</div>';
      $output .= '<table width=100% border=0 style="border-style:none;">';
      $cc = 0;
      foreach($topics as $topic) {
        $cc++;
        $user = get_userdatabylogin($topic->username);
        $cur_user = get_userdatabylogin($current_user->user_login);
        $post_text = base64_decode($topic->post_text);
        $post_text = str_replace("\\", "", $post_text);
        if($cc == 1)
        {
          $output .= '<tr><td colspan=3 class="yadb-noborder">';
          $output .= '<p style="border:1px solid lightgrey;" id="topic_category">Category: '. $topic->categorie .'</p><h2>' . base64_decode($topic->topic_text) .'</h2>';
          $output .= '</td></tr>';
        }
        $output .= '<tr id="header'. $topic->id .'">';
        $output .= '<td width=50 valign=top align=center class="yadb-noborder">' . get_avatar($user->ID,50,"",$topic->username). '</td>';
        $output .= '<th class="yadb-noborder-center">'. $topic->username . ' commented on ' . $topic->time .'</th>';
        $output .= '<th width=100 class="yadb-noborder-center">';

        $output .= '<div id="edit_btn_set-'.$topic->id .'" style="display:none">';
        $output .= '<a onclick="save_topic(\'' . $topic->id . '\');" style="cursor:pointer"><img id="save_button" title="save topic" src="'.$yadb_url.'/img/save.png"></a> ';
        $output .= '<a onclick="cancel_edit_topic(\'' . $topic->id . '\');" style="cursor:pointer"><img id="cancel_button" title="cancel" src="'.$yadb_url.'/img/cancel.png"></a>';
        $output .= '</div>';

        $output .= '<div id="read_btn_set-'.$topic->id .'">';
        if ($current_user->user_login == $topic->username || current_user_can('editor') || current_user_can('administrator')) {
          $output .= '<a onclick="edit_topic(\'' . $topic->id . '\');" style="cursor:pointer"><img id="edit_button" title="edit topic" src="'.$yadb_url.'/img/edit-16.png"></a> ';
        }
        if (current_user_can('administrator')) {
          $output .= '<a onclick="delete_topic(\'' . $topic->id . '\',\'' . $topic->reply . '\',\'' . $topic->uuid . '\');" style="cursor:pointer"><img id="delete_button" title="delete topic" src="'.$yadb_url.'/img/trash-16.png"></a> ';
          if($topic->reply == "0") # pin only main Topic
          {
            if($topic->pinned == "1") {
                $output .= '<a  style="cursor:pointer"><img onclick="pin_topic(\'' . $topic->uuid . '\',\'' . $topic->id . '\',\'0\');" id="pin_button" title="unppin topic to the top" src="'.$yadb_url.'/img/pinned-16.png"></a> ';
            } else {
                $output .= '<a  style="cursor:pointer"><img onclick="pin_topic(\'' . $topic->uuid . '\',\'' . $topic->id . '\',\'1\');" id="pin_button" title="pin topic to the top" src="'.$yadb_url.'/img/pin-16.png"></a> ';
            }
          }
        }
        $output .= '</div>';
        $output .= '</th>';
        $output .= '</tr>';
        $output .= '<tr id="content'. $topic->id . '" style="background:#ffffff">';
        $output .= '<td class="yadb-noborder"></td><td  colspan=2 style="text-align:left;">';
        $output .= '<div id="postTextContainer-'.  $topic->id .'">' . convert_smilies( $post_text ) . '</div>';
        $output .= '</td>';
        $output .= '</tr>';
        $output .= '<tr id="trenner'. $topic->id . '"><td class="yadb-noborder"></td><td class="yadb-trenner" colspan=2><img src="'.$yadb_url.'/img/arrow.png"></td></tr>';

      }
      $output .= '<tr id="tr-comment" style="border-top-style:none;"><td class="yadb-noborder">'. get_avatar($cur_user->ID,50,"",$current_user->user_login).'</td><td class="yadb-noborder" colspan=2 style="text-align:right;">';
      $output .= '<div><textarea id="comment-topic-text" name="comment-topic"></textarea></div>';
      $output .= '</td></tr>';

      $output .= '<tr id="tr-comment-btn" style="border-top-style:none;"><td class="yadb-noborder"></td><td class="yadb-noborder" colspan=2 style="text-align:right;">';
      $output .= '<div title="comment this Topic" id="btn_comment" class="yadb-comment-button" onclick="comment_topic(\'' . $topic->uuid . '\',\'' . $topic->id . '\',\'' . $current_user->user_login . '\');">COMMENT</div>';
      $output .= '</td></tr>';

      $output .= '</table></div>';
      $sql = "UPDATE " . $table_name . " SET views=views+1 where uuid='" . $_POST['uuid'] . "';";
      #require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      $wpdb->query($sql);
      #dbDelta( $sql );
    }
  }

  if($_POST['content'] == "topics") {

    $item_per_page = 15;
    $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    $position = (($page_number-1) * $item_per_page);

    $sql = "SELECT * from " . $table_name . " where reply='0' ";

    $yadb_query =  $sql . " order by pinned DESC , time DESC LIMIT " . $position . "," . $item_per_page . ";";
    $topics = $wpdb->get_results($yadb_query);

    $output = '';
    if(!empty($topics)) {
      foreach($topics as $topic) {
        $comments_sql = "SELECT COUNT(*) from ". $table_name . " where uuid='". $topic->uuid  ."'; ";
        $comments_result = $wpdb->get_var($comments_sql);
        $replies = $comments_result - 1;



        $user = get_userdatabylogin($topic->username);
        if ($user->ID) { $author = $topic->username; } else {$author = "Guest";}
        date_default_timezone_set("Europe/Berlin");
        $age = intval((strtotime(date('Y-m-d H:i:s')) - strtotime($topic->time)) / 60);
        if ($age < 1440) {
          if ($age < 60) { $age = intval($age) . " minutes";}
          else { $age = intval($age / 60) . " h";}

        }
        if ($age >= 1440) {
          if ($age < 1440) {$gk = "<";} else { $gk = ">"; }
          $age = $gk . " " .intval($age / 60 / 24) . " days";
        }
        if($topic->pinned == "1") {
          $pin_Image = '<div style="float: left;width:12px;height:100%;"><img src="'.$yadb_url.'/img/pinned-16.png"></div>';
          $text_size = "92%";
        }
        else {
          $pin_Image = "";
          $text_size = "100%";
        }
        $Activity = $age;
        $output .= '<tr id="' .$topic->id . '" class=wp_yadb_row onclick="loadTopicContent(this,\'' .$topic->uuid . '\')"; onmouseover="rowOver(this,\'.5\',\'#dddddd\')"; onmouseout="rowOver(this,\'1\',\'transparent\')";>';
        $output .= '<td style=text-align:left>'. $pin_Image . '<div style="width:' . $text_size . ';float:right">' .base64_decode($topic->topic_text) . '<br><small>' .$topic->time . '</small></div></td>';
        $output .= '<td>' . $topic->categorie . '</td>';
        $output .= '<td align=left>' . $author . '</td>';
        $output .= '<td>' . $replies . '</td>';
        $output .= '<td>' . $topic->views . '</td>';
        $output .= '<td align=right><small>' .$Activity . '</small></td>';
    		$output .= '</tr>';
      }
    }
  }
  print $output;
?>
