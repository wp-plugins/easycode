<?php
/*
Plugin Name: EasyCode
Plugin URI: ppc.000a.de
Version: 1.2
Author: Bob
Description: A useful tool for programmers' blog. It can help insert programming code in your posts.
*/

if(!class_exists("easycode")){
	class EasyCode {
		var $adminname = 'EasyCodeAdminOption';
		var $pluginname = 'EasyCode';
		var $option;

		function EasyCode(){
			$this->option = $this->getoption();
			load_plugin_textdomain('easycode', '/wp-content/plugins/easycode/lang/');
		}

		function codedisp($code,$title){
			$title = rtrim($title);
			if(empty($html)){
				$option = $this->option;
				$str = '<div class="ecbox_%d" id="codebox"><a id="ec_showcode" class="ec_button" %s href="javascript:;">+</a>%s%s <span class="ectitle">';
				$arr = array((bool) $option['hidden'],
					($option['hidden']==2)?:' onclick="EasyCodeClass.ShowCode(this);"',
					$option['enable_copy']?'<a id="copycode" class="ec_button"  onclick="EasyCodeClass.CopyCode(this);" href="javascript:;">['.__('Copy','easycode').']</a>':'',
					$option['enable_edit']?'<a id="textareaboxcode" class="ec_button" onclick="EasyCodeClass.TextAreaBox(this);" href="javascript:;">['.__('Edit','easycode').']</a>':''
				);
				static $html;
				$html = vsprintf($str,$arr);
			}

			$code = strip_tags($code,'<b><i><em><span><font><del><u><strong>');
			$code = preg_replace("/[\n\r\t]*([^\n\r\t]+)[\n\r\t]*/ism", $option['html_tag_p']?"\n\\1<br>":"\n<li>\\1</li>", $code);
			$code = "\n".$html.$title.'</span><div class="ec_main"><ol>'.$code."</ol></div></div>\n";
			return $code;
		}

		function CodetoHtml($text){
			$option = $this->option;
			if(stripos($text,'[code') && stripos($text,'[/code]')){
				$text = preg_replace("/\s?\[code[ ]*([^\n]*)\](.+?)\[\/code\]\s?/ies", "\$this->codedisp('\\2','\\1')", $text);
				$this->printHTML();
			}
			return $text;
		}

		function printHTML(){
			$option = $this->option;
			if(define('EC_OUTPUT_HTML',true)){//output once
				//css
				echo '<link rel="stylesheet" type="text/css" id="easycodestyle" href="'.get_bloginfo('wpurl').'/wp-content/plugins/easycode/style.css" />';
				$custom = '<style type="text/css">
					.ecbox_0, .ecbox_1 {background: %s; border:%s %s solid; width:%s;}
					 .ectitle {background:%s; color:%s;}
					 .ec_button {color:%s; border-bottom-left-radium:5px;}
					 .ec_button:hover, #ec_showcode {color:%s; background:%s;}
					 .ecbox_1 ol {color:%s}
					 .ecbox_1 ol li:hover{background:%s; color: %s;}
					 .ecbox_1 ol {list-style-type:%s;}
					 .ec_main {overflow:auto; max-height:%s; %s}
				</style>';
				$color = $option['color'];
				$ol_list_style_type_array = array(0=>'none',1=>'decimal',2=>'decimal-leading-zero');
				$match =array($color['bn'],$color['bc'],$color['bb']?'3px':'1px',$option['size']['width']?$option['size']['width']:'',$color['bh'],$color['th'],$color['bc'],$color['bn'],$color['bc'],$color['tn'],$color['bh'],$color['th'],$ol_list_style_type_array[$option['rownum']],$option['size']['height']?$option['size']['height']:'',$option['word_wrap']?'word-wrap:break-word;':'');
				vprintf($custom,$match);

?>
<div id="EasyCodeTextAreaBox" class="ec_textareabox">
	<div class="main">
		<div class="close_b"><a href="javascript:;" style="float:left;" onclick="EasyCodeClass.TextAreaBox();">&nbsp;<?php _e('Edit Code','easycode'); ?></a><span>Press Ctrl+C to copy the following code</span></div>
		<textarea id="textarea_ec" ></textarea>
	</div>
	<div class="bgb" onclick="EasyCodeClass.TextAreaBox();"></div>
</div>
<?php
				//javascript
?>
<script type="text/javascript">
	<!--
	function newEasyCodeClass(codeid){

		this.HtmlTo=function(text,u){
			text = text.replace(/<[^\n<>]+>/igm,'');
			if(!u){
				text = text.replace('&amp;','&');
				text = text.replace('&quot;','"');
				text = text.replace('&lt;','<');
				text = text.replace('&gt;','>');
				text = text.replace('&#039;',"'");
				text = text.replace(/[\r\n\t]+/igm, "\n");
				text = text.replace("\n\n", "\n");
			}
			return text;
		}

		this.CopyCode = function(obj){
			var olobj = obj.parentNode.getElementsByTagName('ol')
			var text = this.HtmlTo(olobj[0].innerHTML);
		    if(navigator.appName.match ( 'Internet Explorer')) {
				if(clipboardData.setData('Text',text)){
					alert( '<?php _e('OK','easycode'); ?>');
				}
			} else {
				this.TextAreaBox(obj);
			}

		}

		this.ShowCode = function(obj){
			var c = obj.parentNode;
			if(c.className == 'ecbox_0'){
				c.className = 'ecbox_1';
			}else{
				c.className = 'ecbox_0';
			}
		}

		this.TextAreaBox=function(obj){
			textareaobj = document.getElementById('EasyCodeTextAreaBox');
			if(obj){
				var olobj = obj.parentNode.getElementsByTagName('ol')
				var text = this.HtmlTo(olobj[0].innerHTML,1);
				textareaobj.style.display = 'block';
				textareaobj.getElementsByTagName('textarea')[0].innerHTML = text;
				textareaobj.getElementsByTagName('textarea')[0].select();
			}else{
				textareaobj.style.display = 'none';
			}
		}
	}

	EasyCodeClass = new newEasyCodeClass();
	//-->
</script>
<?php
			}
		}

		function printAdminPage(){
?>
<div class='wrap'>
<h2>EasyCode Setting</h2>
<?php
			if(isset($_POST['submit'])){
				$option = array();
				$option['hidden'] = intval($_POST['hidden']);
				$option['enable_edit'] = intval($_POST['enable_edit']);
				$option['enable_copy'] = intval($_POST['enable_copy']);
				$option['html_tag_p'] = intval($_POST['html_tag_p']);
				$option['word_wrap'] = intval($_POST['word_wrap']);
				$option['rownum'] = intval($_POST['rownum']);
				$option['color'] = $_POST['defaultcolor']?array('bn'=>'#f7f7f7','bh'=>'#fff','tn'=>'#666','th'=>'#336699','bc'=>'#999','bb'=>0):$_POST['color'];
				$option['size'] = $_POST['size'];
				update_option($this->adminname,$option);
				echo '<div class="updated settings-error"><p>'.__('Settings saved.').'</p></div>';
			}else{
				$option = $this->option;
			}

			$rownumradio = array($option['rownum']=>' checked');
			$hiddenradio = array($option['hidden']=>' checked');
?>

<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<table class="form-table">
<tr valign="top">
<td><?php _e('Collapsing','easycode'); ?></td>
<td>
	<label><input type="radio" name="hidden" value="0" <?php  echo $hiddenradio[0]; ?>><?php _e('Collapsing code','easycode'); ?></label><br>
	<label><input type="radio" name="hidden" value="1" <?php  echo $hiddenradio[1]; ?>><?php _e('Do not collapse, enable collapsing','easycode'); ?></label><br>
	<label><input type="radio" name="hidden" value="2" <?php  echo $hiddenradio[2]; ?>><?php _e('Do not collapse, disable collapsing','easycode'); ?></label><br>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('line numbers','easycode');?></th>
<td>
	<label><input type="radio" name="rownum" value="0" <?php  echo $rownumradio[0]; ?>><?php _e('none','easycode'); ?></label><br>
	<label><input type="radio" name="rownum" value="1" <?php  echo $rownumradio[1]; ?>><?php _e('no leading zeros','easycode'); ?></label><br>
	<label><input type="radio" name="rownum" value="2" <?php  echo $rownumradio[2]; ?>><?php _e('leading zeros','easycode'); ?></label>
</td>
</tr>

<tr valign="top">
<td><?php _e('Others','easycode'); ?></td>
<td>
<label><input type="checkbox" name="html_tag_p" value="1" <?php echo $option['html_tag_p']?'checked="checked"':''; ?>><?php _e('HTML TAG:use &lt;p&gt; instead of &lt;ol&gt;','easycode'); ?></label><br>
<label><input type="checkbox" name="enable_edit" value="1" <?php echo $option['enable_edit']?'checked="checked"':''; ?>><?php _e('Enable editing','easycode'); ?></label><br>
<label><input type="checkbox" name="enable_copy" value="1" <?php echo $option['enable_copy']?'checked="checked"':''; ?>><?php _e('Enable copying','easycode'); ?></label><br>
<label><input type="checkbox" name="word_wrap" value="1" <?php echo $option['word_wrap']?'checked="checked"':''; ?>><?php _e('Word wrap','easycode'); ?></label><br>
</td>
</tr>

<tr><td><h3><?php _e('Appearance'); ?></h3></td><td><?php _e('Normal','easycode');?> / <?php _e('Hover','easycode'); ?> / <input type="checkbox" name="defaultcolor" value="1">default</td></tr>
<tr><td><?php _e('Background');?></td><td><input type="text" name="color[bn]" value="<?php echo $option['color']['bn']; ?>"><input type="text" name="color[bh]" value="<?php echo $option['color']['bh']; ?>"></td></tr>
<tr><td><?php _e('Text'); ?></td><td><input type="text" name="color[tn]" value="<?php echo $option['color']['tn']; ?>"><input type="text" name="color[th]" value="<?php echo $option['color']['th']; ?>"></td></tr>
<tr><td><?php _e('Border'); ?></td><td><input type="text" name="color[bc]" value="<?php echo $option['color']['bc']; ?>"><label> <?php _e('Bold'); ?><input type="checkbox" name="color[bb]" value="1" <?php echo $option['color']['bb']?'checked="checked"':''; ?>></label></td></tr>
<tr><td><?php _e('Width'); ?></td><td><input type="text" name="size[width]" value="<?php echo $option['size']['width']; ?>"> 0 = <?php _e('automatism ','easycode');?></tr>
<tr><td><?php _e('Max Height'); ?></td><td><input type="text" name="size[height]" value="<?php echo $option['size']['height']; ?>"></tr>

</table>

<p class="submit"><input name="submit" class="button-primary" id="submit" type="submit" value="<?php _e('Save Changes'); ?>"/></p>
</form>
</div>

<?php
		}

		function getoption(){
			$option = get_option($this->adminname);
			if(empty($option)){
				$option = array('hidden'=>0,'rownum'=>2,'html_tag_p'=>0,'enable_copying'=>1,'enable_editing'=>1,'word_wrap'=>1,'color'=>array('bn'=>'#f7f7f7','bh'=>'#fff','tn'=>'#666','th'=>'#336699','bc'=>'#999','bb'=>0),'size'=>array('width'=>0,'height'=>0));
				update_option($this->adminname,$option);//default value
			}
			return $option;
		}

		function CreateAdminMenu(){
			add_options_page('EasyCode', 'EasyCode-Setting', 8, basename ( __FILE__ ) , array(&$this , 'printAdminPage'));
		}
	}
}

$ecc=new EasyCode();
add_filter('the_content' , array(&$ecc , 'CodetoHtml' ));
add_action('admin_menu',array(&$ecc , 'CreateAdminMenu'));

?>
