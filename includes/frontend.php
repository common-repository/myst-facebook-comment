<?php 


// 1. Initialised the Facebook SDK
// ** Check if the SDK already init manually
if(get_option('myst_fbc_app_id')!="" && get_option('myst_fbc_include_sdk')){
	add_action('wp_head','hook_fb_sdk');
	function hook_fb_sdk() {
		
		ob_start(); ?>
	   <!-- FB Init -->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/<?php echo get_option('myst_fbc_adjust_language') ?>/sdk.js#xfbml=1&version=v2.6&appId=<?php echo get_option('myst_fbc_app_id') ?>";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<?php $output = ob_get_clean();
		echo $output;
	}
}

// 2. Moderator Listing
if(get_option('myst_fbc_app_id')!=""){
	add_action('wp_head','myst_fbc_add_mod');
	function myst_fbc_add_mod() {
		$output = '<meta property="fb:app_id" content="' . get_option('myst_fbc_app_id') . '" />';
		echo $output;
	}
	
}

if(get_option('myst_fbc_add_moderator')!=""){
	add_action('wp_head','myst_fbc_add_mod');
	function myst_fbc_add_mod() {
		
		$getmod = explode (",", get_option('myst_fbc_add_moderator'));
		
		foreach ($getmod as $singmod) {
			$output .= '<meta property="fb:admins" content="'.trim($singmod).'"/>';
		}
	    
		echo $output;
	}
}

// 3. Comment Plugin
add_filter( "comments_template", "facebook_comment_plugin",0 );
function facebook_comment_plugin(){
	return plugin_dir_path( __FILE__ ) . 'comments.php';	
}
