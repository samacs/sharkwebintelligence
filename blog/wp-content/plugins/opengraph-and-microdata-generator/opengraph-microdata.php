<?php
/*
Plugin Name: Opengraph and Microdata Generator
Plugin URI: http://www.itsabhik.com/wp-plugins/opengraph-microdata-generator.html
Description: Adds Facebook OpenGraph and Schema.Org compatible microdata at <head> section to help search engines to show rich snippet and index your blog far more better.
Version: 2.2
Author: Abhik
Author URI: http://www.itsabhik.com/
License: GPL3
*/

// add the admin options page
function ogmd_page () {
    if (function_exists ('add_submenu_page'))
        add_submenu_page ('options-general.php', __('Opengraph and Microdata Generator Settings'), __('OpenGraph/MicroData'),
            'manage_options', 'ogmd-settings', 'wpogmc_options_page');
}

function wpogmc_admin_init(){
register_setting( 'wpogmc_settings', 'wpogmcappid' );
register_setting( 'wpogmc_settings', 'wpogmcthumbnail' );
register_setting( 'wpogmc_settings', 'wpogmclocale');
register_setting( 'wpogmc_settings', 'wpogmcwordlimit');
}
add_action ('admin_menu', 'ogmd_page');
add_action('admin_init', 'wpogmc_admin_init');
add_option('wpogmclocale', 'en_US');
add_option('wpogmcwordlimit', '25');

