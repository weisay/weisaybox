<h3>最新评论</h3>
<div class="r_comment">
<ul>
<?php
	global $wpdb , $pre_HTML , $post_HTML;
	$my_email = get_bloginfo ('admin_email');
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, SUBSTRING(comment_content,1,24) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE ( comment_type = '' OR comment_type = 'comment' ) AND comment_approved = '1' AND post_password = '' AND comment_author_email != '$my_email' ORDER BY comment_date_gmt DESC LIMIT 10";
	$comments = $wpdb->get_results($sql);
	$output = $pre_HTML;
	foreach ($comments as $comment) {$output .= "<li>".get_avatar(get_comment_author_email(), 48, '', get_comment_author())."<p class='comment-author'>".strip_tags($comment->comment_author)."</p>" . " <a href=\"" . get_comment_link($comment->comment_ID) . "\" title=\"查看 " .$comment->post_title . "\">" . strip_tags($comment->com_excerpt)."</a></li>\n";}
	$output .= $post_HTML;
	$output = convert_smilies($output);
	echo $output;
?>
</ul>
</div>