<?php
if (!function_exists('optionsframework_init')) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
	require_once dirname( __FILE__ ) . '/inc/options-framework.php';
	$optionsfile = locate_template( 'options.php' );
	load_template( $optionsfile );
}

include("includes/patch.php");
include("includes/patch_emoji.php");
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
		wp_enqueue_style( 'weisaybox-mmenu', get_template_directory_uri().'/mmenu.css','','5.0.7','all' );
		wp_enqueue_style( 'weisaybox-style', get_stylesheet_uri(),'','5.0.7','all' );
		wp_enqueue_style( 'weisaybox-dark', get_template_directory_uri().'/dark.css','','5.0.7','all' );
	}
}
add_action( 'wp_enqueue_scripts', 'weisaybox_styles', '1' );

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
function cravatar($avatar)
{
	global $gravatar_urls;
	return str_replace($gravatar_urls, 'cravatar.cn', $avatar);
}
function weavatar_avatar($avatar)
{
	global $gravatar_urls;
	return str_replace($gravatar_urls, 'weavatar.com', $avatar);
}
function loli_avatar($avatar)
{
	global $gravatar_urls;
	return str_replace($gravatar_urls, 'gravatar.loli.net', $avatar);
}
function sep_cc_avatar($avatar)
{
	global $gravatar_urls;
	return str_replace($gravatar_urls, 'cdn.sep.cc', $avatar);
}
if (weisay_option('wei_gravatar') == 'two') {
	add_filter('get_avatar', 'weavatar_avatar');
	add_filter('get_avatar_url', 'weavatar_avatar');
} elseif (weisay_option('wei_gravatar') == 'three') {
	add_filter('get_avatar', 'loli_avatar');
	add_filter('get_avatar_url', 'loli_avatar');
} elseif (weisay_option('wei_gravatar') == 'four') {
	add_filter('get_avatar', 'sep_cc_avatar');
	add_filter('get_avatar_url', 'sep_cc_avatar');
} else {
	add_filter('get_avatar', 'cravatar');
	add_filter('get_avatar_url', 'cravatar');
}

// 获得热评文章
function simple_get_most_viewed($posts_num=10, $days=700){
	global $wpdb;
	$sql = "SELECT ID , post_title , comment_count
			FROM $wpdb->posts
			WHERE post_type = 'post' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days
			AND ($wpdb->posts.`post_status` = 'publish' OR $wpdb->posts.`post_status` = 'inherit')
			ORDER BY comment_count DESC LIMIT 0 , $posts_num ";
	$posts = $wpdb->get_results($sql);
	$output = "";
	foreach ($posts as $post){
		$output .= "\n<li><a href= \"".get_permalink($post->ID)."\" rel=\"bookmark\" title=\"".$post->post_title." (".$post->comment_count."条评论)\" >". $post->post_title."</a></li>";
	}
	echo $output;
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
function paging_nav(){
	global $wp_query;
	$big = 999999999; // 需要一个不太可能的整数
	$pagination_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	) );
	echo '<div class="pagination">';
	echo $pagination_links;
	echo '</div>';
}

