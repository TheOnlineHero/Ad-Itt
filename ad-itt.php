<?php
/*
Plugin Name: Ad Itt
Plugin URI: http://wordpress.org/extend/plugins/ad-itt/
Description: Adds advertisements to your website.

Installation:

1) Install WordPress 3.4.2 or higher

2) Download the following file:

http://downloads.wordpress.org/plugin/ad-itt.zip

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.

Version: 1.0
Author: TheOnlineHero - Tom Skroza
License: GPL2
*/

function ad_itt_activate() {
  add_option( "css_content_wrapper_selector", "", "", "yes" );
  add_option( "enable_left_ad_link", "", "", "yes" );
  add_option( "left_ad_link", "", "", "yes" );
  add_option( "left_ad_img", "", "", "yes" );
  add_option( "left_ad_position", "", "", "yes" );
  add_option( "enable_right_ad_link", "", "", "yes" );
  add_option( "right_ad_link", "", "", "yes" );
  add_option( "right_ad_img", "", "", "yes" );
  add_option( "right_ad_position", "", "", "yes" );

  add_option( "enable_top_ad_link", "", "", "yes" );
  add_option( "top_ad_link", "", "", "yes" );
  add_option( "top_ad_img", "", "", "yes" );
  add_option( "top_ad_retract_time", "", "", "yes" );
  add_option( "top_ad_close_img", "", "", "yes" );
  add_option( "top_ad_position", "", "", "yes" );
  add_option( "enable_bottom_ad_link", "", "", "yes" );
  add_option( "bottom_ad_link", "", "", "yes" );
  add_option( "bottom_ad_img", "", "", "yes" );
  add_option( "bottom_ad_position", "", "", "yes" );

}
register_activation_hook( __FILE__, 'ad_itt_activate' );

add_action('admin_menu', 'register_ad_itt_page');

function register_ad_itt_page() {
   add_menu_page('Ad Itt', 'Ad Itt', 'manage_options', 'ad-itt/ad-itt.php', 'ad_itt_settings_page');
}

//call register settings function
add_action( 'admin_init', 'register_ad_itt_settings' );
function register_ad_itt_settings() {
  //register our settings
  register_setting( 'ad-itt-group', 'css_content_wrapper_selector' );
  register_setting( 'ad-itt-group', 'enable_left_ad_link' );
  register_setting( 'ad-itt-group', 'left_ad_link' );
  register_setting( 'ad-itt-group', 'left_ad_img' );
  register_setting( 'ad-itt-group', 'left_ad_position' );
  register_setting( 'ad-itt-group', 'enable_right_ad_link' );
  register_setting( 'ad-itt-group', 'right_ad_link' );
  register_setting( 'ad-itt-group', 'right_ad_img' );
  register_setting( 'ad-itt-group', 'right_ad_position' );

  register_setting( 'ad-itt-group', 'enable_top_ad_link' );
  register_setting( 'ad-itt-group', 'top_ad_link' );
  register_setting( 'ad-itt-group', 'top_ad_img' );
  register_setting( 'ad-itt-group', 'top_ad_retract_time' );
  register_setting( 'ad-itt-group', 'top_ad_close_img' );
  register_setting( 'ad-itt-group', 'top_ad_position' );
  register_setting( 'ad-itt-group', 'enable_bottom_ad_link' );
  register_setting( 'ad-itt-group', 'bottom_ad_link' );
  register_setting( 'ad-itt-group', 'bottom_ad_img' );
  register_setting( 'ad-itt-group', 'bottom_ad_position' );
  
  @check_ad_itt_dependencies_are_active(
    "Ad Itt", 
    array(
      "Tom M8te" => array("plugin"=>"tom-m8te/tom-m8te.php", "url" => "http://downloads.wordpress.org/plugin/tom-m8te.zip", "version" => "1.1"),
      "JQuery Colorbox" => array("plugin"=>"jquery-colorbox/jquery-colorbox.php", "url" => "http://downloads.wordpress.org/plugin/jquery-colorbox.zip"))
  );
}


