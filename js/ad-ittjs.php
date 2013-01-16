<?php require_once("../../../../wp-blog-header.php");
header("Content-Type: text/javascript"); ?>
jQuery(window).load(function() {
	<?php if (get_option('enable_top_ad_link') != "" && get_option('top_ad_link') != "" && get_option('top_ad_img') != "") {
			$close_link = "Close";
			if (get_option("top_ad_close_img") != "") {
				$close_link = "<img title='Close' alt='Close' src='".get_option("top_ad_close_img")."' />";
			}
			$class = get_option("top_ad_position");
			echo("jQuery(\"".get_option('css_content_wrapper_selector')."\").prepend(\"<div class='".$class."' id='top_ad'><a target='_blank' href='".get_option('top_ad_link')."'><img src='".get_option('top_ad_img')."' /></a> <a id='close_top_ad_img' href=''>".$close_link."</a></div>\");");
	} ?>
	<?php if (get_option('enable_bottom_ad_link') != "" && get_option('bottom_ad_link') != "" && get_option('bottom_ad_img') != "") {

		echo("jQuery(\"".get_option('css_content_wrapper_selector')."\").append(\"<a target='_blank' id='bottom_ad' href='".get_option('bottom_ad_link')."'><img src='".get_option('bottom_ad_img')."' /></a>\");");
	} ?>
	<?php if (get_option('enable_left_ad_link') != "" && get_option('left_ad_link') != "" && get_option('left_ad_img') != "") {
		$class = get_option("left_ad_position");
		echo("jQuery(\"body\").append(\"<a target='_blank' id='left_ad' class='".$class."' href='".get_option('left_ad_link')."'><img src='".get_option('left_ad_img')."' /></a>\");");
	} ?>
	<?php if (get_option('enable_right_ad_link') != "" && get_option('right_ad_link') != "" && get_option('right_ad_img') != "") {
		$class = get_option("right_ad_position");
		echo("jQuery(\"body\").append(\"<a target='_blank' id='right_ad' class='".$class."' href='".get_option('right_ad_link')."'><img src='".get_option('right_ad_img')."' /></a>\");");
	} ?>

	jQuery("#top_ad").width(jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").css("width"));

	var position = jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").offset();
  var left = parseInt(position.left - parseInt(jQuery("#left_ad").css("width")));
	jQuery("#left_ad").css("left", left);

	var right = parseInt(position.left + parseInt(jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").css("width")));
	jQuery("#right_ad").css("left", right);

	<?php if (get_option('top_ad_retract_time') != "" ) { ?>
		jQuery("#top_ad").delay(<?php echo(get_option('top_ad_retract_time')); ?>).slideUp();
	<?php } ?>

	jQuery("#close_top_ad_img").live("click", function() {
		jQuery("#top_ad").hide();
		return false;
	});

	jQuery(window).resize(function() {
		var position = jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").offset();
	  var left = parseInt(position.left - parseInt(jQuery("#left_ad").css("width")));
		jQuery("#left_ad").css("left", left);

		var right = parseInt(position.left + parseInt(jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").css("width")));
		jQuery("#right_ad").css("left", right);
	});
});