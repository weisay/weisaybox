<?php
if (!function_exists('optionsframework_init')) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';
	$optionsfile = locate_template( 'options.php' );
	load_template( $optionsfile );
}

require get_template_directory() . '/includes/patch.php';
require get_template_directory() . '/includes/patch-emoji.php';
require get_template_directory() . '/includes/tags.php';
require get_template_directory() . '/includes/theme-updater.php';
if (function_exists('register_sidebar'))
{
	register_sidebar(array(
		'name'			=> '小工具1',
		'id' => 'sidebar-1',
		'before_widget'	=> '<div class="widget">',
		'after_widget' => '</div><div class="clear"></div>',
		'before_title'	=> '<h3>',
		'after_title'	=> '</h3>',
	));
}
{
	register_sidebar(array(
		'name'			=> '小工具2',
		'id' => 'sidebar-2',
		'before_widget'	=> '<div class="widget">',
		'after_widget' => '</div><div class="clear"></div>',
		'before_title'	=> '<h3>',
		'after_title'	=> '</h3>',
	));
}
{
	register_sidebar(array(
		'name'			=> '小工具3',
		'id' => 'sidebar-3',
		'before_widget'	=> '<div class="widget">',
		'after_widget' => '</div><div class="clear"></div>',
		'before_title'	=> '<h3>',
		'after_title'	=> '</h3>',
	));
}

if ( function_exists('register_nav_menus') ) {
	register_nav_menus(array(
		'topmenu' => 'PC顶部菜单',
		'headermenu' => 'PC导航菜单',
		'leftmenu' => '移动端左侧菜单',
	));
}

if ( ! function_exists( 'weisaybox_styles' ) ) {
	function weisaybox_styles() {
		$theme = wp_get_theme();
		$themeversion = $theme->get('Version');
		wp_enqueue_style( 'weisaybox-mmenu', get_template_directory_uri().'/mmenu.css','',$themeversion,'all' );
		wp_enqueue_style( 'weisaybox-style', get_stylesheet_uri(),'',$themeversion,'all' );
		wp_enqueue_style( 'weisaybox-dark', get_template_directory_uri().'/dark.css','',$themeversion,'all' );
	}
}
add_action( 'wp_enqueue_scripts', 'weisaybox_styles', '1' );

//独立页面增加摘要功能
add_action('init', 'page_excerpt');
function page_excerpt() {
	add_post_type_support('page', array('excerpt'));
}

//添加HTML编辑器自定义快捷按钮
function my_quicktags($mce_settings) {
?>
<?php if (weisay_option('wei_prismjs') == 'displays') : ?>
<script type="text/javascript">
	var aLanguage = ['html', 'css', 'javascript', 'php', 'java', 'c'];
	for( var i = 0, length = aLanguage.length; i < length; i++ ) {
		QTags.addButton(aLanguage[i], aLanguage[i], '<pre class="line-numbers"><code class="language-' + aLanguage[i] + '">\n', '\n</code></pre>');
	}
</script>
<?php endif; ?>	
<?php
}
add_action('after_wp_tiny_mce', 'my_quicktags');

//替换Gavatar头像地址
$gravatar_urls = array('www.gravatar.com', '0.gravatar.com', '1.gravatar.com', '2.gravatar.com', 'secure.gravatar.com', 'cn.gravatar.com');
$gravatar_mirrors = array(
	'weavatar' => 'weavatar.com',
	'cravatar' => 'cravatar.cn',
	'loli_net' => 'gravatar.loli.net',
	'sep_cc' => 'cdn.sep.cc',
	'official' => false
);
if (weisay_option('wei_gravatar') == '0') {
	$gravatar_mirror = 'official';
} elseif (weisay_option('wei_gravatar') == '2') {
	$gravatar_mirror = 'cravatar';
} elseif (weisay_option('wei_gravatar') == '3') {
	$gravatar_mirror = 'loli_net';
} elseif (weisay_option('wei_gravatar') == '4') {
	$gravatar_mirror = 'sep_cc';
} else {
	$gravatar_mirror = 'weavatar';
}
function custom_gravatar($avatar) {
	global $gravatar_urls, $gravatar_mirror, $gravatar_mirrors;
	if ($gravatar_mirror === 'official') {
		return $avatar;
	}
	if (isset($gravatar_mirrors[$gravatar_mirror]) && $gravatar_mirrors[$gravatar_mirror]) {
		return str_replace($gravatar_urls, $gravatar_mirrors[$gravatar_mirror], $avatar);
	}
	return $avatar;
}
add_filter('get_avatar', 'custom_gravatar');
add_filter('get_avatar_url', 'custom_gravatar');

