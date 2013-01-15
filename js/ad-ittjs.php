<?php require_once("../../../../wp-blog-header.php");
header("Content-Type: text/javascript"); ?>
jQuery(window).load(function() {

	<?php if (get_option('top_ad_link') != "" && get_option('top_ad_img') != "") {
			echo("jQuery(\"".get_option('css_content_wrapper_selector')."\").prepend(\"<a target='_blank' id='top_ad' href='".get_option('top_ad_link')."'><img src='".get_option('top_ad_img')."' /></a>\");");
	} ?>
	<?php if (get_option('bottom_ad_link') != "" && get_option('bottom_ad_img') != "") {
		echo("jQuery(\"".get_option('css_content_wrapper_selector')."\").append(\"<a target='_blank' id='bottom_ad' href='".get_option('bottom_ad_link')."'><img src='".get_option('bottom_ad_img')."' /></a>\");");
	} ?>
	<?php if (get_option('left_ad_link') != "" && get_option('bottom_ad_img') != "") {
		echo("jQuery(\"body\").append(\"<a target='_blank' id='left_ad' href='".get_option('left_ad_link')."'><img src='".get_option('left_ad_img')."' /></a>\");");
	} ?>
	<?php if (get_option('right_ad_link') != "" && get_option('right_ad_img') != "") {
		echo("jQuery(\"body\").append(\"<a target='_blank' id='right_ad' href='".get_option('right_ad_link')."'><img src='".get_option('right_ad_img')."' /></a>\");");
	} ?>
	var position = jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").offset();
  var left = parseInt(position.left - parseInt(jQuery("#left_ad").css("width")));
	jQuery("#left_ad").css("left", left);

	var right = parseInt(position.left + parseInt(jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").css("width")));
	jQuery("#right_ad").css("left", right);

});

jQuery(window).resize(function() {
	var position = jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").offset();
  var left = parseInt(position.left - parseInt(jQuery("#left_ad").css("width")));
	jQuery("#left_ad").css("left", left);

	var right = parseInt(position.left + parseInt(jQuery("<?php echo(get_option('css_content_wrapper_selector')); ?>").css("width")));
	jQuery("#right_ad").css("left", right);
});