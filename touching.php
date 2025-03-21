<?php
/*
Template Name: Touching
*/
?>
<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main main-all">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; <?php the_title(); ?></div>
</div>
<div class="main-all touching-comments-picture"><img src="<?php bloginfo('template_directory'); ?>/images/touching<?php echo rand(1,4)?>.jpg" alt="每一条评论，都是一个故事"></div>
<div class="article article_c">
	<div id="comments" class="comments-area">
	<h3 class="subtitle">目前已入选 <?php
		global $wpdb;
		$counts = $wpdb->get_results("SELECT * FROM $wpdb->comments WHERE comment_karma = '1' and comment_approved = '1'");
		$karmamun = count($counts);
		echo $karmamun;
		?> 条走心评论</h3>
	<div id="pagetext">
		<ol class="comment-list touching-comments-list">
		<?php
		$comments = get_comments(array(
			'karma' => '1',
			'status' => 'approve',
			'order' => 'desc',
		));
		wp_list_comments(array(
			'max_depth' => -1,
			'type' => 'comment',
			'callback' => 'weisay_touching_comments_list',
			'end-callback' => 'weisay_touching_comments_end_list',
			'per_page' => 20,
			'reverse_top_level' => false
		),$comments);
		?>
		</ol>
		<div class="pagination">
		<?php
		$per_page = 20;
		$pagemun = ceil($karmamun / $per_page);
		$max_page = $pagemun;
		paginate_comments_links(array('total' => $max_page));
		?></div>
	</div>
	<div class="clear"></div>
	</div>
</div>
</div>
</div>
<?php get_footer(); ?>