function ad_itt_settings_page() {
?>

<?php
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');
    // wp_enqueue_script('jquery-ui-resizable');
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_register_script('my-upload', WP_PLUGIN_URL, array('jquery','media-upload','thickbox'));
    wp_enqueue_script('my-upload');
    echo("<script language='javascript' src='".get_option("siteurl")."/wp-content/plugins/jquery-colorbox/js/jquery.colorbox-min.js'></script>");
    echo("<script language='javascript' src='http://malsup.github.com/jquery.form.js'></script>");
    echo("<link rel='stylesheet' href='".get_option("siteurl")."/wp-content/plugins/jquery-colorbox/themes/theme1/colorbox.css' />");
?>

<script type="text/javascript">
  jQuery(function() {
    var current_input;
    jQuery(".image-uploader").click(function() {
      current_input = jQuery(this).prev("input");
      jQuery.colorbox({inline:true, href:"#upload_image_container", width: "940px", height: "550px"});
    });

    jQuery("#filter_image_name").live("keyup", function() {
        jQuery.post("<?php echo(get_option('siteurl')); ?>/wp-content/plugins/ad-itt/ad-itt-search.php", { filter_image_name: jQuery(this).val() },
            function(data) {
              jQuery("#images").html(data);
            }
        );
    });

    jQuery("#images img").live("click", function() {
      jQuery(current_input).val(jQuery(this).attr("src"));
      jQuery("#cboxClose").click();
    });

    var bar = jQuery('.bar');
    var percent = jQuery('.percent');
    jQuery(".percent").hide();
    jQuery('#uploadfile_form').ajaxForm({
        beforeSend: function() {
            jQuery(".percent").hide();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            jQuery(".percent").show();
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
        },
        complete: function(xhr) {
            image_url = xhr.responseText.split(",")[0].match(/wp-content(.+)$/)[0];
            jQuery(".percent").hide();
            jQuery("#filter_image_name").val(jQuery("#uploadfiles").val().match("[a-z|A-Z|\.|-|_]*$")[0]);
            jQuery("#filter_image_name").val(jQuery("#filter_image_name").val().replace(new RegExp("\.[a-z|A-Z]*$","i"),""));
            jQuery("#filter_image_name").keyup();
        }
    }); 
    
  });
</script>
<style>
  #upload_image_container {display: none;}
  #cboxWrapper #upload_image_container {display: block;}
  #images ul li {float: left; margin-right: 5px;}
  .hint { color: #008000;}
  .inside th {text-align: left;}
  .inside table {margin-left: 10px;}
  tr.odd th {background: #cac9c9;}
  tbody tr.odd td, tr.odd th {background: #dfdfdf;}
  .inside table {width: 100%;}
  th.enable-col {width: 20px;text-align: center;}
</style>

<div id="upload_image_container">
  <div class="wrap">
<h2>Ad Itt</h2>
<div class="postbox " style="display: block; ">
<div class="inside">
  <table class="form-table">
    <tbody>

      <tr valign="top">
        <th scope="row">
          <label for="filter_image_name">Upload</label>
        </th>
        <td>
          <form name="uploadfile" id="uploadfile_form" method="POST" enctype="multipart/form-data" action="<?php echo (get_option('siteurl').'/wp-content/plugins/ad-itt/ad-itt-upload.php').'#uploadfile'; ?>" accept-charset="utf-8" >
            <input type="file" name="uploadfiles[]" id="uploadfiles" size="35" class="uploadfiles" />
            <input class="button-primary" type="submit" name="uploadfile" id="uploadfile_btn" value="Upload"  />
          </form>
          <div class="progress">
              <div class="bar"></div >
              <div class="percent">0%</div >
          </div>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">
          <label for="filter_image_name">Search</label>
        </th>
        <td>
          <input type="text" id="filter_image_name" name="filter_image_name" value="" />
        </td>
      </tr>
      <tr>
        <td></td>
        <td><div id="images"></div></td>
      </tr>
    </tbody>
  </table>
</div>
</div>
</div>
</div>

<div class="wrap">
<h2>Ad Itt</h2>
<div class="postbox " style="display: block; ">
<div class="inside">
<form method="post" action="options.php">
  <?php settings_fields( 'ad-itt-group' ); ?>
  <table class="form-table">
    <thead>
      <tr>
        <th class="enable-col">Enable/Disable</th>
        <th colspan="2"></th>
      </tr>
    </thead>
    <tbody>
      <tr valign="top" class="odd">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="css_content_wrapper_selector">Css Content Wrapper Selector</label>
        </th>
        <td>
          <input type="text" name="css_content_wrapper_selector" value="<?php echo get_option('css_content_wrapper_selector'); ?>" />
          <span class="hint">Example: #content, #main-content, .content</span>
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col">
          <input type="hidden" name="enable_top_ad_link" value="" />
          <input type="checkbox" value="on" name="enable_top_ad_link" <?php if (get_option("enable_top_ad_link") == "on") { echo "checked"; } ?>/>
        </th>
        <th scope="row">
          <label for="top_ad_link">Top Ad Link</label>
        </th>
        <td>
          <input type="text" id="top_ad_link" name="top_ad_link" value="<?php echo get_option('top_ad_link'); ?>" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="top_ad_img">Top Ad Image</label>
        </th>
        <td>
          <input type="text" id="top_ad_img" name="top_ad_img" value="<?php echo get_option('top_ad_img'); ?>" />
          <input type="button" class="image-uploader" value="Upload" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="top_ad_close_img">Top Ad Close Image</label>
        </th>
        <td>
          <input type="text" id="top_ad_close_img" name="top_ad_close_img" value="<?php echo get_option('top_ad_close_img'); ?>" />
          <input type="button" class="image-uploader" value="Upload" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="top_ad_retract_time">Top Ad Time Limit (in milliseconds)</label>
        </th>
        <td>
          <input type="text" id="top_ad_retract_time" name="top_ad_retract_time" value="<?php echo get_option('top_ad_retract_time'); ?>" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="top_ad_position">Top Ad Position</label>
        </th>
        <td>
          <select id="top_ad_position" name="top_ad_position">
            <option value="relative" <?php if (get_option("top_ad_position") == "relative") {echo("selected");}?> >Relative</option>
            <option value="absolute" <?php if (get_option("top_ad_position") == "absolute") {echo("selected");}?> >Absolute</option>
          </select>
        </td>
      </tr>

      <tr valign="top" class="odd">
        <th class="enable-col">
          <input type="hidden" name="enable_bottom_ad_link" value="" />
          <input type="checkbox" value="on" name="enable_bottom_ad_link" <?php if (get_option("enable_bottom_ad_link") == "on") { echo "checked"; } ?>/>
        </th>
        <th scope="row">
          <label for="bottom_ad_link">Bottom Ad Link</label>
        </th>
        <td>
          <input type="text" id="bottom_ad_link" name="bottom_ad_link" value="<?php echo get_option('bottom_ad_link'); ?>" />
        </td>
      </tr>

      <tr valign="top" class="odd">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="bottom_ad_img">Bottom Ad Image</label>
        </th>
        <td>
          <input type="text" id="bottom_ad_img" name="bottom_ad_img" value="<?php echo get_option('bottom_ad_img'); ?>" />
          <input type="button" class="image-uploader" value="Upload" />
        </td>
      </tr>

<!--       <tr valign="top" class="odd">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="bottom_ad_position">Bottom Ad Position</label>
        </th>
        <td>
          <select id="bottom_ad_position" name="bottom_ad_position">
            <option value="absolute" <php if (get_option("bottom_ad_position") == "absolute") {echo("selected");}> >Absolute</option>
            <option value="static" <php if (get_option("bottom_ad_position") == "static") {echo("selected");}> >Static</option>
          </select>
        </td>
      </tr> -->

      <tr valign="top" class="even">
        <th class="enable-col">
          <input type="hidden" name="enable_left_ad_link" value="" />
          <input type="checkbox" value="on" name="enable_left_ad_link" <?php if (get_option("enable_left_ad_link") == "on") { echo "checked"; } ?>/>
        </th>
        <th scope="row">
          <label for="left_ad_link">Left Ad Link</label>
        </th>
        <td>
          <input type="text" id="left_ad_link" name="left_ad_link" value="<?php echo get_option('left_ad_link'); ?>" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="left_ad_img">Left Ad Image</label>
        </th>
        <td>
          <input type="text" id="left_ad_img" name="left_ad_img" value="<?php echo get_option('left_ad_img'); ?>" />
          <input type="button" class="image-uploader" value="Upload" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="left_ad_position">Left Ad Position</label>
        </th>
        <td>
          <select id="left_ad_position" name="left_ad_position">
            <option value="fixed" <?php if (get_option("top_ad_position") == "fixed") {echo("selected");}?> >Fixed</option>
            <option value="absolute" <?php if (get_option("top_ad_position") == "absolute") {echo("selected");}?> >Absolute</option>
          </select>
        </td>
      </tr>

      <tr valign="top" class="odd">
        <th class="enable-col">
          <input type="hidden" name="enable_right_ad_link" value="" />
          <input type="checkbox" value="on" name="enable_right_ad_link" <?php if (get_option("enable_right_ad_link") == "on") { echo "checked"; } ?>/>
        </th>
        <th scope="row">
          <label for="right_ad_link">Right Ad Link</label>
        </th>
        <td>
          <input type="text" id="right_ad_link" name="right_ad_link" value="<?php echo get_option('right_ad_link'); ?>" />
        </td>
      </tr>

      <tr valign="top" class="odd">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="right_ad_img">Right Ad Image</label>
        </th>
        <td>
          <input type="text" id="right_ad_img" name="right_ad_img" value="<?php echo get_option('right_ad_img'); ?>" />
          <input type="button" class="image-uploader" value="Upload" />
        </td>
      </tr>

      <tr valign="top" class="even">
        <th class="enable-col"></th>
        <th scope="row">
          <label for="right_ad_position">Right Ad Position</label>
        </th>
        <td>
          <select id="right_ad_position" name="right_ad_position">
            <option value="fixed" <?php if (get_option("right_ad_position") == "fixed") {echo("selected");}?> >Fixed</option>
            <option value="absolute" <?php if (get_option("right_ad_position") == "absolute") {echo("selected");}?> >Absolute</option>
          </select>
        </td>
      </tr>

    </tbody>
  </table>
  <p class="submit">
    <input type="submit" name="Submit" value="Update Options">
  </p>
  </form>
</div>
</div>
</div>
<?php }

function check_ad_itt_dependencies_are_active($plugin_name, $dependencies) {
  require_once(ABSPATH . 'wp-admin/includes/plugin.php');
  $msg_content = "<div class='updated'><p>Sorry for the confusion but you must install and activate ";
  $plugins_array = array();
  $upgrades_array = array();
  define('PLUGINPATH', ABSPATH.'wp-content/plugins');
  foreach ($dependencies as $key => $value) {
    $plugin = get_plugin_data(PLUGINPATH."/".$value["plugin"],true,true);
    $url = $value["url"];
    if (!is_plugin_active($value["plugin"])) {
      array_push($plugins_array, "<a href='$url'>$key</a>");
    } else {
      if (isset($value["version"]) && str_replace(".", "", $plugin["Version"]) < str_replace(".", "", $value["version"])) {
        array_push($upgrades_array, "<a href='$url'>$key</a>");
      }
    }
  }
  $msg_content .= implode(", ", $plugins_array) . " before you can use $plugin_name. Please ";
  $download_plugins_array = array();
  foreach ($dependencies as $key => $value) {
    if (!is_plugin_active($value["plugin"])) {
      $url = $value["url"];
      array_push($download_plugins_array, "<a href='$url'>click here to download $key</a>");
    }
  }
  $msg_content .= implode(", ", $download_plugins_array)."</p></div>";
  if (count($plugins_array) > 0) {
    deactivate_plugins( __FILE__, true);
    echo($msg_content);
  } 

  if (count($upgrades_array) > 0) {
    deactivate_plugins( __FILE__,true);
    echo "<div class='updated'><p>$plugin_name requires the following plugins to be updated: ".implode(", ", $upgrades_array).".</p></div>";
  }
}

add_action('wp_head', 'add_ad_itt_js_and_css');
function add_ad_itt_js_and_css() { 
  wp_enqueue_script('jquery');
  ?>
  <script language="javascript" src="<?php echo(get_option("siteurl")); ?>/wp-content/plugins/ad-itt/js/ad-ittjs.php?20130115b"></script>
  <link rel="stylesheet" href="<?php echo(get_option("siteurl")); ?>/wp-content/plugins/ad-itt/css/ad-itt.css?20130115b"></link>
<?php } ?>