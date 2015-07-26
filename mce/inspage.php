<?php
if (!defined('WP_LOAD_PATH')) {
	$classic_root = dirname(__FILE__).'/../../../../';
	if (file_exists($filedir=$classic_root.'wp-load.php')) {
		include_once $filedir;
	}elseif(file_exists('wp-load.php')) {
		include_once 'wp-load.php';
	}else{
		exit("Could not find wp-load.php");
	}
	load_plugin_textdomain('easycode', '/wp-content/plugins/easycode/lang/');
}

if(!(is_user_logged_in() && (current_user_can('edit_posts')||current_user_can('edit_pages')))) exit(':(');

$siteurl = get_bloginfo('wpurl');
$plugindir = $siteurl.'/wp-content/plugins/easycode/';

$option = get_option('EasyCodeAdminOption');

$formatted = $option['formatted'];
$formattedradio = array('pre'=>'','bb'=>'','code'=>'');;
foreach($formattedradio as $tag=>$val){
	if(!$formatted[$tag]) $formattedradio[$tag]=' disabled="disabled"';
}
if($formatted['pre']) $formattedradio['pre'].=' checked="checked"';
elseif($formatted['bb']) $formattedradio['bb'].=' checked="checked"';
elseif($formatted['code']) $formattedradio['code'].=' checked="checked"';
else $formattedradio['pre'].=' checked="checked"';

?>
<!DOCTYPE html>
<head>
<title>[EasyCode] <?php _e('Insert Code','easycode');?></title>
<script language="javascript" type="text/javascript" src="<?php echo $siteurl;?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $siteurl;?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript" src="./inspage.js"></script>
<base target="_self" />
<style type="text/css">
	body {background:#eee; font-size:12px; padding:0;}
	input{border:none; border-bottom:1px gray dashed; font-size:14px;}
	div.input_block {width:100%; background:white; border-radius:5px;margin:5px 0;padding:1px;}
	div.input_line {width:100%;margin:10px 0;}
		div.input_line span {padding:5px;}
	#content textarea {width:100%; height:10em; border:1px gray dashed; font-size:16px; font-family:Monaco,Consolas,"Lucida Console","Courier New";}
	input#submit {background:white;width:50px;height:30px; border:solid 1px gray;}
</style>
</head>
<body onload="init();">
<form name="insert_ec_codebox" action="###">

<div id="tag" class="input_line" onclick="tagRadioChange();">
	<span><?php _e('Tag','easycode');?></span>
	<label><input type="radio" name="tag" id="pre-tag" value="pre"<?php echo $formattedradio['pre'];?>><b>&lt;pre&gt;</b>&nbsp;&nbsp;</label>
	<label><input type="radio" name="tag" id="bb-tag" value="bb"<?php echo $formattedradio['bb'];?>>[code]..[/code]&nbsp;&nbsp;</label>
	<label><input type="radio" name="tag" id="code-tag" value="code"<?php echo $formattedradio['code'];?>>&lt;code&gt;</label>
</div>
<div class="input_block">
	<div class="input_line" id="title">
		<span><?php _e('Title','easycode');?></span><input style="width:250px;" type="text"  id="title-input" name="title">
	</div>
	<div class="input_line" id="lang">
		<span><?php _e('Language','easycode');?></span>
		<select name="lang" id="lang-input">
		<option value="None" selected>None</option>
		</select>
	</div>
</div>
<div class="input_block" id="content">
	<div class="input_line">
		<span><?php _e('Your Code','easycode');?></span><br /><textarea name="content" id="content-input"></textarea>
	</div>
</div>
<p><input id="submit" type="button" value="OK" onclick="insertCodeBox();"/></p>


</form>

</body>
</html>