//热评日志
function get_hot_reviews($posts_num = 10, $days = 365) {
	global $wpdb;
	$posts_num = absint($posts_num);
	$days = absint($days);
	$cache_key = "hot_reviews_{$days}_{$posts_num}";
	$output = get_transient($cache_key);
	if ($output === false) {
		$sql = $wpdb->prepare(
			"SELECT ID, post_title, comment_count
			FROM {$wpdb->posts}
			WHERE post_type = 'post'
			AND post_date >= DATE_SUB(NOW(), INTERVAL %d DAY)
			AND (post_status = 'publish' OR post_status = 'inherit')
			ORDER BY comment_count DESC
			LIMIT %d",
			$days,
			$posts_num
		);
		$posts = $wpdb->get_results($sql);
		$output = '';
		if (!empty($posts)) {
			foreach ($posts as $post) {
				$title_attr = esc_attr($post->post_title . " ({$post->comment_count}条评论)");
				$output .= sprintf(
					'<li><a href="%s" rel="bookmark" title="%s">%s</a></li>' . "\n",
					esc_url(get_permalink($post->ID)),
					$title_attr,
					esc_html($post->post_title)
				);
			}
		}
		set_transient($cache_key, $output, 2 * HOUR_IN_SECONDS);
	}
	return $output;
}

//热门日志
function get_timespan_most_viewed($mode = '', $limit = 10, $display = true) {
global $wpdb, $post, $days;
$days = weisay_option('wei_hotpostno');
	if (empty($days)){
		$days = 365;
	}
$limit_date = current_time('timestamp') - ($days*86400);
$limit_date = date("Y-m-d H:i:s",$limit_date);
$where = '';
$temp = '';
if(!empty($mode) && $mode != 'both') {
$where = "post_type = '$mode'";
} else {
$where = '1=1';
}
$most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
if($most_viewed) {
foreach ($most_viewed as $post) {
$post_title = get_the_title();
$post_views = intval($post->views);
$post_views = number_format($post_views);
$temp .= "<li><a href=\"".get_permalink()."\" title=\"".get_the_title()."\">$post_title</a></li>";
}
} else {
$temp = '<li>'.__('暂无热门日志', 'wp-postviews').'</li>'."\n";
}
if($display) {
echo $temp;
} else {
return $temp;
}
}

