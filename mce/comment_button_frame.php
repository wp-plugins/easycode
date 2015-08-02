<script type="text/javascript" src="comment_button.js"></script>
<style type="text/css">
	body {padding:0;}
	table th {padding:2px; text-align:right; }
	table td {padding:2px;}
	#ec-title-input {width:200px;}
	#ec-lang-input {width:200px;}
	#ec-tag-input {width:120px;}
	#submit {width:60px;}
</style>
<form name="insert_ec_codebox" action="###">
<table width="200" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<th scope="row"></th>
		<td></td>
	</tr>
	<tr>
		<th scope="row">Title</th>
		<td><input type="text" id="ec-title-input" name="title" /></td>
	</tr>
	<tr>
		<th scope="row">Language</th>
		<td>
		<select name="lang" id="ec-lang-input">
			<option value="None" selected>None</option>
		</select>
		</td>
	</tr>
	<tr>
		<th scope="row">Tag</th>
		<td>
			<select name="tag" id="ec-tag-input">
				<option id="pre-tag" selected="selected"><b>&lt;pre&gt;</b></option>
				<!--<option id="bb-tag">[code]..[/code]</option>
				<option id="code-tag">&lt;code&gt;</option>-->
			</select>
			<input id="submit" type="button" value="Insert" onclick="insertCodeBox();"/>
		</td>
	</tr>
</table>
</form>