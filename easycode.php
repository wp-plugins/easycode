<?php
/*
Plugin Name: EasyCode
Plugin URI: URI
Version: 1.0
Author: Bob
Description: EasyCode
*/

load_plugin_textdomain('easycode', 'wp-content/plugins/easycode/lang');

if(!class_exists("easycode")){
	class EasyCode {
		var $adminname = 'EasyCodeAdminOption';
		var $pluginname = 'EasyCode';
		var $option = '';

		function EasyCode(){
			$this->option = $this->getoption();
			load_plugin_textdomain('easycode', '/wp-content/plugins/easycode/lang/');
		}

		function getreplacestr($codeid){
			$option = $this->option;
			if($option['hiden']==2) $hiden_op_ac = '_'; $option['hiden'] = 0;
			return $replacestr = '<div class="ecodebox" id="codebox_'."$codeid".'" ondblclick="EasyCodeClass.ChageListStyle(this.id,event)">
						<a id="showcode_'.$codeid.'" class="showcode_'.$option['hiden'].'" onclick'.$hiden_op_ac.'="EasyCodeClass.ShowCode(this.id,EasyCodeClass.gethsb(this.id));" href="javascript:;" title'.$hiden_op_ac.'="'.translate('click to collapse','easycode').'">	</a>
						<a id="copycode_'.$codeid.'" class="copycode" onclick="EasyCodeClass.CopyCode(this.id);" href="javascript:;">['.translate('COPY','easycode').']</a>
						<span class="infobar" id="ecinfobar_'.$codeid.'">'.translate(' - Please Click','easycode').'</span>';
		}

		function CodetoHtml($text){
			$option = $this->option;
			$oparr = array(1,2,1);

			if($pregcount = preg_match_all('#\[code\]#im',$text,$arr)){
				for($i = 1;$i<=$pregcount;$i++){
					$replacestr = $this->getreplacestr($codeid = $this->getcodeid());
					$text = preg_replace('#[<(/|)p(/|)>]*[<(/|)br(/|)>]*\[code\][<(/|)p(/|)>]*[<(/|)br(/|)>]*#im',$replacestr,$text,1);
				}
				$text=preg_replace('#[<(/|)p(/|)>]*[<(/|)br(/|)>]*\[/code\]#im','</div>',$text);
				$sas = true;
			}

			if($pregcount = preg_match_all('#<ol class="easycode">#im',$text,$arr)){
				for($i = 1;$i<=$pregcount;$i++){
					$replacestr = $this->getreplacestr($codeid = $this->getcodeid());
					$text = preg_replace('#<ol class="easycode">([^«]+)</ol>#im',$replacestr.'<ol>\1</ol></div>',$text,1);
				}
				$sas = true;
			}

			if($sas){
				$this->linkcssfile();
				$this->linkcssfile($option['style']);
				$ol_list_style_type_array = array(0=>'none',1=>'decimal',2=>'decimal-leading-zero');
?>
<style type="text/css">
	.ecodebox{overflow:hidden; height:<?php if($option['hiden']==1) echo '20px;';?>}
		.ecodebox ol {list-style-type:<?php echo $ol_list_style_type_array[$option['rownum']];?>; font-family:Monaco,Consolas,"Lucida Console","Courier New";}
</style>
<script type="text/javascript">
	<!--
	function newEasyCodeClass(codeid){
		this.hsb = new Array();
		this.liststyle = new Array('none','decimal','decimal-leading-zero');

		this.getcodeid = function(id){
			id = id.replace(/copycode_/,'');
			id = id.replace(/showcode_/,'');
			return id;
		}

		this.gethsb = function(id){
			id = this.getcodeid(id);
			if(!this.hsb[id]){
				this.hsb[id] = <?php echo $oparr[$option['hiden']]; ?>;
			}
			return this.hsb[id];
		}

		this.CopyCode = function(id){
			idi = id.replace(/copycode_/,'ecinfobar_');
			id = id.replace(/copycode_/,'codebox_');
			var TagName = document.getElementById(id).getElementsByTagName('ol');
			var text = TagName[0].innerHTML;
			text = text.replace(/<[a-z|/]+>/igm,'');
			text = text.replace(/&amp;/gm,'&');
			text = text.replace(/&quot;/gm,'"');
			text = text.replace(/&lt;/gm,'<');
			text = text.replace(/&gt;/gm,'>');

			if(clipboardData.setData('Text',text)){
				document.getElementById(idi).innerHTML = '<?php _e('Done','easycode'); ?>';
			}else{
				document.getElementById(idi).innerHTML = '<?php _e('Fail','easycode'); ?>';
			}
		}

		this.ShowCode = function(id,op){
			id = this.getcodeid(id);
			var a = document.getElementById('showcode_'+id);
			var c = document.getElementById('codebox_'+id);
			if(op!=1 && op!=2){
				if(!c.style.height || c.style.height==='auto'){
					var op = 1;
				}else{
					var op = 2;
				}
			}
			if(op==1){
				c.style.height = '20px';
				a.className = 'showcode_1';
				this.hsb[id] = 2;
			}else{
				c.style.height = 'auto';
				a.className = 'showcode_0'
				this.hsb[id] = 1;
			}
		}

		this.ChageListStyle = function(id){
			var a = document.getElementById(id).getElementsByTagName('ol');
			switch (a[0].style.listStyleType){
			case 'none':
			case '':
			case undefined:
				lst = 'decimal';
			break
			case 'decimal':
				lst = 'decimal-leading-zero';
			break
			case 'decimal-leading-zero':
				lst = 'none';
			break
			}
			a[0].style.listStyleType = lst;
		}
	}

	EasyCodeClass = new newEasyCodeClass();
	//-->
</script>
<?php
			}
			return $text;
		}

		function linkcssfile($cssname = 'normal'){
			if($cssname !== ''){
				$plv = get_bloginfo('wpurl').'/wp-content/plugins/easycode/style/'.$cssname.'/style.css';
				echo '<link rel="stylesheet" type="text/css" id="easycodestyle" href="'.$plv.'" />';
			}
		}

		function getcodeid(){
			return mt_rand();
		}

		function getoption(){
			$option = get_option($this->adminname);
			if(empty($option)){
				$option = array('hiden'=>0,'style'=>'normal','rownum'=>2);
				update_option($this->adminname,$option);
			}
			return $option;
		}

		function printAdminPage(){
			if(isset($_POST['submit'])){
				$option = array();
				$option['hiden'] = intval($_POST['hiden']);
				$option['style'] = $_POST['style'];
				$option['rownum'] = intval($_POST['rownum']);
				update_option($this->adminname,$option);
				echo '<div class="updated settings-error"><p>'.translate('Settings saved.').'&nbsp;&nbsp;<b>:)</b></p></div>';
			}else{
				$option = $this->option;
			}

			$stylelist = scandir(dirname(__FILE__).'/style');
			$styleradio = array($option['style']=>' selected');
			$rownumradio = array($option['rownum']=>' checked');
			$hidenradio = array($option['hiden']=>' checked');
?>
<div class='wrap'>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<h2>EasyCode Setting</h2>

<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Default status','easycode'); ?></th>
<td>
	<small><em></em></small><br>
	<label><input type="radio" name="hiden" value="1" <?php  echo $hidenradio[1]; ?>><?php _e('Collapsing code','easycode'); ?></label><br>
	<label><input type="radio" name="hiden" value="0" <?php  echo $hidenradio[0]; ?>><?php _e('Do not collapse/ allow users to collapse','easycode'); ?></label><br>
	<label><input type="radio" name="hiden" value="2" <?php  echo $hidenradio[2]; ?>><?php _e('Do not collapse/ don\'t allow users to collapse','easycode'); ?></label><br>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('line numbers','easycode');?></th>
<td>
	<label><input type="radio" name="rownum" value="0" <?php  echo $rownumradio[0]; ?>><?php _e('none','easycode'); ?></label><br>
	<label><input type="radio" name="rownum" value="1" <?php  echo $rownumradio[1]; ?>><?php _e('1-bit number with no leading zeros','easycode'); ?></label><br>
	<label><input type="radio" name="rownum" value="2" <?php  echo $rownumradio[2]; ?>><?php _e('2-bit number with leading zeros','easycode'); ?></label>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Style','easycode');?><br><small><em></em></small></th>
<td>
	<script type="text/javascript">
		function ChageEasyCodeCSS(id){
			sobj = document.getElementById(id);
			stylename = sobj.options[sobj.selectedIndex].text;
			fobj = document.getElementById('easycodestyleiframe');
			fobj.contentDocument.getElementById('easycodestyle').href = 'style/'+stylename+'/style.css';
			fobj.contentDocument.getElementsByTagName('StyleName')[0].innerHTML = stylename;
			fobj.style.display = 'block';
		}
	</script>

	<select name="style" id="easycode_style" onchange="ChageEasyCodeCSS(this.id)">
		<?php
		foreach($stylelist as $styledir){
			if($styledir !== '.' && $styledir !== '..'){
		?>
		<option value="<?php echo $styledir; ?>" <?php echo $styleradio[$styledir]; ?>><?php echo $styledir ?></option>
		<?php
			}
		}
		?>
	</select>
	<br><small><?php _e('Preview','easycode'); ?></small><br>
	<iframe width="300px" height="160px" id="easycodestyleiframe" style="display:none;"  scrolling="no" border="0" frameborder="0" src="<?php echo get_bloginfo('wpurl');?>/wp-content/plugins/easycode/stylepreview.htm" onload="ChageEasyCodeCSS('easycode_style');"></iframe>
</td>
</tr>
</table>

<p class="submit"><input name="submit" class="button-primary" id="submit" type="submit" value="<?php _e('Save','easycode'); ?>"/></p>
</form>
</div>

<?php
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