//日志归档
	class hacklog_archives
{
	function GetPosts() 
	{
		global  $wpdb,$rawposts;
		if ( $posts = wp_cache_get( 'posts', 'ihacklog-clean-archives' ) )
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
		wp_cache_set( 'posts', $posts, 'ihacklog-clean-archives' );
		return $posts;
	}
	function PostList( $atts = array() ) 
	{
		global $wp_locale;
		global $hacklog_clean_archives_config;
		$atts = shortcode_atts(array(
			'usejs'        => $hacklog_clean_archives_config['usejs'],
			'monthorder'   => $hacklog_clean_archives_config['monthorder'],
			'postorder'    => $hacklog_clean_archives_config['postorder'],
			'postcount'    => '1',
			'commentcount' => '1',
		), $atts);
		$atts=array_merge(array('usejs' => 1, 'monthorder' => 'new', 'postorder' => 'new'),$atts);
		$posts = $this->GetPosts();
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
		$firstmonth = TRUE;
		foreach( $posts as $yearmonth => $posts ) {
			list( $year, $month ) = explode( '.', $yearmonth );
			$firstpost = TRUE;
			foreach( $posts as $post ) {
				if ( TRUE == $firstpost ) {
					$spchar = $firstmonth ? '<span class="car-toggle-icon car-minus">-</span>' : '<span class="car-toggle-icon car-plus">+</span>';
					$html .= '<li class="car-pubyear-'. $year .'"><span class="car-yearmonth" style="cursor:pointer;">'.$spchar.' ' . sprintf( __('%1$s %2$d'), $wp_locale->get_month($month), $year );
					if ( '0' != $atts['postcount'] ) 
					{
						$html .= '<span class="archive-count">(共' . count($posts) . '篇文章)</span>';
					}
					if ($firstmonth == FALSE) {
					$html .= "</span>\n<ul class='car-monthlisting' style='display:none;'>\n";
					} else {
					$html .= "</span>\n<ul class='car-monthlisting'>\n";
					}
					$firstpost = FALSE;
					$firstmonth = FALSE;
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
	function PostCount() 
	{
		$num_posts = wp_count_posts( 'post' );
		return number_format_i18n( $num_posts->publish );
	}
}
if(!empty($post->post_content))
{
	$all_config=explode(';',$post->post_content);
	foreach($all_config as $item)
	{
		$temp=explode('=',$item);
		$hacklog_clean_archives_config[trim($temp[0])]=htmlspecialchars(strip_tags(trim($temp[1])));
	}
}
else
{
	$hacklog_clean_archives_config=array('usejs' => 1, 'monthorder' => 'new', 'postorder' => 'new');
}
$hacklog_archives=new hacklog_archives();

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
	if(empty($first_img)){
		$random = mt_rand(1, 30);
		echo get_bloginfo ( 'stylesheet_directory' );
		echo '/images/random/'.$random.'.jpg';
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
	<div class="comment-author vcard"><?php echo get_avatar( $comment, 40, '', get_comment_author() ); ?></div>
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
		<?php if( (weisay_option('wei_touching') == 'displays') && ( $comment->comment_karma == '1' )) : ?><div class="touching-comments-chosen"><a href="<?php echo weisay_option('wei_touchingurl'); ?>" target="_blank"><span>入选走心评论</span></a></div><?php endif; ?>
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
	<?php if ( (weisay_option('wei_touching') == 'displays') && current_user_can('level_10') ) {//走心评论按钮
	?>
	<span class="touching-comments-button"><a class="karma-link" data-karma="<?php echo $comment->comment_karma; ?>" href="<?php echo wp_nonce_url( site_url('/comment-karma'), 'KARMA_NONCE' ); ?>" onclick="return post_karma(<?php comment_ID(); ?>, this.href, this)">
		<?php if ($comment->comment_karma == 0) {
		echo '<span title="加入走心"><svg t="1691142362631" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3461" ><path d="M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032M902.11434027 389.56455147c0 57.54855787-41.73561173 143.42877973-91.125008 187.5253632-1.35349333 1.2301504-2.58255147 2.58364373-3.81161067 4.06593706-73.42262933 95.66248427-221.2448032 214.31688427-292.6830368 266.2877408C461.38864 808.5743296 301.43851307 687.4618112 219.3229664 580.77818347c-1.1024416-1.44954773-2.39371733-2.80522347-3.74721067-4.06593707-49.2027456-44.03436693-90.71568533-129.69410027-90.71568533-187.14769493 0-121.14308053 86.3670432-219.71666667 192.5496608-219.71666667 68.4452672 0 134.3407296 74.08409387 169.27394667 147.5383776 4.6291648 9.7331424 14.8982464 15.7954816 26.80461866 15.7954816s22.17436267-6.0634304 26.83518187-15.7954816c34.90156373-73.45428373 100.76427947-147.5383776 169.24338453-147.5383776C815.7451136 169.8478848 902.11434027 268.42147093 902.11434027 389.56455147" fill="#d81e06" p-id="3462"></path></svg></span>';
		} else {
		echo '<span title="取消走心"><svg t="1691141971354" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3103"><path d="M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032" fill="#d81e06" p-id="3104"></path></svg></span>';
		}
	?></a></span>
	<?php
	}
	?>
		</div>
	<div class="clear"></div>
</div>
<?php
}
function weisay_end_comment() {
		echo '</li>';
}

//走心评论独立页面使用
function weisay_touching_comments_list($comment) {
	$cpage = get_page_of_comment( $comment->comment_ID, $args = array() );
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>" class="comment-body">
		<div class="comment-meta">
		<div class="comment-author vcard"><?php echo get_avatar( $comment, 40, '', get_comment_author() ); ?></div>
		<b class="fn comment-name"><?php comment_author_link() ?></b><span class="edit-link"><?php edit_comment_link('编辑', ' ' ); ?></span>
		</div>
		<div class="comment-content"><?php comment_text(); ?></div>
		<div class="comment-metadata">
		<?php comment_date('Y-m-d') ?> 评论于&nbsp;&nbsp;•&nbsp;&nbsp;<a href="<?php echo get_comment_link($comment->comment_ID, $cpage); ?>" target="_blank"><?php echo get_the_title($comment->comment_post_ID); ?></a>
		</div>
	</div><div class="clear"></div>
<?php
}
function weisay_touching_comments_end_list() {
		echo '</li>';
}

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

	if (!is_user_logged_in() || !current_user_can('level_10')) {
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