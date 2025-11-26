<?php get_header(); ?>
<div class="roll">
<div id="dark-mode-toggle-button" onclick="applyCustomDarkModeSettings(toggleCustomDarkMode());" title="点击切换显示模式" class="roll_dark"></div>
<div title="回到顶部" class="roll_top"></div><div title="转到底部" class="roll_down"></div></div>
<div id="content">
<div class="main">
<div id="map">
<div class="site">当前位置： <a title="返回首页" href="<?php bloginfo('url'); ?>/">首页</a> &gt; 
	<?php if (have_posts()) : ?>
	<?php $post = $posts[0]; ?>
	<?php if (is_category()) { ?><?php single_cat_title(); ?>
	<?php } elseif( is_tag() ) { ?><?php single_tag_title(); ?>
	<?php } elseif (is_day()) { ?><?php the_time('Y年n月j日'); ?>发布的所有文章
	<?php } elseif (is_month()) { ?><?php the_time('Y年n月'); ?>发布的所有文章
	<?php } elseif (is_year()) { ?><?php the_time('Y年'); ?>发布的所有文章
	<?php } elseif (is_author()) { ?><?php the_author(); ?>发布的所有文章
	<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
	博客分类
	<?php } ?><?php endif; ?>
</div>
</div>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<ul <?php post_class(); ?> id="post-<?php the_ID(); ?>">
<li>
<div class="article">
<?php edit_post_link('编辑', '<span class="edit" style="display:none;">', '</span>'); ?>
<h2 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><?php require get_template_directory() . '/includes/new.php'; ?></h2>
<?php if (weisay_option('wei_thumbnail') == 'two') { ?>
<?php require get_template_directory() . '/includes/thumbnail-two.php'; ?>
<?php } else if (weisay_option('wei_thumbnail') == 'three') { ?>
<?php require get_template_directory() . '/includes/thumbnail-three.php'; ?>
<?php } else { require get_template_directory() . '/includes/thumbnail-one.php'; } ?>
<div class="entry-summary"><?php
$pc = $post->post_content;
$full_text = strip_tags(apply_filters('the_content', $pc));
if (has_excerpt()) {
	$excerpt = strip_tags(get_the_excerpt());
	echo '<p>' . $excerpt . '</p>';
}
elseif (preg_match('/<!--more.*?-->/',$pc)) {
	$parts = get_extended($pc);
	$more_text = strip_tags(apply_filters('the_content', $parts['main']));
	echo '<p>' . $more_text . '</p>';
}
else {
	echo '<p>' . mb_strimwidth($full_text, 0, 300, ' ...') . '</p>';
}
?></div>
<div class="clear"></div>
<div class="info"><span class="author"><span class="fn"><?php the_author() ?></span></span><span class="updated"><span class="date-fby"> 发布于 </span><?php the_time('Y-m-d') ?><span class="date-hi"><?php the_time(' H:i') ?></span></span><span class="category"><?php the_category(', ') ?></span><?php if(function_exists('the_views')) { echo '<span class="views">'; the_views(); echo '</span>'; } ?><span class="comments"><?php comments_popup_link ('抢沙发','1条评论','<span content="UserComments:%">%</span>条评论'); ?></span><?php the_tags('<span class="tags">', ', ', '</span>'); ?></div>
<div class="more"><a style="width: 30px; overflow: hidden;" class="read-more-icon" href="<?php the_permalink() ?>" title="<?php the_title(); ?>" rel="nofollow"><strong>Read more</strong><span></span></a></div>
</div>
</li>
</ul>
<div class="clear"></div>
	<?php endwhile; ?>
	<?php endif; ?>
<?php if(function_exists('paging_nav')) paging_nav(); ?>
</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>