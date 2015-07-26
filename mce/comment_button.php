<?php
$formatted = $this->option['formatted'];
echo '<link rel="stylesheet" type="text/css" href="'.$this->plugindir.'/mce/comment_button.css" />';

?>
<script type="text/javascript">
<!--
function ECshowCommentWindow(){
	o = document.getElementById('easycode_comment_window');
	s = o.style.display;
	if(s=='none'){
		o.style.display='block';
	}else{
		o.style.display='none';
	}
}
//-->
</script>
<div>
	<a id="easycode_comment_button" href="javascript:ECshowCommentWindow();" title="<?php _e('Insert Code','easycode');?>'"></a>
	<iframe id="easycode_comment_window" src="<?php echo $this->plugindir.'/mce/comment_button_frame.php';?>" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" style="display:none;"></iframe>
</div>


