<?php
/*
Plugin Name: Wordpress Tiniest Super Cache
Plugin URI: http://ahlul.web.id/2011/11/06/wordpress-tiniest-super-cache.html
Description: Wordpress tiniest cache plugin ever with just 2KB engine. Boost your page load time 10000 times faster, and save lots of your memory.
Version: 0.9.9
Author: Ahlul Faradish Resha, S.Si
Author URI: http://ahlul.web.id/about
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/*
Release Notes:
0.9.0 - First Beta Release
0.9.1 - Fix bug of blank page that cause by empty cached file. Now it delete automatically if engine read empty file.
*/
?>
<?php
add_action('admin_menu', 'wtsc_menu');
add_action('admin_notices', 'wtsc_notice' );
function wtsc_notice() {
	$wtsc_status = get_option('wtsc_status');
	if(empty($wtsc_status)) {
	echo "<div class='update-nag'><span style='color:red;'><strong>WORDPRESS TINIEST SUPER CACHE</strong> not active yet</span>. <a href=\"options-general.php?page=wtsc-options&enable=1\">Click here</a> to activate OR <a href=\"options-general.php?page=wtsc-options\">Click here</a> to setup configurations.</div>";
	}
}
function wtsc_menu() {
	add_options_page('Tiniest Super Cache', 'Tiniest Super Cache', 'manage_options', 'wtsc-options', 'wtsc_options');
}

