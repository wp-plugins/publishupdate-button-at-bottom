<?php
/**
 * Plugin Name: Publish/Update Button At Bottom
 * Plugin URI:
 * Description: This plugin pushes a copy of the Publish/Update button into an on screen footer. This is really for Posts that have a lot of Custom Fields which makes the post editing screen very long.
 * Version: 2.0.2
 * Author: Kev Leitch kevleitch@gmail.com
 * Author URI: 
 * License: GPLv2 or later
 */
 
add_action('admin_init', 'force_publish_update_button_to_bottom_init');
add_action('edit_form_advanced', 'force_publish_update_button_to_bottom');
add_action('edit_page_form', 'force_publish_update_button_to_bottom');
add_action('admin_enqueue_scripts', 'pbfab_stylesheet');

// Styles
function pbfab_stylesheet() {
    wp_register_style('pbfabreg',plugin_dir_url( __FILE__ ) . 'pbfab-css.css');
    wp_enqueue_style( 'pbfabreg' );
}

// jQuery
function force_publish_update_button_to_bottom_init(){
	wp_enqueue_script('jquery');
}

// Do the work
function force_publish_update_button_to_bottom() {
	echo "<script type='text/javascript'>\n";
	echo "
	jQuery(function($) { 
		if($('body').hasClass('post-type-post') || $('body').hasClass('post-type-page')) {
			$('#publishing-action').append('<div id=\"publish-floater-wrapper\"><input name=\"publish\" type=\"submit\" class=\"button button-primary button-large\" id=\"publish-floater\" accesskey=\"p\" value=\"Publish or Update\"></div>');
		}
		$('a.submitdelete').clone().appendTo('#publish-floater-wrapper');
		$('#publish-floater-wrapper a.submitdelete').addClass('button button-primary button-large');
		$('#publish-floater-wrapper a.submitdelete:last').remove();
		$('#publish-floater-wrapper a.submitdelete').addClass('byebye');
		$('#publish-floater-wrapper a.byebye').removeClass('submitdelete');
		$('a#post-preview').clone().appendTo('#publish-floater-wrapper');
		$('input#save-post').clone().appendTo('#publish-floater-wrapper');
		var dcm = (document.cookie.match(/^(?:.*;)?never-show-info-bar-again=([^;]+)(?:.*)?$/)||[,null])[1];
		if(dcm == 1){
			var cookieornot = \"none\";
		}else{
			var cookieornot = \"block\";
		}
		$('body').append('<div id=\"publish-floater-wrapper-info\" style=\"display:' + cookieornot +';\"><h3>Publish buttons at bottom</h3><p>You can hide this bar by pressing the \'down\' key on your keyboard. If you need to see it again, press the \'up\' key on your keyboard.</p><p><a  class=\"got-it\" href=\"\">OK, got it.</a></p><div id=\"tri-down\"><img src=\"" . plugin_dir_url( __FILE__ )  . "/arrowdown.png\" /></div></div>');
		$(document).keydown(function(e) {
			switch(e.which) {
			case 38: // up
			$('#publish-floater-wrapper').fadeIn();
			break;
			case 40: // down
			$('#publish-floater-wrapper').fadeOut();
			break;
			default: return;
		}
		e.preventDefault();
	});
	$('a.got-it').click(function() {
		$('#publish-floater-wrapper-info').fadeOut();
		document.cookie=\"never-show-info-bar-again=1; expires=Wed, 1 Jan 2020 12:00:00 UTC\";
		return false;
	});
});
";
echo "</script>\n";	
}
?>