<div id="sidebar">
<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
<div class="widgets">
<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div>
<?php endif; ?>
<div id="tab-title">
<?php include('includes/sidebar_tab.php'); ?>
</div>
<?php if (weisay_option('wei_hotreviewer') == 'hide') { ?>
<?php { echo ''; } ?>
<?php } else { include('includes/sidebar_hotreviewer.php'); } ?>
<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
<div class="widgets">
<?php dynamic_sidebar( 'sidebar-2' ); ?>
</div>
<?php endif; ?>
<div class="widget">
<?php include('includes/sidebar_comment.php'); ?>
</div>
<?php if (weisay_option('wei_tags') == 'display') { ?>
<div class="widget">
<h3>标签云集</h3>
<div class="tags"><?php wp_tag_cloud('smallest=0.875&largest=0.875&unit=rem&number=18&orderby=count&order=RAND');?></div><div class="clear"></div>
</div>
<?php } else { echo ''; }  ?>
<?php if ( is_home() ) { ?>
<div class="widget">
<h3>友情链接</h3>
<div class="v-links"><ul><?php wp_list_bookmarks('orderby=link_id&categorize=0&show_images=0&category='.weisay_option('wei_linkid').'&title_li='); ?></ul></div><div class="clear"></div></div>
<?php } ?>
<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
<div class="widgets">
<?php dynamic_sidebar( 'sidebar-3' ); ?>
</div>
<?php endif; ?>
<?php if(function_exists('the_views')) {  ?>
<?php if ( is_singular() || is_category() && function_exists('get_most_viewed_category')){ ?>
<div style="margin-top:15px;">
<div class="widget hotpost">
<h3>热门日志</h3>
<ul><?php
if (is_single() || is_category()){
    get_timespan_most_viewed_category('single', 'post');
} else {
   get_timespan_most_viewed('post');
}
?></ul>
</div>
</div>
<?php } ?>
<?php } ?>
</div>