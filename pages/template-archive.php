<?php
/*
Template Name: 文章归档
*/
?>
<?php get_header(); ?>
<?php $theme = wp_get_theme(); $themeversion = $theme -> get('Version'); ?>
<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/archives.js?ver=' . $themeversion); ?>"></script>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; <?php the_title(); ?></div>
</div>
<div class="article article_c">
<p class="articles_all"><strong><?php bloginfo('name'); ?></strong> 目前共有 <?php echo intval(wp_count_posts()->publish); ?>篇文章，<?php $comment_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'"); echo $comment_count; ?>条评论。</p>
<?php global $article_archive; echo $article_archive->post_list(); ?>
</div>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>