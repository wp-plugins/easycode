<?php
/*
Plugin Name: EasyCode
Plugin URI: ppc.000a.de
Version: 1.31
Author: Bob
Description: A useful tool for programmers' blog. It can help insert programming code in a better way.
*/

if(!class_exists("easycode")){
	class EasyCode {
		var $adminname = 'EasyCodeAdminOption';
		var $pluginname = 'EasyCode';
		var $plugindir = '';
		var $flodername = 'easycode';
		var $option;
		function EasyCode(){
			$this->option = $this->loadoption();
			$this->flodername = basename(dirname(__FILE__));
			$this->plugindir = get_bloginfo('wpurl')."/wp-content/plugins/$this->flodername";//NOTICE the '/'
			load_plugin_textdomain('easycode', '/wp-content/plugins/$this->flodername/lang/');
		}

		function loadoption(){
			$option = get_option($this->adminname);
			if(empty($option)){
				$option = array(
					'hidecode'=>0,
					'rownum'=>2,
					'html_tag_p'=>0,
					'handle_comments'=>1,
					'edit_code'=>1,
					'nowrap'=>1,
					'tab-size'=>4,
					'size'=>array('width'=>0,'height'=>0),
					'use_custom_color'=>0,
					'system_color'=>'Gray',
					'color'=>array('bg'=>'f7f7f7','bga'=>'fff','text'=>'666','texta'=>'336699','side'=>'999','border'=>'666'),
					'border-width'=>1,
					'formatted'=>array('bb'=>1,'pre'=>1,'code'=>1),
				);
				update_option($this->adminname,$option);//default value
			}
			return $this->option=$option;
		}

		function CommentHandler($text){
			if($this->option['handle_comments']){
				$text = $this->PostHandler($text);
			}
			return $text;
		}

		function PostHandler($text){
			$option = $this->option;
			$formatted = $option['formatted'];
			$parent_this = &$this;

			if($formatted['bb']){
				$text = preg_replace_callback("/\s?\[([^\n]*?)code[ ]*([^\n]*?)\][\s]?(.+?)[\s]?\[\/code\]\s?/is", function($matches) use(&$parent_this){return $parent_this->codedisp($matches[3],'bb',$matches[2],$matches[1]);}, $text,-1,$replace_count_1);
			}
			if($formatted['pre']){
				$text = preg_replace_callback("/\s?<pre class=\"easycode;([^\n]*)\">[\s]?(.+?)[\s]?<\/pre>\s?/is", function($matches) use(&$parent_this){return $parent_this->codedisp($matches[2],'pre',$matches[1]);}, $text,-1,$replace_count_2);
			}
			if($formatted['code']){
				$text = preg_replace_callback("/\s?<code>[\s]?(.+?)[\s]?<\/code>\s?/is", function($matches) use(&$parent_this){return $parent_this->codedisp($matches[1],'code');}, $text,-1,$replace_count_3);
			}

			return $text;
		}

		function codedisp($content,$tag='code',$title='',$lang=''){
			$option = $this->option;

			$content = str_replace("\t",str_repeat('&nbsp;',4),$content);
			switch($tag){
			case 'pre':
				$classname = explode(';',ltrim($title));
				foreach($classname as $value){
					if(empty($value)) continue;
					$ex= explode(':',$value);
					$info[$ex[0]]=$ex[1];
				}
				$title = $info['title'];
				$lang=$info['lang'];
				break;
			case 'bb':
				//$title = ltrim($title);
				//$lang = ltrim($lang);
				break;
			case 'code':
			default:
			}

			return $this->print_codebox($content,ltrim($title),ltrim($lang));
		}


		function print_codebox($content,$title='',$lang=''){
			$option = $this->option;
			if(is_admin()){//Some common words.Simple Box.
				$box = "<div class=\"easycodebox_simple\">
				<div class=\"title\">[<i>{$lang}</i>]|<b>{$title}</b></div>
				<div class=\"main\">{$content}</div>
				</div>";
				$this->printsimpleCSS();
			}elseif(is_feed()){
				$box = "<span>[<i>{$lang}</i>]<b>{$title}</b></span><code>{$content}</code>";
			}else{
				$guideword = __('Click here to show/hide the code.','easycode');
				$HTML_tag_allowed = '<b><i><em><del><u><strong><strike>';
				$main = preg_replace_callback("/[\n\r]*([^\n\r]+)[\n\r]*/ism", function($matches){return "\n<li>".strip_tags ($matches[1],$HTML_tag_allowed)."</li>";}, ltrim($content));
				$title = strip_tags ($title,$HTML_tag_allowed.'<br><br/><br />');
				if(!$title){
					$title = "<span class='small'>$guideword</span>";
				}else{
					$gui=' title="'.$guideword.'"';
				}
				//output CodeBoxID
				static $boxid=0;
				$boxid++;
				echo "<script>ecbox_number=$boxid;</script>";

				$op[0]=$option['hidecode']?'hidecode':'';
				$op[1]=$option['edit_code']?' ondblclick="EasyCodeClass(this).editCode(1);"':'';
				$box = "<div class=\"easycodebox $op[0] $lang\" id=\"easycodebox-$boxid\">
				<div class=\"headbar\" onclick=\"EasyCodeClass(this).ShowCode();\"$gui>$title</div>
				<div class=\"scrollbox\" $op[1]>
				<table border='0' cellpadding='0' cellspacing='0'>
					<tr>
						<td class=\"ecside\">&nbsp;</td>
						<td class=\"main\"><ol>{$main}</ol></td>
					</tr>
				</table></div></div>";

				$this->printCSS();
			}
			return $box;
		}

		function printCSS(){
			$option = $this->option;
			include_once 'printcss.php';
		}

		function printsimpleCSS(){
			$option = $this->option;
			include_once 'printsimplecss.php';
		}

		function printAdminPage(){
			include_once 'adminpage.php';
		}


		function get_style_list(){
			$flist = scandir(dirname(__FILE__).'/boxstyles');
			$slist =array();
			foreach($flist as $fname){
				if(substr($fname,-4,4)=='.css'){
					$slist[]=substr($fname,0,-4);
				}
			}
			return $slist;
		}

		/*function get_style_color($sname='',$index=0){
			if(empty($sname)) return $this->get_style_list();

			$colorrefer = array('bg','bga','text','texta','side','border');
			$color=array();
			$content = file($sname.'.s',FILE_IGNORE_NEW_LINES+FILE_SKIP_EMPTY_LINES);
			foreach($content as $key=>$value){
					$color[$colorrefer[$key]]=$content[$key]=$this->color_handler($value);

			}
			return $index?$color+$content:$color;
		}*/


		function color_handler($color){
			$strhandler=function($cstr){
				$cstr = preg_replace('/[^0-9A-F]/S','',strtoupper($cstr));
				$len = strlen($cstr);
				if($len>=6){
					$cstr=substr($cstr,0,6);
				}else if($len<6 && $len>=3){
					$cstr=substr($cstr,0,3);
				}else if($len<3 && $len>0){
					$fc=substr($cstr,0,1);
					$cstr=$fc.$fc.$fc;
				}else{
					$cstr='fff';
				}
				return $cstr;
			};

			if(is_array($color)){
				foreach($color as $key=>$value){
					$color[$key]=$strhandler($value);
				}
			}else{
				$color=$strhandler($color);
			}
			return $color;
		}


		function CreateAdminMenu(){
			add_options_page('EasyCode', 'EasyCode-Setting', 8, basename ( __FILE__ ) , array(&$this , 'printAdminPage'));
		}


		//TinyMCE
		function mce_add() {
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
			// Add only in Rich Editor mode
			if ( get_user_option('rich_editing') == 'true') {
				add_filter('mce_css',array($this,'mce_add_css'));
				add_filter("mce_external_plugins", array($this,'mce_add_plugin'));
				add_filter('mce_buttons', array($this,'mce_register_button'));
			}
		}
		function mce_add_css($csslist){
			if(!empty($csslist)) $csslist.=',';
			$csslist.=$this->plugindir.'/mce/mce_add_style.css';
			return $csslist;
		}
		function mce_register_button($buttons) {//Load the button
			$buttons[] = "easycode_box";
			return $buttons;
		}
		function mce_add_plugin($plugin_array) {// Load the TinyMCE plugin : editor_plugin.js
			$plugin_array['easycode_box'] = $this->plugindir.'/mce/editor_plugin.js';
			return $plugin_array;
		}

		//Button For Comments
		function add_comment_button(){
			if($this->option['handle_comments']){
				include 'mce/comment_button.php';
			}
		}
		function plugin_action_links($links, $file) {
			if ( $file == $this->flodername.'/easycode.php' ) {
				$links[] = '<a href="options-general.php?page=easycode">'.__('Settings').'</a>';
			}
			return $links;
		}


	}
}

$ecc=new EasyCode();
add_filter('the_content' , array($ecc,'PostHandler' ));
add_filter('get_comment_text' , array($ecc,'CommentHandler' ));
add_action('admin_menu',array($ecc,'CreateAdminMenu'));
add_filter( 'plugin_action_links', array($ecc,'plugin_action_links'), 10, 2 );

add_action('init', array($ecc,'mce_add'));
add_action('comment_form_after_fields',array($ecc,'add_comment_button'));
add_action('comment_form_logged_in_after',array($ecc,'add_comment_button'));

?>