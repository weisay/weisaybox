<div class="widget hotreviewer">
<h3>最活跃的读者</h3>
<ul>
<?php
global $wpdb;
$cache_key = 'sidebar_hotreviewer';
$counts = get_transient($cache_key);
if (false === $counts) {
	$my_email = get_bloginfo('admin_email');
	$query = $wpdb->prepare(
		"SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email
		FROM {$wpdb->prefix}comments
		WHERE comment_date > date_sub(NOW(), INTERVAL 12 MONTH)
			AND comment_approved = '1'
			AND comment_author_email != %s
			AND comment_author_url != ''
			AND (comment_type = '' OR comment_type = 'comment')
			AND user_id = '0'
		GROUP BY comment_author_email
		ORDER BY cnt DESC
		LIMIT 15",
		$my_email
	);
	$counts = $wpdb->get_results($query);
	if ($counts) {
		// 设置缓存1小时
		set_transient($cache_key, $counts, 2 * HOUR_IN_SECONDS);
	}
}
$mostactive = '';
if ($counts && is_array($counts)) {
	foreach ($counts as $count) {
		$author = esc_attr($count->comment_author);
		$url = esc_url($count->comment_author_url);
		$avatar = get_avatar($count->comment_author_email, 60, '', $author);
		$mostactive .= sprintf(
			'<li><a href="%s" title="%s (留下%d个脚印)" rel="external nofollow">%s</a></li>%s',
			$url,
			$author,
			(int)$count->cnt,
			$avatar,
			"\n"
		);
	}
	if (!empty($mostactive)) {
		echo $mostactive;
	}
}
?>
</ul>
</div>