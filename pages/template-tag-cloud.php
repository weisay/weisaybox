<?php
/*
Template Name: 标签云集
*/
?>
<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="查看评论" class="roll_comm"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; <?php the_title(); ?></div>
</div>
<div class="article article_c page-tag">
<?php echo tag_cloud_list(); ?>
</div>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>