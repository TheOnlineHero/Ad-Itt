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


add_action( 'admin_init', 'register_ad_itt_search_settings' );
function register_ad_itt_search_settings() {
  $filter_image_name = $_POST["filter_image_name"];
  if ($filter_image_name != "") {
    $images = tom_get_results("posts", "*", "post_type='attachment' AND post_title LIKE '%$filter_image_name%' AND post_mime_type IN ('image/png', 'image/jpg', 'image/jpeg', 'image/gif')", array("post_date DESC"), "7");
    echo "<ul id='images'>";
    foreach ($images as $image) { 
        ?>
        <li>
          <img style='width: 100px; min-height: 100px' src='<?php echo($image->guid); ?>' />
        </li>

    <?php }
    echo "</ul>";
    exit();
  }
} 

add_action( 'admin_init', 'register_ad_itt_upload_settings' );
function register_ad_itt_upload_settings() {
  $uploadfiles = $_FILES['uploadfiles'];

  if (is_array($uploadfiles)) {

    foreach ($uploadfiles['name'] as $key => $value) {

      // look only for uploded files
      if ($uploadfiles['error'][$key] == 0) {

        $filetmp = $uploadfiles['tmp_name'][$key];

        //clean filename and extract extension
        $filename = $uploadfiles['name'][$key];

        // get file info
        // @fixme: wp checks the file extension....
        $filetype = wp_check_filetype( basename( $filename ), null );
        $filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
        $filename = $filetitle . '.' . $filetype['ext'];
        $upload_dir = wp_upload_dir();

        /**
         * Check if the filename already exist in the directory and rename the
         * file if necessary
         */
        $i = 0;
        while ( file_exists( $upload_dir['path'] .'/' . $filename ) ) {
          $filename = $filetitle . '_' . $i . '.' . $filetype['ext'];
          $i++;
        }
        $filedest = $upload_dir['path'] . '/' . $filename;

        /**
         * Check write permissions
         */
        if ( !is_writeable( $upload_dir['path'] ) ) {
          $this->msg_e('Unable to write to directory %s. Is this directory writable by the server?');
          return;
        }

        /**
         * Save temporary file to uploads dir
         */
        if ( !@move_uploaded_file($filetmp, $filedest) ){
          $this->msg_e("Error, the file $filetmp could not moved to : $filedest ");
          continue;
        }

        $attachment = array(
          'post_mime_type' => $filetype['type'],
          'post_title' => $filetitle,
          'post_content' => '',
          'post_status' => 'inherit',
        );

        $attach_id = wp_insert_attachment( $attachment, $filedest );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filedest );
        wp_update_attachment_metadata( $attach_id,  $attach_data );
        preg_match("/\/wp-content(.+)$/", $filedest, $matches, PREG_OFFSET_CAPTURE);
        tom_update_record_by_id("posts", array("guid" => get_option("siteurl").$matches[0][0]), "ID", $attach_id);
        echo $filedest;
      }
    }   
  }
}

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
    wp_register_script( 'my-jquery-colorbox', get_option("siteurl")."/wp-content/plugins/jquery-colorbox/js/jquery.colorbox-min.js" );
    wp_enqueue_script('my-jquery-colorbox');
    wp_register_script( 'my-form-script', plugins_url('/js/jquery.form.js', __FILE__) );
    wp_enqueue_script('my-form-script');
    wp_register_style( 'my-jquery-colorbox-style',get_option("siteurl")."/wp-content/plugins/jquery-colorbox/themes/theme1/colorbox.css");
    wp_enqueue_style('my-jquery-colorbox-style');
?>

<script type="text/javascript">
  jQuery(function() {
    var current_input;
    jQuery(".image-uploader").click(function() {
      current_input = jQuery(this).prev("input");
      jQuery.colorbox({inline:true, href:"#upload_image_container", width: "940px", height: "550px"});
    });

    jQuery("#filter_image_name").live("keydown", function() {
        if (jQuery(this).val().length < 2) {
          jQuery("#images_container").html("");
        } else {
          jQuery.post("<?php echo(get_option('siteurl')); ?>/wp-admin/admin.php?page=ad-itt/ad-itt.php", { filter_image_name: jQuery(this).val() },
              function(data) {
                jQuery("#images_container").html(data);
              }
          );
        }
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
            jQuery(".percent").hide();
            jQuery("#filter_image_name").val(jQuery("#uploadfiles").val().match("[a-z|A-Z|\.|-|_]*$")[0]);
            jQuery("#filter_image_name").val(jQuery("#filter_image_name").val().replace(new RegExp("\.[a-z|A-Z]*$","i"),""));
            jQuery("#filter_image_name").keydown();
        }
    }); 
    
  });
</script>
<style>
  #upload_image_container, #images {display: none;}
  #cboxWrapper #upload_image_container, #cboxWrapper #images {display: block;}
  ul#images li {float: left; margin-right: 5px;}
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
          <form name="uploadfile" id="uploadfile_form" method="POST" enctype="multipart/form-data" action="#uploadfile" accept-charset="utf-8" >
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
        <td><div id="images_container"></div></td>
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
  $msg_content = "<div class='updated'><p>Sorry for the confusion but you must install and activate ";
  $plugins_array = array();
  $upgrades_array = array();
  define('PLUGINPATH', ABSPATH.'wp-content/plugins');
  foreach ($dependencies as $key => $value) {
    $plugin = get_plugin_data(PLUGINPATH."/".$value["plugin"],true,true);
    $url = $value["url"];
    if (!is_plugin_active($value["plugin"])) {
      array_push($plugins_array, $key);
    } else {
      if (isset($value["version"]) && str_replace(".", "", $plugin["Version"]) < str_replace(".", "", $value["version"])) {
        array_push($upgrades_array, $key);
      }
    }
  }
  $msg_content .= implode(", ", $plugins_array) . " before you can use $plugin_name. Please go to Plugins/Add New and search/install the following plugin(s): ";
  $download_plugins_array = array();
  foreach ($dependencies as $key => $value) {
    if (!is_plugin_active($value["plugin"])) {
      $url = $value["url"];
      array_push($download_plugins_array, $key);
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
  <script language="javascript">
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
  </script>

<?php 
  wp_register_style( 'my-ad-itt-style', plugins_url('/css/ad-itt.css?20130115b', __FILE__) );
    wp_enqueue_style('my-ad-itt-style');
} ?>