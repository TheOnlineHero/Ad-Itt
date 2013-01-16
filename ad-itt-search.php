<ul>
<?php
//call register settings function
require_once("../../../wp-blog-header.php");
add_action( 'admin_init', 'register_ad_itt_search_settings' );
function register_ad_itt_search_settings() {
	$filter_image_name = $_POST["filter_image_name"];
	$images = tom_get_results("posts", "*", "post_type='attachment' AND post_title LIKE '%$filter_image_name%' AND post_mime_type IN ('image/png', 'image/jpg', 'image/jpeg', 'image/gif')", array("post_date DESC"), "7");
	foreach ($images as $image) { 
	    ?>
	    <li>
	      <img style='width: 100px; min-height: 100px' src='<?php echo($image->guid); ?>' />
	    </li>
	<?php }
} 
register_ad_itt_search_settings();
?>
</ul>