//分类热门日志
function get_timespan_most_viewed_category($type, $mode = '', $limit = 10, $display = true) {
	global $wpdb, $post, $id, $days;
	$days = weisay_option('wei_hotpostno');
	if (empty($days)){
		$days = 365;
	}
	$categories = null;
	if ($type == 'single') {
		$categories = get_the_category($id);
	} else {
		$categories = get_the_category();
	}
	$category_id = array();
	foreach ($categories as $category) {
		array_push($category_id, $category->term_id);
	}
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(is_array($category_id)) {
		$category_sql = "$wpdb->term_taxonomy.term_id IN (".join(',', $category_id).')';
	} else {
		$category_sql = "$wpdb->term_taxonomy.term_id = $category_id";
	}
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $wpdb->term_taxonomy.taxonomy = 'category' AND $category_sql AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER BY views DESC LIMIT $limit");
	if($most_viewed) {
	foreach ($most_viewed as $post) {
	$post_title = get_the_title();
	$post_views = intval($post->views);
	$post_views = number_format($post_views);
	$temp .= "<li><a href=\"".get_permalink()."\" title=\"".get_the_title()."\">$post_title</a></li>";
	}
	} else {
	$temp = '<li>'.__('暂无热门日志', 'wp-postviews').'</li>'."\n";
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

//分页
function paging_nav() {
	global $wp_query;
	if ($wp_query->max_num_pages <= 1) {
		return;
	}
	$big = 99999999; // 需要一个不太可能的整数
	$pagination_links = paginate_links(array(
		'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
		'format' => get_option('permalink_structure') ? 'page/%#%/' : '&paged=%#%',
		'current' => max(1, get_query_var('paged')),
		'total' => $wp_query->max_num_pages,
		'mid_size' => 2,
		'end_size' => 1
	));
	if ($pagination_links) {
		$pagination_links = preg_replace('#(/page/1)/(?=\?|&|["\']|$)#', '/', $pagination_links);
		$pagination_links = preg_replace('#(/page/1)(?=\?|&|["\']|$)#', '', $pagination_links);
	}
	echo '<div class="pagination">' . $pagination_links . '</div>';
}

//日志归档
	class article_archive
{
	function get_posts() 
	{
		global  $wpdb,$rawposts;
		if ( $posts = wp_cache_get( 'posts', 'iarticle-clean-archive' ) )
			return $posts;
		$query="SELECT DISTINCT ID,post_date,post_date_gmt,comment_count,post_password FROM $wpdb->posts WHERE post_type='post' AND post_status = 'publish'";
		$rawposts =$wpdb->get_results( $query, OBJECT );
		foreach( $rawposts as $key => $post ) {
			if (!is_array($posts)) {
				$posts = [];
				}
			$posts[ mysql2date( 'Y.m', $post->post_date ) ][] = $post;
			$rawposts[$key] = null; 
		}
		$rawposts = null;
		wp_cache_set( 'posts', $posts, 'iarticle-clean-archive' );
		return $posts;
	}
	function post_list( $atts = array() )
	{
		global $wp_locale;
		global $article_clean_archive_config;
		$atts = shortcode_atts(array(
			'usejs' => $article_clean_archive_config['usejs'],
			'monthorder' => $article_clean_archive_config['monthorder'],
			'postorder' => $article_clean_archive_config['postorder'],
			'postcount' => '1',
			'commentcount' => '1',
		), $atts);
		$atts=array_merge(array('usejs' => 1, 'monthorder' => 'new', 'postorder' => 'new'),$atts);
		$posts = $this->get_posts();
		( 'new' == $atts['monthorder'] ) ? krsort( $posts ) : ksort( $posts );
		foreach( $posts as $key => $month ) {
			$sorter = array();
			foreach ( $month as $post )
				$sorter[] = $post->post_date_gmt;
			$sortorder = ( 'new' == $atts['postorder'] ) ? SORT_DESC : SORT_ASC;
			array_multisort( $sorter, $sortorder, $month );
			$posts[$key] = $month;
			unset($month);
		}
		$html = '<div class="car-container';
		if ( 1 == $atts['usejs'] ) $html .= ' car-collapse';
		$html .= '">'. "\n";
		if ( 1 == $atts['usejs'] ) $html .= '<select id="archive-selector"></select><a href="#" class="car-toggler">展开所有月份'."</a>\n";
		$html .= '<ul class="car-list">' . "\n";
		$first_month = TRUE;
		foreach( $posts as $yearmonth => $posts ) {
			list( $year, $month ) = explode( '.', $yearmonth );
			$first_post = TRUE;
			foreach( $posts as $post ) {
				if ( TRUE == $first_post ) {
					$spchar = $first_month ? '<span class="car-toggle-icon car-minus">-</span>' : '<span class="car-toggle-icon car-plus">+</span>';
					$html .= '<li class="car-pubyear-'. $year .'"><span class="car-yearmonth" style="cursor:pointer;">'.$spchar.' ' . sprintf( __('%1$s %2$d'), $wp_locale->get_month($month), $year );
					if ( '0' != $atts['postcount'] ) 
					{
						$html .= '<span class="archive-count">(共' . count($posts) . '篇文章)</span>';
					}
					if ($first_month == FALSE) {
					$html .= "</span>\n<ul class='car-monthlisting' style='display:none;'>\n";
					} else {
					$html .= "</span>\n<ul class='car-monthlisting'>\n";
					}
					$first_post = FALSE;
					$first_month = FALSE;
				}
				$html .= '<li>' . mysql2date( 'd', $post->post_date ) . '日: <a target="_blank" href="' . get_permalink( $post->ID ) . '">' . get_the_title( $post->ID ) . '</a>';
				if ( !empty($post->post_password) )
				{
				$html .= "";
				}
				elseif ( '0' == $post->comment_count ){
					$html .= '<span class="archive-count">（暂无评论）</span>';
				}
				elseif ( '0' != $post->comment_count )
				{
				$html .= '<span class="archive-count">（' . $post->comment_count . ' 条评论）</span>';
				}
				$html .= "</li>\n";
			}
			$html .= "</ul>\n</li>\n";
		}
		$html .= "</ul>\n</div>\n";
		return $html;
	}
	function post_count() 
	{
		$num_posts = wp_count_posts( 'post' );
		return number_format_i18n( $num_posts->publish );
	}
}
if(!empty($post->post_content))
{
	$all_config = explode(';',$post->post_content);
	foreach($all_config as $item)
	{
		$temp = explode('=',$item);
		$article_clean_archive_config[trim($temp[0])] = htmlspecialchars(strip_tags(trim($temp[1])));
	}
}
else
{
	$article_clean_archive_config = array('usejs' => 1, 'monthorder' => 'new', 'postorder' => 'new');
}
$article_archive = new article_archive();

//支持外链缩略图
if ( function_exists('add_theme_support') )
	add_theme_support('post-thumbnails');
	function catch_first_image() {
		global $post, $posts;
		$first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if(isset($matches [1] [0])){
		$first_img = $matches [1] [0];
		}
	return $first_img;
	}

//评论
function weisay_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount,$wpdb, $post;
	if(!$commentcount) { //初始化楼层计数器
		$comments = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_post_ID = $post->ID AND ( comment_type = '' OR comment_type = 'comment' ) AND comment_approved = '1' AND !comment_parent");
		$cnt = count($comments);//获取主评论总数量
		$page = get_query_var('cpage');//获取当前评论列表页码
		$cpp=get_option('comments_per_page');//获取每页评论显示数量
		if (ceil($cnt / $cpp) == 1 || ($page > 1 && $page  == ceil($cnt / $cpp))) {
		$commentcount = intval($cnt) + 1;//如果评论只有1页或者是最后一页，初始值为主评论总数
		} else {
		$commentcount = intval($cpp) * intval($page) + 1;
		}
	}
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php $add_below = 'div-comment'; ?>
	<div class="comment-meta">
	<div class="comment-author vcard"><?php echo get_avatar( $comment->comment_author_email, 40, '', get_comment_author() ); ?></div>
		<?php if ( $comment->comment_approved == '1' ) : ?>
		<div class="comment-floor"><?php
			if(!$parent_id = $comment->comment_parent){
			switch ($commentcount){
			case 2 :echo "沙发";--$commentcount;break;
			case 3 :echo "板凳";--$commentcount;break;
			case 4 :echo "地板";--$commentcount;break;
			default:printf('%1$s楼', --$commentcount);
				}
			}
		?></div><?php endif; ?>
		<b class="fn comment-name"><?php comment_author_link() ?></b><?php printf(( $comment->user_id === $post->post_author ) ? '<span class="post-author">博主</span>' : ''); ?>
		<div class="comment-metadata">
		<?php comment_date('Y-m-d') ?> <?php comment_time() ?><?php edit_comment_link('编辑','&nbsp;&nbsp;•&nbsp;&nbsp;',''); ?>
		</div>
		<?php if( (weisay_option('wei_touching') == 'displays') && ( $comment->comment_karma == '1' )) : ?><div class="touching-comments-chosen"><?php
		$touchingUrl = weisay_option('wei_touchingurl');
		if ($touchingUrl) {
			echo '<a href="' . $touchingUrl . '" target="_blank"><span>入选走心评论</span></a>';
		} else {
			echo '<span>入选走心评论</span>';
		}
		?></div><?php endif; ?>
	</div>
	<div class="comment-content">
	<?php if ( $comment->comment_approved == '0' ) : ?>
	<p class="comment-approved" >您的评论正在等待审核中...</p>
	<?php endif; ?>
	<?php comment_text(); ?>
	</div>
	<div class="comment-footer">
		<span class="comment-reply">
		<?php 
		$replyButton = get_comment_reply_link(array_merge( $args, array('reply_text' => '回复', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])));
		$replyButton = str_replace( 'data-belowelement', 'onclick="return addComment.moveForm( \'div-comment-'.get_comment_ID().'\', \''.get_comment_ID().'\', \'respond\', \''.get_the_ID().'\', false, this.getAttribute(\'data-replyto\') )" data-belowelement', $replyButton);
		echo $replyButton;
		?>
		</span>
	<?php if (weisay_option('wei_touching') == 'displays' && current_user_can('manage_options')) : ?>
	<span class="touching-comments-button"><a class="karma-link" data-karma="<?php echo $comment->comment_karma; ?>" href="<?php echo wp_nonce_url( site_url('/comment-karma'), 'KARMA_NONCE' ); ?>" onclick="return post_karma(<?php comment_ID(); ?>, this.href, this)">
		<?php if ($comment->comment_karma == 0) {
		echo '<span title="加入走心"><svg class="hearticon" viewBox="0 0 1024 1024"><path d="M752 144c55.6 0 107.8 21.6 147.1 60.9S960 296.4 960 352c0 32.7-6.1 66.4-18.1 100.2-11.4 32-28.3 64.9-50.3 97.8-38.7 57.8-93.4 116.2-162.5 173.6C643 795.1 555.6 847.2 512 871.5c-43.2-24-129.4-75.1-215.2-146-69.6-57.5-124.6-116-163.7-174-22.2-33.1-39.3-66.1-50.8-98.4C70.2 418.9 64 385 64 352c0-55.6 21.6-107.8 60.9-147.1S216.4 144 272 144c76.9 0 147.2 42.2 183.6 110.1l28.2 52.7c12.1 22.5 44.4 22.5 56.4 0l28.2-52.7C604.8 186.2 675.1 144 752 144z m0-64c-101.3 0-189.7 55.4-236.5 137.6-1.5 2.7-5.4 2.7-6.9 0C461.7 135.4 373.3 80 272 80 121.8 80 0 201.8 0 352c0 338 512 592 512 592s512-255 512-592c0-150.2-121.8-272-272-272z" fill="#d81e06"></path></svg></span>';
		} else {
		echo '<span title="取消走心"><svg class="hearticon" viewBox="0 0 1024 1024"><path d="M1024 352c0 337-512 592-512 592S0 690 0 352C0 201.8 121.8 80 272 80c101.3 0 189.7 55.4 236.5 137.6 1.5 2.7 5.4 2.7 6.9 0C562.3 135.4 650.7 80 752 80c150.2 0 272 121.8 272 272z" fill="#d81e06"></path></svg></span>';
		}
	?></a></span>
	<?php endif; ?>
		</div>
	<div class="clear"></div>
</div>
<?php
}
function weisay_end_comment() {	echo '</li>'; }

