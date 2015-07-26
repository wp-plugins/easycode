<div id="EasyCodeTextAreaBox" class="ec_textareabox">
	<div class="main"><small><?php _e('Press Ctrl+V to copy the following code.','easycode');?></small>
		<textarea id="textarea_ec" onmouseover="this.select();"></textarea>
	</div>
	<div class="bgb" onclick="EasyCodeClass().TextAreaBox(0);" ></div>
</div>
<?php
$option = $this->option;

//common CSS&JS file
echo '<script type="text/javascript" src="'.$this->plugindir.'/easycode.js"></script>';
echo '<script type="text/javascript">EasyCodeGlobal.pluginDir="'.$this->plugindir.'";</script>';
echo '<link rel="stylesheet" type="text/css" id="easycodecss" href="'.$this->plugindir.'/ecbox.css" />';
	$ol_list_style_type_array = array(0=>'none',1=>'decimal',2=>'decimal-leading-zero');


//print Style
if(!$option['use_user_style']){
	if(!in_array($option['default_style'],$slist=$this->get_style_list())) $option['default_style']='Gray';
	echo '<link rel="stylesheet" type="text/css" id="easycodecss_style" href="'.$this->plugindir.'/boxstyles/'.$option['default_style'].'.css" />';
}else{
	$color = $option['color'];
	$match =array(
		$color['bg'],
		$color['border'],
		$option['border-width'],
		$color['text'],
		$color['side'],
		$color['bga'],
		$color['texta'],
	);
$custom = '
<style type="text/css" style=\"display:none;\">
<!--
	.easycodebox {background: #%s !important; border:#%s %opx solid !important;}
		.easycodebox .headbar, .easycodebox .scrollbox .main {color:#%s !important;}
		.easycodebox .scrollbox .ecside {background:#%s !important;}
			.easycodebox ol li:hover{background:#%s !important; color:#%s !important;}
-->
</style>';
	vprintf($custom,$match);
}

//Option CSS NNN
$match =array(
	$option['nowrap']?'nowrap':'normal',
	$ol_list_style_type_array[$option['rownum']],
	);
$custom= "<style type=\"text/css\" style=\"display:none;\">
<!--
.easycodebox ol {white-space:%s !important;list-style-type:%s !important;}
-->
</style>";
vprintf($custom,$match);

?>