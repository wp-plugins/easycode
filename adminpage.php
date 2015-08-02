<?php
$slist = $this->get_style_list();
if(isset($_POST['submit'])){
	$option = array();
	$option['color'] = $this->color_handler($_POST['color']);
	$option['hidecode'] = intval($_POST['hidecode']);
	$option['rownum'] = intval($_POST['rownum']);
	$option['edit_code'] = intval($_POST['edit_code']);
	$option['nowrap'] = intval($_POST['nowrap']);
	$option['tab-size'] = max(intval($_POST['nowrap']),1);
	$option['html_tag_p'] = intval($_POST['html_tag_p']);
	$option['handle_comments'] = intval($_POST['handle_comments']);
	$option['use_custom_color'] = (bool)$_POST['use_custom_color'];
	$option['system_color'] = in_array($_POST['system_color'],$slist,true)?$_POST['system_color']:'Gray';
	$option['border-width'] = min(max(intval($_POST['border-width']),0),9);
	$option['formatted']=$_POST['formatted'];
	$option['formatted']['pre']=1;
	$option['formatted']= array_map('intval',$option['formatted']);

	update_option($this->adminname,$option);
	'<div class="updated settings-error"><p>'.__('Settings saved.').'</p></div>';
}elseif(isset($_POST['resetall'])){
	delete_option($this->adminname);
	$this->loadoption();
	$option = $this->option;
	echo '<div class="updated settings-error"><p>'.__('Settings saved.').'</p></div>';
}else{
	$option = $this->option;
}

$rownumradio = array($option['rownum']=>' checked="checked"');
$hiddenradio = array($option['hidecode']=>' checked="checked"');
$userstyleradio = array($option['use_custom_color']=>' checked="checked"');
if(in_array($_GET['op'],array('general','appearance'))){
	$op=$_GET['op'];
}else{
	$op='general';
}
$oparr=array($op=>'nav-tab-active');


//output the page
echo'<script type="text/javascript" src="'.$this->plugindir.'/easycode-adminpage.js"></script>';
echo '<link rel="stylesheet" type="text/css" id="easycodecss" href="'.$this->plugindir.'/admin.css" />';

?>
<div class='wrap'>
<h2 class="nav-tab-wrapper">EasyCode Setting&nbsp;
<a href="javascript:ecAdmin.chageTab('general');" class="nav-tab nav-tab-active" id="tab-general"><?php _e('General Settings'); ?></a>
<a href="javascript:ecAdmin.chageTab('appearance');ecAdmin.refreshColorShow();ecAdmin.changeStyleRadio();" class="nav-tab" id="tab-appearance"><?php _e('Appearance'); ?></a>
<form method="post" action="" style="display:inline;" onsubmit="return confirm('Are you sure to reset All settings?');">
	<input type="submit" name="resetall" value="<?php _e('Reset All Settings','easycode'); ?>" class="button"/>
</form>
</h2>

<form method="post" action="">

<table class="form-table" id="op-general">

<tr valign="top">
	<th scope="row"><?php _e('Collapsing','easycode'); ?></th>
	<td>
		<label><input type="radio" name="hidecode" value="1" <?php echo $hiddenradio[1]; ?>><?php _e('Collapsing code','easycode'); ?></label><br />
		<label><input type="radio" name="hidecode" value="0" <?php echo $hiddenradio[0]; ?>><?php _e('Do not collapse, enable collapsing','easycode'); ?></label><br />
	</td>
</tr>

<tr valign="top">
	<th scope="row"><?php _e('line numbers','easycode');?></th>
	<td>
		<label><input type="radio" name="rownum" value="0" <?php  echo $rownumradio[0]; ?>><?php _e('none','easycode'); ?></label><br />
		<label><input type="radio" name="rownum" value="1" <?php  echo $rownumradio[1]; ?>><?php _e('no leading zeros','easycode'); ?></label><br />
		<label><input type="radio" name="rownum" value="2" <?php  echo $rownumradio[2]; ?>><?php _e('leading zeros','easycode'); ?></label>
	</td>
</tr>

<tr valign="top">
	<th scope="row"><?php _e('Others','easycode'); ?></th>
	<td>
		<label><input type="checkbox" name="html_tag_p" value="1" <?php echo $option['html_tag_p']?'checked="checked"':''; ?>><?php _e('HTML TAG:use &lt;p&gt; instead of &lt;ol&gt;','easycode'); ?></label><br />
		<label><input type="checkbox" name="handle_comments" value="1" <?php echo $option['handle_comments']?'checked="checked"':''; ?>><?php _e('Handle code in comments','easycode'); ?></label><br />
		<label><input type="checkbox" name="edit_code" value="1" <?php echo $option['edit_code']?'checked="checked"':''; ?>><?php _e('Allow editing code','easycode'); ?></label><br />
	</td>