function wtsc_options() {
	
	if($_POST['wtsc']) {
		if(empty($_POST['wtsc']['ignorehome'])) $_POST['wtsc']['ignorehome'] = -1;
		if(empty($_POST['wtsc']['ignorenonsingle'])) $_POST['wtsc']['ignorenonsingle'] = -1;
		
		$_POST['wtsc']['cachedir'] = stripslashes($_POST['wtsc']['cachedir']);
		
		add_option( 'wtsc', $_POST['wtsc']) or update_option( 'wtsc', $_POST['wtsc']);
		@file_put_contents(dirname(__FILE__)."/wptsc-ignore",$_POST['wtsc']['ignoreurl']);
		@file_put_contents(dirname(__FILE__)."/wptsc-hardcache",$_POST['wtsc']['hardcache']);


@mkdir($_POST['wtsc']['cachedir'],0777,true);
$cd = (is_dir($_POST['wtsc']['cachedir']))?$_POST['wtsc']['cachedir']:"wptsc-cachedir";
$ct = (is_numeric($_POST['wtsc']['cachetime']))?$_POST['wtsc']['cachetime']:3600;
$var = '<?php
$cache_dir = "'.$cd.'";
$cache_time = "'.$ct.'";
$ignore_get = '.intval($_POST['wtsc']['ignore_get']).';
$ignore_post = '.intval($_POST['wtsc']['ignore_post']).';
?>';
@file_put_contents(dirname(__FILE__)."/wptsc-var.php",$var);
		
		echo '<div class="message updated"><p>Options is saved</p></div>';
	}
	
	$wtsc_status = get_option('wtsc_status');
	if($_GET['enable'] and $wtsc_status != 'enable') wptsc_enable();
	if($_GET['disable'] and $wtsc_status == 'enable') wptsc_disable();
	
	$wtsc = get_option('wtsc');
	$wtsc_status = get_option('wtsc_status');
	?>
    <div class="wrap">  
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Wordpress Tiniest Super Cache</h2><br />
<?php
if($_GET['flushall']) {
	$tdir = dirname(__FILE__)."/wptsc-cachedir";
	$cache_dir = ($wtsc['cachedir'])?$wtsc['cachedir']:$tdir;
	foreach(glob($cache_dir.'/*') as $v){
	 @unlink($v);
	}
	@file_put_contents($cache_dir."/index.html","");
	echo '<div class="message updated"><p>All cached files is flushed.</p></div>';
}
?>
<?php
	if(!function_exists('file_get_contents') or !function_exists('file_put_contents')):
?>
<div class="message updated"><p>We can't find function <strong>file_get_contents</strong> and <strong>file_put_contents</strong> on your server. It needed.</p></div>
<?php exit; endif; ?>
<p>
<?php if($wtsc_status): ?>
This Plugin currently enabled, <a href="options-general.php?page=wtsc-options&disable=1">Click here</a> to disable. 
<?php else: ?>
This Plugin currently disabled, <a href="options-general.php?page=wtsc-options&enable=1">Click here</a> to enable. 
<?php endif; ?>
 <span style="color:red;">(PLEASE DO NOT EDIT YOUR WP-CONFIG.PHP BEFORE THIS PROCESS COMPLETED)</span>
</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="ahlul_amc@yahoo.co.id">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="Ahlul Faradish Resha">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">
<h2 class="nav-tab-wrapper"><a href="#" rel="settings" class="nav-tab nav-tab-active">Global Settings</a> <a href="#" rel="faq" class="nav-tab">FAQ</a> <a href="options-general.php?page=wtsc-options&flushall=1" class="nav-tab"><span style="color:red;">FLUSH ALL CACHE</span></a> &rarr; <span style="position:relative"> DONATE For this Plugin <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" align="absmiddle"></span></h2>
</form>

    <form action="options-general.php?page=wtsc-options" method="post">
    <div id="faq" style="display:none">
        <p><strong>Are you sure this plugin can boost my page load  time 10000 times faster?</strong></p>
        <p>Yes, it make sense.. why? because this plugin save loaded page as static files. Then when the page is requested again this plugin will read directly the cached file.</p>
        <p>This plugin will save lots of your memory, because if cached file is found this plugin will cut all wordpress process from the top. So as we know if you just hook wordpress process in middle (as others do) it will not work, because almost of wordpress process like database query performed before template is loaded.    </p>
        <p><strong>Will this plugin broke wordpress process?</strong></p>
        <p>This plugin will cache every page that process by server, that mean if you have script that process by browser ie: javascript, will not cache by this plugin. So this will not break your theme.</p>
        <p><strong>Will wp-admin cached by this plugin too?</strong></p>
        <p>No, this plugin will ignore all url that contain &quot;wp-&quot;.</p>
    <p><strong>How the cached file flushed?</strong></p>
        <p>This plugin will flush or will not use cached file if it receive GET or POST request. And can be flush too from this panel.</p>
        <p><strong>Will this plugin return 404 status for 404 page?</strong></p>
        <p>Hmm, since this plugin still at beta version I don't have much time to develop this plugin. So currently it will return status 200 (OK) for all cached url. But don't worry I'll upgrade this feature next time.</p>
        <p style="color:red;">ATTENTION!! Using this plugin at your own risk.</p>
    </div>
    <div id="settings">
    <h3>Cache Directory:</h3>
    <p>
      <input type="text" size="80" name="wtsc[cachedir]" value="<?php echo ($wtsc['cachedir'])?$wtsc['cachedir']:dirname(__FILE__)."/wptsc-cachedir"; ?>" />
    </p>
    <h3>Cache Time:</h3>
    <p>How long you want to hold cached file? (in second): 
      <input type="text" size="4" name="wtsc[cachetime]" value="<?php echo ($wtsc['cachetime'])?$wtsc['cachetime']:3600; ?>" />
    </p>
<h3>Do this when:</h3>
<ul>
  <li>Add/Edit Post or Page or Custom Post</li>
  <li>Edit/Insert Comment</li>
</ul>
    <p>
      <label><input type="radio" name="wtsc[flushonchange]" value="single" <?php echo (empty($wtsc['flushonchange']) or $wtsc['flushonchange'] == 'single')?'checked':''; ?>/> 
        Flush cached file related to post/page</label>
      .
    </p>
    
    <p> <label><input type="radio" name="wtsc[flushonchange]" value="all" <?php echo ($wtsc['flushonchange'] == 'all')?'checked':''; ?>/> 
    Flush all cached files.</label></p>
    <p>Keep cached page when receive:</p>
    <p><label><input type="checkbox" value="1" name="wtsc[ignore_get]" <?php echo ($wtsc['ignore_get'] == '1')?'checked':''; ?>/> GET</label></p>
    <p><label><input type="checkbox" value="1" name="wtsc[ignore_post]" <?php echo ($wtsc['ignore_post'] == '1')?'checked':''; ?>/> POST</label></p>
    <!--
    <h3>Ignore Below Page:</h3>
    <p>
      <label>
        <input type="checkbox" name="wtsc[ignorehome]" value="ignorehome" <?php echo ($wtsc['ignorehome'] == 'ignorehome' or empty($wtsc['ignorehome']))?'checked':''; ?> />
        Homepage</label>
.</p>
    <p>
      <label>
        <input type="checkbox" name="wtsc[ignorenonsingle]" value="ignorenonsingle" <?php echo ($wtsc['ignorenonsingle'] == 'ignorenonsingle' or empty($wtsc['ignorenonsingle']))?'checked':''; ?> />
        Non single or non page url(ie:Archive,Search)</label>
    </p>-->
<h3>Ignore Specific Url:</h3>
<p>Write on below textarea all url that you want to ignore by this plugin. One url per line.</p>
    <textarea cols="80" rows="10" name="wtsc[ignoreurl]"><?php echo htmlspecialchars(stripslashes($wtsc['ignoreurl'])); ?></textarea>
    
<h3>Hard Cache Url:</h3>
<p>This plugin will flush a cached page when it receive POST or GET. Use below textarea to list all page that will not flushed even receive POST or GET</p>
    <textarea cols="80" rows="10" name="wtsc[hardcache]"><?php echo htmlspecialchars(stripslashes($wtsc['hardcache'])); ?></textarea>
    
    <p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
    
    </div>
    </form>
    
<script>
jQuery(document).ready(function(){
	jQuery('.nav-tab').click(function() {
		var cdiv = jQuery(this).attr("rel");
		if(!cdiv) {
			return true;
		}
		jQuery('.nav-tab').each(function(index) {
			var r = jQuery(this).attr("rel");
			if(cdiv == r) {
				jQuery(this).addClass('nav-tab-active');
				jQuery("#"+r).show();
			} else {
				jQuery(this).removeClass('nav-tab-active');
				jQuery("#"+r).hide();
			}
		});
		return false;
	});
});
</script>
<div class="message updated"><p>For Bugs & Feature request post your comment at <a href="http://ahlul.web.id/2011/11/06/wordpress-tiniest-super-cache.html">http://ahlul.web.id/2011/11/06/wordpress-tiniest-super-cache.html</a></p></div>
    </div>
    <?php
}

