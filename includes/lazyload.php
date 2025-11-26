<script type="text/javascript" src="<?php echo esc_url(get_template_directory_uri() . '/js/lazyload.js'); ?>"></script>
<script type="text/javascript">
	jQuery(function() {
		jQuery(".article img, .articles img").not("#respond_box img").lazyload({
			placeholder: "<?php echo esc_url(get_template_directory_uri()); ?>/images/image-pending.gif",
			effect: "fadeIn"
		});
	});
</script>