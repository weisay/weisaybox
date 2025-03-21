<div class="tab">
<ul id="tabnav">
<li>最新日志</li>
<li class="selected">热评日志</li>
<li>随机日志</li>
</ul>
</div>
<div class="clear"></div>
<div id="tab-content">
<ul>
<?php $myposts = get_posts('numberposts=10&offset=0');foreach($myposts as $post) :?>
<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>
</ul>
<ul class="active"><?php simple_get_most_viewed(); ?></ul>
<ul>
<?php $myposts = get_posts('numberposts=10&orderby=rand');foreach($myposts as $post) :?>
<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>
</ul>
</div>