add_action( 'save_post', 'wptsc_post_save' );
function wptsc_post_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;  
	$permalink = get_permalink( $post_id );	
	wptsc_flush(home_url('/')); wptsc_flush(home_url(''));
	wptsc_flush($permalink);
}

function wptsc_flush($url) {
	$tdir = dirname(__FILE__)."/wptsc-cachedir";
	$wtsc = get_option('wtsc');
	$cache_dir = ($wtsc['cachedir'])?$wtsc['cachedir']:$tdir;
	if($wtsc['flushonchange'] != 'all') {
		@unlink($cache_dir."/".md5($url));
	} else {
		foreach(glob($cache_dir.'/*') as $v){
   		 @unlink($v);
		}
		@file_put_contents($cache_dir."/index.html","");
	}
}

register_deactivation_hook( __FILE__, 'wptsc_disable' );
function wptsc_disable() {
	$gc = @file_get_contents(ABSPATH."/wp-config.php");
	$egc = explode('//wptsc-start-do-not-edit',$gc);
	$egc2 = explode('//wptsc-end-do-not-edit',$egc[1]);
	$iconf = $egc[0].$egc2[1];
	@file_put_contents(ABSPATH."/wp-config.php",$iconf);	
	add_option( 'wtsc_status', '') or update_option( 'wtsc_status', '');
}
function wptsc_enable() {
	copy(ABSPATH."/wp-config.php",ABSPATH."/wp-config.bk.".time().".php");
	$gc = @file_get_contents(ABSPATH."/wp-config.php");
	$egc = explode('<?php',$gc);
$iconf = '<?php
//wptsc-start-do-not-edit
@include("'.dirname(__FILE__)."/wptsc-var.php".'");
@include("'.dirname(__FILE__)."/wptsc-engine.php".'");
//wptsc-end-do-not-edit
'.$egc[1];
	@file_put_contents(ABSPATH."/wp-config.php",$iconf);
	add_option( 'wtsc_status', 'enable') or update_option( 'wtsc_status', 'enable');
}
?>