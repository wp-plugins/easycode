function init() {
	tinyMCEPopup.resizeToInnerSize();
	tagRadioChange();
}

function insertCodeBox(){
	var title=document.getElementById('title-input').value;
	var lang = document.getElementById('lang-input').value;
	var content = document.getElementById('content-input').value;
	if(content!=''){
		/*content = content.replace(/&/g,'&amp;');
		content = content.replace(/"/g,'&quot;');
		content = content.replace(/´/g,'&acute;');
		content = content.replace(/'/g,'&apos;');*/
		content = content.replace(/</g,'&lt;');
		content = content.replace(/>/g,'&gt;');
		content = content.replace(/ /g,'&nbsp;');
		content = content.replace(/\r\n/g,'<br />').replace(/\n|\r/g,'<br />');
		content=content.replace(/\t/g,'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		content = content?(' '+content.replace(/code]/g,'code]]')):'';
		title = title?(' '+title.replace(/code]/g,'c0de]')):'';
		if(lang===undefined || lang=='None') lang='';
	}
	//Tag
	if (document.getElementById('bb-tag').checked){
		tag1 = '['+lang+'code'+title+']';
		tag2 = '[/code]';
	}else if (document.getElementById('code-tag').checked){
		tag1 = '<code>';
		tag2 = '</code>';
	}else{
		tag1 = '<pre class="easycode; title:'+title+';lang:'+lang+';">';
		tag2 = '</pre>';
	}

	tinyMCEPopup.editor.execCommand('mceInsertContent', true, tag1+content+tag2);
	tinyMCEPopup.close();
	return;
}


function tagRadioChange(){
	var titleobj = document.getElementById('title');
	var langobj = document.getElementById('lang');

	if (document.getElementById('pre-tag').checked){
		titleobj.style.display = langobj.style.display ='block';
	}else if (document.getElementById('code-tag').checked){
		titleobj.style.display = langobj.style.display ='none';
	}else{
		titleobj.style.display = langobj.style.display ='block';
	}
}


