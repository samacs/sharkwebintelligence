<?php
//-----------------------------------  // Add Support Tab Styles //-----------------------------------//

function load_custom_wp_admin_style(){
        //Support Tab Style
        wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/includes/support/options-style.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );
        
        //iFrame Auto Height
        wp_enqueue_script('iframe_js', get_template_directory_uri() . '/includes/support/iframe/jquery.autoheight.js', false );
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');


//-----------------------------------  // Add Themes to Support Tab //-----------------------------------//

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
	jQuery(document).ready(function( $ ) {
		$(".embed-themes").html("<iframe width='770' src='http://themes.okaythemes.com/browse/iframe/index.html'></iframe>");  
		$('.embed-themes iframe').iframeAutoHeight();
	});
</script>
 
<?php
}