<div class="widget hotreviewer">
<h3>最活跃的读者</h3>
<ul>
<?php
global $wpdb , $output;
	$my_email = get_bloginfo ('admin_email');
	$query="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 60 DAY ) AND comment_author_email != '$my_email' AND post_password='' AND comment_author_url != '' AND comment_approved='1' AND ( comment_type='' OR comment_type='comment' )) AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT 15";
	$wall = $wpdb->get_results($query);
	foreach ($wall as $comment)
	{
		if( $comment->comment_author_url )
		$url = $comment->comment_author_url;
		else $url="#";
		$r="rel='external nofollow'";
		$tmp = "<li><a href='".$url."' ".$r." title='".$comment->comment_author." (留下".$comment->cnt."个脚印)'>".get_avatar( $comment->comment_author_email, 56, '', $comment->comment_author )."</a></li>\n";
		$output .= $tmp;
	}
	echo $output;
?>
</ul>
</div>