jQuery(window).load(function() {
  if (AdIttAjax.enable_top_ad_link != "" && AdIttAjax.top_ad_link != "" && AdIttAjax.top_ad_img != "") {
      var close_link = "Close";
      if (AdIttAjax.top_ad_close_img != "") {
        close_link = "<img title='Close' alt='Close' src='" + AdIttAjax.top_ad_close_img + "' />";
      }
      var ad_class = AdIttAjax.top_ad_position;
      jQuery(AdIttAjax.css_content_wrapper_selector).prepend("<div class='"+ad_class+"' id='top_ad'><a target='_blank' href='" + AdIttAjax.top_ad_link + "'><img src='" + AdIttAjax.top_ad_img + "' /></a> <a id='close_top_ad_img' href=''>" + close_link + "</a></div>");
  }
  if (AdIttAjax.enable_bottom_ad_link != "" && AdIttAjax.bottom_ad_link != "" && AdIttAjax.bottom_ad_img != "") {
    jQuery(AdIttAjax.css_content_wrapper_selector).append("<a target='_blank' id='bottom_ad' href='" + AdIttAjax.bottom_ad_link + "'><img src='" + AdIttAjax.bottom_ad_img + "' /></a>");
  }
  if (AdIttAjax.enable_left_ad_link != "" && AdIttAjax.left_ad_link != "" && AdIttAjax.left_ad_img != "") {
    var ad_class = AdIttAjax.left_ad_position;
    jQuery("body").append("<a target='_blank' id='left_ad' class='" + ad_class + "' href='" + AdIttAjax.left_ad_link + "'><img src='" + AdIttAjax.left_ad_img + "' /></a>");
  }
  if (AdIttAjax.enable_right_ad_link != "" && AdIttAjax.right_ad_link != "" && AdIttAjax.right_ad_img != "") {
    var ad_class = AdIttAjax.right_ad_position;
    jQuery("body").append("<a target='_blank' id='right_ad' class='" + ad_class + "' href='" + AdIttAjax.right_ad_link + "'><img src='" + AdIttAjax.right_ad_img + "' /></a>");
  }
  jQuery("#top_ad").width(jQuery(AdIttAjax.css_content_wrapper_selector).css("width"));
  var position = jQuery(AdIttAjax.css_content_wrapper_selector).offset();
  var left = parseInt(position.left - parseInt(jQuery("#left_ad").css("width")));
  jQuery("#left_ad").css("left", left);
  var right = parseInt(position.left + parseInt(jQuery(AdIttAjax.css_content_wrapper_selector).css("width")));
  jQuery("#right_ad").css("left", right);
  if (AdIttAjax.top_ad_retract_time != "" ) {
    jQuery("#top_ad").delay(AdIttAjax.top_ad_retract_time).slideUp();
  }
  jQuery("#close_top_ad_img").live("click", function() {
    jQuery("#top_ad").hide();
    return false;
  });
  jQuery(window).resize(function() {
    var position = jQuery(AdIttAjax.css_content_wrapper_selector).offset();
    var left = parseInt(position.left - parseInt(jQuery("#left_ad").css("width")));
    jQuery("#left_ad").css("left", left);
    var right = parseInt(position.left + parseInt(jQuery(AdIttAjax.css_content_wrapper_selector).css("width")));
    jQuery("#right_ad").css("left", right);
  });
});