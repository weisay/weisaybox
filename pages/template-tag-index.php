<?php
/*
Template Name: 标签索引
*/
?>
<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="查看评论" class="roll_comm"></div><div title="转到底部" class="roll_down"></div></div>
<script type="text/javascript">
jQuery(document).on('click','.tag-index a[href^="#"]',function(e){e.preventDefault();var href=jQuery(this).attr('href');var targetId=href;var selector='#'+CSS.escape(targetId.substring(1));var $target=jQuery(selector);if($target.length){var pos=$target.offset().top;jQuery('html, body').animate({scrollTop:pos},400)}});
</script>
<div id="content">
<div class="main main-all">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; <?php the_title(); ?></div>
</div>
<div class="article article_c page-tag">
<?php tag_groups_html(); ?>
</div>
</div>
</div>
<?php get_footer(); ?>