<?php
/*
Plugin Name: WP Twitter Pitch
Plugin URI: http://technosailor.com/#
Description: This plugin provides PR companies and users wanting to pitch, a means to give an "elevator" pitch in 140 characters or less.
Author: Aaron Brazell
Version: 1.0b
Author URI: http://technosailor.com
*/

function twitterpitch_config_page() {
   if (function_exists('add_submenu_page')) {
        add_submenu_page('plugins.php',__('Twitter Pitch Configuration'), __('Twitter Pitch Configuration'), 10, basename(__FILE__), 'twitterpitch_conf');
        }
}
add_action('admin_menu', 'twitterpitch_config_page');

function twitterpitch_conf()
{
	if( !$twitterauth = get_option('twitterpitch_credentials') )
	{
		$twitterauth['twitterid'] = '';				# The Twitter ID that the bot uses
		$twitterauth['twitterpass'] = '';
		$twitterauth['twittermessage'] = '';
		$twitterauth['topitch'] = '';
	}
	if ( !empty($_POST ) ) :
	$twitterauth = array(
		'twitterid'		=> attribute_escape( $_POST['twitterid'] ),
		'twitterpass'	=> attribute_escape( $_POST['twitterpass']),
		'twittermessage'=> stripslashes( $_POST['twittermessage']),
		'topitch'		=> attribute_escape( $_POST['topitch'])
		);
		update_option( 'twitterpitch_credentials', $twitterauth );
	?>
	<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
	<?php endif; ?>
	<div class="wrap">
	<h2><?php _e('Twitter Pitch Configuration'); ?></h2>
	<form action="" method="post" id="twiqp-conf" style="margin: auto; width: 400px; ">
	<?php wp_nonce_field('twitterpitch-update-options_twitterauth'); ?>
	
	<h3><label for="twitterid"><?php _e('Twitter ID'); ?></label></h3>
	<p><input id="twitterid" name="twitterid" type="text" size="15" maxlength="15" value="<?php echo $twitterauth['twitterid']; ?>" /></p>

	<h3><label for="twitterpass"><?php _e('Twitter Password'); ?></label></h3>
	<p><input id="twitterpass" name="twitterpass" type="password" size="15" maxlength="15" value="<?php echo $twitterauth['twitterpass']; ?>" /></p>
	
	<h3><label for="twittermessage"><?php _e('Your Message to "Pitchers"'); ?></label></h3>
	<p><textarea rows="6" cols="50" id="twittermessage" name="twittermessage"><?php echo stripslashes( $twitterauth['twittermessage'] ) ?></textarea></p>
	
	<h3><label for="topitch"><?php _e('Twitter ID to Send Pitches To'); ?></label></h3>
	<p><input id="topitch" name="topitch" type="text" size="15" maxlength="15" value="<?php echo $twitterauth['topitch']; ?>" /></p>
	
	
	<p class="submit"><input type="submit" name="submit" value="<?php _e('Update options &raquo;'); ?>" /></p>
	
	</form>
	</div>
	<?php
}

function istwitterdown()
{
	$ch = curl_init('http://twitter.com');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	$http_headers = curl_exec( $ch );
	$http_headers = explode("\n", $http_headers);
	//print_r( explode("\n", $http_headers));
	$status = explode(' ', $http_headers[0]);
	
	if( $status[1] == '200' )
		return true;
	
	return false;
}

function twitterpitch_form( $lightboxlike = true)
{
	$x = rand(1,10);
	$y = rand(1,10);
	$twitterauth = get_option('twitterpitch_credentials');
	?>
	<div id="twitterpitchwrapper">
	<div id="twitterpitchform">
	<h2>Twitter Pitch!</h2>
		<?php 
		echo stripslashes( $twitterauth['twittermessage'] );
		// Twitter is down
		if( !istwitterdown() )
		{
			echo '<p><strong>Sorry, Twitter appears to be down at the moment. Come back later</strong></p>';
		}
		?>
		<form action="<?php echo get_option('siteurl') ?>/wp-content/plugins/wp-twitterpitch/processform.php" method="post" id="tpform">
			<label for="twitterpitch">Pitch (characters remaining: <span class="charsremaining"></span>):</label>
			<textarea id="twitterpitch" name="twitterpitch" cols="50" rows="3"></textarea><br />
			<label for="twitterpitch_captcha">What does <?php echo $x ?> + <?php echo $y ?> equal?</label>
			<input type="hidden" id="twitterpitch_captcha_real" name="twitterpitch_captcha_real" value="<?php echo md5( ($x + $y) ) ?>" />
			<input type="twitterpitch_captcha" name="twitterpitch_captcha" size="3" /><br />
			<input type="submit" name="twitterpitchsubmit" id="twitterpitchsubmit" value="Pitch!" />
		</form>
		<div id="loading"></div>
		<div id="formsubmitresult"></div>
		<div id="twitterpitchclosebar"><p><a href="#" id="twitterpitchclose_link">(X) Close</p></div>
	</div>
	</div>
	<?php
}

function twitterpitch()
{
	echo'<a href="#" id="twitterpitch_link">Twitter Pitch Me!</a>';
}
add_action('wp_footer', 'twitterpitch_form');

function twitterpitch_head()
{
	?>
	<script type="text/javascript">
		$j=jQuery.noConflict();

		$j(document).ready(function(){
			
			$j("#twitterpitch").textlimit('span.charsremaining',140)
			
			$j("#twitterpitch_link").click( function() {
				$j("#twitterpitchwrapper").show();
				$j("html").css("overflow","hidden");
			});
			
			$j("#twitterpitchclose_link").click( function() {
				$j("#twitterpitchwrapper").hide();
				$j("html").css("overflow","auto");
			});
			
			var options = { 
			        target:        '#formsubmitresult', 
			        clearForm: true
			    };
			
			$j('#tpform').submit(function() { 
				$j("#loading").show();
				$j(this).ajaxSubmit(options); 
				$j("#loading").hide();
				return false; 
			});
		});
	</script>
	<style type="text/css">
		#twitterpitchwrapper { position:absolute; top:0; left:0; right:0; bottom:0; width:100%; height:100%; background:url( <?php echo get_option('siteurl') . '/wp-content/plugins/wp-twitterpitch/opaque.png'; ?>); display:none;}
		#twitterpitchform { border:2px solid #000; width: 700px; margin: 0 auto; margin-top:100px; background: #fff; padding:10px;}
			#twitterpitchform textarea {height:100px; }
		.twitterpitcherror { color: #f00; font-weight:bold; }
		.twitterpitchok { color: #6c0; font-weight:bold;}
		.charsremaining { font-weight:bold; }
	</style>
	<?php
}
add_action('wp_head', 'twitterpitch_head');

function twitterpitch_oninit()	
{
	define('TWITTERPITCH_DEBUG', true);
	if( TWITTERPITCH_DEBUG )
		add_action('wp_footer','twitterpitch');
		
	wp_enqueue_script('jquery');
	wp_enqueue_script('jqueryFormPlugin', get_bloginfo( 'siteurl' ) . '/wp-content/plugins/wp-twitterpitch/jqueryFormPlugin.js', array('jquery'));
	wp_enqueue_script('jqueryTextLimit', get_bloginfo('siteurl') . '/wp-content/plugins/wp-twitterpitch/jqueryTextLimit.js', array('jquery'));
}
add_action('init','twitterpitch_oninit');
?>