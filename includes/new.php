<?php
    $t1=$post->post_date_gmt;
    $t2=date("Y-m-d H:i:s");
    $interval=(strtotime($t2)-strtotime($t1))/86400;
    if($interval<5){echo '<img src="'.get_bloginfo('template_directory').'/images/new.gif" alt="较新的文章" title="较新的文章" />';}
?>