//走心评论独立页面使用
function weisay_touching_comments_list($comment) {
	$cpage = get_page_of_comment( $comment->comment_ID, $args = array() );
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
<?php $add_below = 'div-comment'; ?>
<div class="comment-info">
<div class="comment-author">
<p class="fn comment-name"><?php comment_author_link(); ?></p>
<p class="comment-datetime"><?php comment_date('Y-m-d'); ?></p>
</div>
<div class="comment-avatar vcard"><?php echo get_avatar( $comment->comment_author_email, 48, '', get_comment_author() ); ?></div>
</div>
<div class="comment-content"><?php comment_text() ?></div>
<div class="comment-from">评论于<span class="bullet">•</span><a href="<?php echo get_comment_link($comment->comment_ID, $cpage); ?>" target="_blank"><?php echo get_the_title($comment->comment_post_ID); ?></a></div>
</div><div class="clear"></div>
<?php
}
function weisay_touching_comments_end_list() { echo '</li>'; }

/**
 * 处理走心评论
 * POST /comment-karma
 * 提交三个参数
 *  comment_karma: 0 或者 1
 *  comment_id: 评论ID
 *  _wpnonce: 避免意外提交
 */
function weisay_touching_comments_karma_request() {
	// Check if we're on the correct url
	global $wp;
	$current_slug = add_query_arg( array(), $wp->request );
	if($current_slug !== 'comment-karma') {
		return false;
	}

	global $wp_query;
	if ($wp_query->is_404) {
		$wp_query->is_404 = false;
	}

	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json; charset=utf-8');

	$result = array(
		'code'=> 403,
		'message'=> 'Login required.'
	);

	if (!is_user_logged_in() || !current_user_can('manage_options')) {
		// 未认证的用户拒绝继续执行请求
		header("HTTP/1.1 403 Forbidden");
		die(json_encode($result));
	}

	if (empty($_SERVER['REQUEST_METHOD']) ||
		strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST' ||
		empty($_SERVER['HTTP_X_REQUESTED_WITH']) ||
		strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
		$result['message'] = 'Request method not allowed';
		header("HTTP/1.1 403 Forbidden");
		die( json_encode($result) );
	}

	// Check if it's a valid request.
	$nonce = filter_input(INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING);
	if ( $nonce===false || ! wp_verify_nonce( $nonce,  'KARMA_NONCE')) {
		$result['message'] = 'Security Check';
		header("HTTP/1.1 403 Forbidden");
		die( json_encode($result) );
	}

	if (empty($_POST['comment_id'])) {
		$result['code'] = 501;
		$result['message'] = 'Incorrect parameter';
		header("HTTP/1.1 500 Internal Server Error");
		die( json_encode($result) );
	}

	// Do your stuff here
	$comment_karma = empty( $_POST['comment_karma'] ) ? '0' : filter_input(INPUT_POST, 'comment_karma', FILTER_SANITIZE_NUMBER_INT);
	$comment_id = filter_input(INPUT_POST, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
	if ($comment_karma === false ||
		$comment_id === false ||
		!is_numeric($comment_karma) ||
		!is_numeric($comment_id)) {
		$result['code'] = 501;
		$result['message'] = 'Incorrect parameter';
		header("HTTP/1.1 500 Internal Server Error");
		die( json_encode($result) );
	}

	// 更新数据库
	$comment_data = array();
	$comment_data['comment_ID'] = intval($comment_id);
	$comment_data['comment_karma'] = intval($comment_karma);
	
	if (wp_update_comment( $comment_data )) {
		$result['code'] = 200;
		$result['message'] = 'ok';
		header("HTTP/1.1 200 OK");
	} else {
		$result['code'] = 502;
		$result['message'] = 'comment update failed';
		header("HTTP/1.1 500 Internal Server Error");
	}

	exit(json_encode($result));
}

add_action( 'template_redirect', 'weisay_touching_comments_karma_request', 0);

//评论邮件通知
function comment_mail_notify($comment_id) {
	$admin_email = get_bloginfo ('admin_email'); // $admin_email 可改為你指定的 e-mail.
	$comment = get_comment($comment_id);
	$comment_author_email = trim($comment->comment_author_email);
	$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
	$to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
	$spam_confirmed = $comment->comment_approved;
	if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $admin_email) && ($comment_author_email == $admin_email)) {
		$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 發出點, no-reply 可改為可用的 e-mail.
		$subject = '您在 [' . get_option("blogname") . '] 的评论有新的回复';
		$message = '
		<div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
		<p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
		<p>您曾在 [' . get_option("blogname") . '] 的文章 《' . get_the_title($comment->comment_post_ID) . '》 上发表评论：<br />'
		. nl2br(get_comment($parent_id)->comment_content) . '</p>
		<p>' . trim($comment->comment_author) . ' 给您的回复如下：<br />'
		. nl2br($comment->comment_content) . '<br /></p>
		<p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回复的完整内容</a></p>
		<p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
		<p style="color:#666;">(此邮件由系统自动发送，请勿回复！)</p>
		</div>';
		$message = convert_smilies($message);
		$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
		$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
		wp_mail( $to, $subject, $message, $headers );
		//echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
	}
}
add_action('comment_post', 'comment_mail_notify');