function wpogmc_options_page() {
?>

<div class="wrap">
<div style="float:left;width:600px;margin:0 20px 0 10px;">
<img src="<?php echo WP_PLUGIN_URL; ?>/opengraph-and-microdata-generator/images/logo.png" border="0">
<div class="postbox">
<h3 class="hndle" style="padding:5px 0 5px 10px;"><span>OpenGraph Settings</span></h3>
<form method="post" action="options.php">
    <?php settings_fields( 'wpogmc_settings' ); ?>
    <?php do_settings_sections( 'wpogmc_settings' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Facebook App ID/Admin ID:</th>
        <td><input type="text" name="wpogmcappid" size="30" value="<?php echo get_option('wpogmcappid'); ?>" /></td>
		</tr>
         
        <tr valign="top">
        <th scope="row">Default Thumbnail URL:<br/><span style="font-size:11px;font-style:italic;">(Will be used if no image is found)</span></th>
        <td><input type="text" name="wpogmcthumbnail" size="70" value="<?php echo get_option('wpogmcthumbnail'); ?>" /></td>
        </tr>
		
        <tr valign="top">
        <th scope="row">Blog Language</th>
        <td>
				<select name="wpogmclocale" value="<?php echo get_option('wpogmclocale'); ?>">
					<option value="<?php echo get_option('wpogmclocale'); ?>" selected="selected" ><?php echo get_option('wpogmclocale'); ?></option>
					<option value="af_ZA">Afrikaans</option>
					<option value="ar_AR">Arabic</option>
					<option value="az_AZ">Azeri</option>
					<option value="be_BY">Belarusian</option>
					<option value="bg_BG">Bulgarian</option>
					<option value="bn_IN">Bengali</option>
					<option value="bs_BA">Bosnian</option>
					<option value="ca_ES">Catalan</option>
					<option value="cs_CZ">Czech</option>
					<option value="cy_GB">Welsh</option>
					<option value="da_DK">Danish</option>
					<option value="de_DE">German</option>
					<option value="el_GR">Greek</option>
					<option value="en_GB">English (UK)</option>
					<option value="en_PI">English (Pirate)</option>
					<option value="en_UD">English (Upside Down)</option>
					<option value="en_US">English (US)</option>
					<option value="eo_EO">Esperanto</option>
					<option value="es_ES">Spanish (Spain)</option>
					<option value="es_LA">Spanish</option>
					<option value="et_EE">Estonian</option>
					<option value="eu_ES">Basque</option>
					<option value="fa_IR">Persian</option>
					<option value="fb_LT">Leet Speak</option>
					<option value="fi_FI">Finnish</option>
					<option value="fo_FO">Faroese</option>
					<option value="fr_CA">French (Canada)</option>
					<option value="fr_FR">French (France)</option>
					<option value="fy_NL">Frisian</option>
					<option value="ga_IE">Irish</option>
					<option value="gl_ES">Galician</option>
					<option value="he_IL">Hebrew</option>
					<option value="hi_IN">Hindi</option>
					<option value="hr_HR">Croatian</option>
					<option value="hu_HU">Hungarian</option>
					<option value="hy_AM">Armenian</option>
					<option value="id_ID">Indonesian</option>
					<option value="is_IS">Icelandic</option>
					<option value="it_IT">Italian</option>
					<option value="ja_JP">Japanese</option>
					<option value="ka_GE">Georgian</option>
					<option value="km_KH">Khmer</option>
					<option value="ko_KR">Korean</option>
					<option value="ku_TR">Kurdish</option>
					<option value="la_VA">Latin</option>
					<option value="lt_LT">Lithuanian</option>
					<option value="lv_LV">Latvian</option>
					<option value="mk_MK">Macedonian</option>
					<option value="ml_IN">Malayalam</option>
					<option value="ms_MY">Malay</option>
					<option value="nb_NO">Norwegian (bokmal)</option>
					<option value="ne_NP">Nepali</option>
					<option value="nl_NL">Dutch</option>
					<option value="nn_NO">Norwegian (nynorsk)</option>
					<option value="pa_IN">Punjabi</option>
					<option value="pl_PL">Polish</option>
					<option value="ps_AF">Pashto</option>
					<option value="pt_BR">Portuguese (Brazil)</option>
					<option value="pt_PT">Portuguese (Portugal)</option>
					<option value="ro_RO">Romanian</option>
					<option value="ru_RU">Russian</option>
					<option value="sk_SK">Slovak</option>
					<option value="sl_SI">Slovenian</option>
					<option value="sq_AL">Albanian</option>
					<option value="sr_RS">Serbian</option>
					<option value="sv_SE">Swedish</option>
					<option value="sw_KE">Swahili</option>
					<option value="ta_IN">Tamil</option>
					<option value="te_IN">Telugu</option>
					<option value="th_TH">Thai</option>
					<option value="tl_PH">Filipino</option>
					<option value="tr_TR">Turkish</option>
					<option value="uk_UA">Ukrainian</option>
					<option value="vi_VN">Vietnamese</option>
					<option value="zh_CN">Simplified Chinese (China)</option>
					<option value="zh_HK">Traditional Chinese (Hong Kong)</option>
					<option value="zh_TW">Traditional Chinese (Taiwan)</option>
				</select>
		</td>
        </tr>
		
        <tr valign="top">
        <th scope="row">Description Word Limit:<br/><span style="font-size:11px;font-style:italic;">(Number of Words you want as description)</span></th>
        <td><input type="text" name="wpogmcwordlimit" size="2" value="<?php echo get_option('wpogmcwordlimit'); ?>" /></td>
        </tr>

    <tr valign="top">
        <th scope="row"></th>
    <td><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></td>
	</tr>
		<tr valign="top">
		<th scope="row"></th>
		<td style="font-size:11px;font-style:italic;">Know your AppID/AdminID <a href="http://graph.facebook.com/YourUsername" rel="nofollow" target="_blank">here</a>. Replace <strong>YourUsername</strong> with Your Username</td>
		</tr>
</table>
</form>
</div>
<div class="postbox">
	<h3 class="hndle" style="padding:5px 0 0px 10px;"><span>My Other Free Plugins</span></h3>
		<p style="padding-left:10px"><a title="Gravatar Hovercard" target="_blank" href="http://www.itsabhik.com/wp-plugins/gravatar-hovercards-wordpress-plugin.html"><strong>Gravatar Hovercards</strong></a> : Fetches the gravatar profile on hovering mouse over any avatar image from Gravatar.<br/><br/>
		<a title="Advanced Author Bio" target="_blank" href="http://www.itsabhik.com/wp-plugins/gravatar-hovercards-wordpress-plugin.html"><strong>Advanced Author Bio</strong></a> : Shows a author bio box after every blog post with some advanced options.<br/><br/>
		<a title="WordPress Server Load" target="_blank" href="http://www.itsabhik.com/wp-plugins/wordpress-server-load-plugin.html"><strong>WordPress Server Load</strong></a> : Shows the load averages of the server in a widget at admin dashboard.</p>
</div>
</div>
<div class="clear"></div>
</div>

<?php
}
// Let's get the image part first.
function iafbschema_image()
{
    $Html = get_the_content();
    $extrae = '/<img .*src=["\']([^ ^"^\']*)["\']/';
		preg_match_all( $extrae  , $Html , $matches );
	$image = $matches[1][0];

    if($image)
    {
		$pos = strpos($image, site_url());
		if ($pos === false) {
			return $_SERVER['HTTP_HOST'].$image;
		} else {
			return $image;
		}
    } else {
		return get_option('wpogmcthumbnail');
    }
}
// Let's limit the word count in description
function wordlength() {
	$theContent = trim(strip_tags(get_the_content()));
		$output = str_replace( '"', '', $theContent);
		$output = str_replace( '\r\n', ' ', $output);
		$output = str_replace( '\n', ' ', $output);
			$limit = get_option('wpogmcwordlimit');
			$content = explode(' ', $output, $limit);
			array_pop($content);
		$content = implode(" ",$content)."...";
	return $content;
}
// Then the required fields for Facebook OPengraph and Schema Microdata.
function iafbschema() {
	if(is_single() ){
		if (have_posts()) : while (have_posts()) : the_post(); 
			$iafbschemameta[title]=get_the_title($post->post_title);
			$iafbschemameta[permalink]=get_permalink();
			$iafbschemameta[image]=iafbschema_image();
			$iafbschemameta[blogname]=get_option('blogname');
			$iafbschemameta[description]= wordlength();
			$iafbschemameta[language]=get_option('wpogmclocale');
			$iafbschemameta[appid]=get_option('wpogmcappid');
			endwhile; endif; 
		}else{
			$iafbschemameta[blogname]=get_option('blogname');
			$iafbschemameta[permalink]=get_option('siteurl');
			$iafbschemameta[image]=iafbschema_image();
			$iafbschemameta[title]=get_option('blogname');
			$iafbschemameta[description]=get_option('blogdescription');
			$iafbschemameta[language]=get_option('wpogmclocale');
			$iafbschemameta[appid]=get_option('wpogmcappid');
	}
	
	echo metas($iafbschemameta);
}
//Lastly, put them togather and add to <head> section.
function metas($iafbschemameta){
	$iametainfo.="\n";
	$iametainfo.="<!-- ItsAbhik.com Facebook OpenGraph and Schema Microdata Generator Start -->";
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:title" content="'.$iafbschemameta[title].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:type" content="article" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:url" content="'.$iafbschemameta[permalink].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:image" content="'.$iafbschemameta[image].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:site_name" content="'.$iafbschemameta[blogname].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:description" content="'.$iafbschemameta[description].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="og:locale" content="'.$iafbschemameta[language].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta property="fb:admins" content="'.$iafbschemameta[appid].'" />';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="name" content="'.$iafbschemameta[title].'">';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="description" content="'.$iafbschemameta[description].'">';
	$iametainfo.="\n";
	$iametainfo.='<meta itemprop="url" content="'.$iafbschemameta[permalink].'">';
	$iametainfo.="\n";
	$iametainfo.='<!-- ItsAbhik.com Facebook OpenGraph and Schema Microdata Generator End -->';
	$iametainfo.="\n";
	return $iametainfo;
}
add_action('wp_head', 'iafbschema');
?>