</tr>

<tr valign="top">
	<th scope="row"><?php _e('Formatted Tag','easycode'); ?></th>
	<td>
		<label><input type="checkbox" name="formatted[bb]" value="1" <?php echo $option['formatted']['bb']?'checked="checked"':''; ?>>[code <i>title</i>]...[/code]</label><br />
		<label><input type="checkbox" name="formatted[pre]" value="1" <?php echo $option['formatted']['pre']?'checked="checked"':''; ?>>HTML <?php _e('Tag','easycode');?>: &lt;pre&gt;</label><br />
		<label><input type="checkbox" name="formatted[code]" value="1" <?php echo $option['formatted']['code']?'checked="checked"':''; ?>>HTML <?php _e('Tag','easycode');?>: &lt;code&gt;</label><small> (<?php _e('The title is not avalible.','easycode');?>)<br />
	</td>
</tr>

</table>

<div id="op-appearance" class="" style="display:none;">
	<p>
		<h4><?php _e('Color schemes','easycode'); ?></h4>
		<label><input type="radio" name="use_custom_color" value="0" onclick="ecAdmin.changeStyleRadio(0);" <?php echo $userstyleradio[0];?>><?php _e('Use system color schemes','easycode');?>&nbsp;&nbsp;</label>
		<select name="system_color" id="system_color">
		<?php if (!in_array($option['system_color'],$slist,TRUE)) $option['system_color']='Gray';
		foreach($slist as $sname){
			$che = ($option['system_color']===$sname)?' selected="selected"':'';
			echo "\t\t\t<option value=\"$sname\" $che>$sname</option>";
		}?>
		</select>
		<br /><br />
		<label><input type="radio" id="use_custom_color" name="use_custom_color" value="1" onclick="ecAdmin.changeStyleRadio(1);" <?php echo $userstyleradio[1];?>><?php _e('Use custom color schemes','easycode');?></label>
	</p>

	<table id="inputcolor">
		<col align="left" />
		<tr>
			<td></td><td></td><td></td>
		</tr>
		<tr>
			<td><?php _e('Background');?></td>
			<td>
				#<input type="text" name="color[bg]" maxlength="7" onblur="ecAdmin.colorHandler(this);" value="<?php echo $option['color']['bg']; ?>" />
				<span class="colorshow">&nbsp;</span>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo __('Background'),'(',__('active'),')';?></td>
			<td>
				#<input type="text" name="color[bga]" maxlength="7" onblur="ecAdmin.colorHandler(this);" value="<?php echo $option['color']['bga']; ?>" />
				<span class="colorshow">&nbsp;</span>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><?php _e('Text Color');?></td>
			<td>
				#<input type="text" name="color[text]" maxlength="7" onblur="ecAdmin.colorHandler(this);" value="<?php echo $option['color']['text']; ?>" />
				<span class="colorshow">&nbsp;</span>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><?php echo __('Text Color'),'(',__('active'),')';?></td>
			<td>
				#<input type="text" name="color[texta]" maxlength="7" onblur="ecAdmin.colorHandler(this);" value="<?php echo $option['color']['texta']; ?>" />
				<span class="colorshow">&nbsp;</span>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><?php _e('Side color','easycode');?></td>
			<td>
				#<input type="text" name="color[side]" maxlength="7" onblur="ecAdmin.colorHandler(this);" value="<?php echo $option['color']['side']; ?>" />
				<span class="colorshow">&nbsp;</span>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><?php _e('Border');?></td>
			<td>
				#<input type="text" name="color[border]" maxlength="7" onblur="ecAdmin.colorHandler(this);" value="<?php echo $option['color']['border']; ?>" />
				<span class="colorshow">&nbsp;</span>
			</td>
			<td></td>
		</tr>
		<tr>
			<td><?php _e('Border Width','easycode');?>&nbsp;(0-9px)</td>
			<td>&nbsp;&nbsp;<input type="text" name="border-width" maxlength="1" value="<?php echo $option['border-width']; ?>"/></td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<h4><?php _e('Other Appearance Options','easycode'); ?></h4>
	<table>
		<tr>
			<td><label><?php _e('No Wrap','easycode'); ?>&nbsp;</label></td>
			<td><input type="checkbox" name="nowrap" value="1" <?php echo $option['nowrap']?'checked="checked"':''; ?>></td>
	</table>
</div>

<p class="submit"><input name="submit" class="button-primary" id="submit" type="submit" value="<?php _e('Save Changes'); ?>"/></p>
</form>

</div>

<?php ?>