//评论翻页Ajax
function AjaxCommentsPage(){
	if( isset($_POST['action'])&& $_POST['action'] == 'compageajax'){
		$postid = $_POST['postid'];
		$pageid = $_POST['pageid'];/*$args数组将请求的评论页页码做为参数传入评论分页函数*/
		$post = new stdClass();
		$post->ID = $postid;
		$args = array(
			'current' => $pageid,
			'echo' => true
		);
		$order = 'DESC'; 
		/*处理为倒序输出*/
		if( 'asc' != get_option('comment_order') ){
				$order = 'ASC'; 
		}
	global $wp_query, $wpdb, $id, $comment, $user_login, $user_ID, $user_identity;
	/**
	 * Comment author information fetched from the comment cookies.
	 *
	 * @uses wp_get_current_commenter()
	 */
	$commenter = wp_get_current_commenter();
	/**
	 * The name of the current comment author escaped for use in attributes.
	 */
	$comment_author = $commenter['comment_author']; // Escaped by sanitize_comment_cookies()
	/**
	 * The email address of the current comment author escaped for use in attributes.
	 */
	$comment_author_email = $commenter['comment_author_email'];  // Escaped by sanitize_comment_cookies()
	/**
	 * The url of the current comment author escaped for use in attributes.
	 */
	// $comment_author_url = esc_url($commenter['comment_author_url']);
	/** @todo Use API instead of SELECTs. */
	if ( $user_ID) {
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND (comment_approved = '1' OR ( user_id = %d AND comment_approved = '0' ) )  ORDER BY comment_date_gmt $order", $post->ID, $user_ID));
	} else if ( empty($comment_author) ) {
		$comments = get_comments( array('post_id' => $post->ID, 'status' => 'approve', 'order' => $order ) );
	} else {
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND ( comment_approved = '1' OR ( comment_author = %s AND comment_author_email = %s AND comment_approved = '0' ) ) ORDER BY comment_date_gmt $order", $post->ID, wp_specialchars_decode($comment_author,ENT_QUOTES), $comment_author_email ));
	}
	// keep $comments for legacy's sake
	$wp_query->comments = apply_filters( 'comments_array', $comments, $post->ID );
	$comments = &$wp_query->comments;
	$wp_query->comment_count = count($wp_query->comments);
	update_comment_cache($wp_query->comments);

	/*下面的输入写入评论页的#ajaxcomment id所在的层*/
	echo "<ol class=\"comment-list\">";
	echo wp_list_comments('callback=weisay_comment&type=comment&end-callback=weisay_end_comment&max_depth=23&page=' . $pageid, $comments);
	//echo wp_list_comments( array( 'callback' => 'weisay_comment' ) );
	echo "</ol><div class=\"navigation\"><div class=\"pagination\" id=\"commentpager\">";
	$comment_pages = paginate_comments_links($args);
	echo $comment_pages."</div></div>";
	die();
	}
}
add_action('template_redirect', 'AjaxCommentsPage');
//全部设置结束
?>