topDoc =  window.top.document;

function ecshowWindow(){
	o = topDoc.getElementById('easycode_comment_window');
	s = o.style.display;
	if(s=='none'){
		o.style.display='block';
	}else{
		o.style.display='none';
	}
}

function insertCodeBox(){
	title = document.getElementById('ec-title-input').value;
	lang = document.getElementById('ec-lang-input').value;
	tag = document.getElementById('ec-tag-input').selectedIndex;
	title = title?(' '+title.replace(/code]/g,'code/]')):'';
	if(lang===undefined || lang=='None') lang='';

	var insert = '',gui=' Stuff with your code! ';
	switch (tag){
	case 1:
		insert =insert+ '['+lang+'code '+title+']\n'+gui+'\n[/code]';
		break;
	case 0:
		insert = '<pre class="easycode;';
		insert = insert+ (title?'title:'+title+';':'');
		insert = insert+ (lang?'lang:'+lang+';':'');
		insert = insert+'">\n'+gui+'\n</pre>';
		break;
	case 2:
		insert = '<code>\n'+gui+'\n</code>';
		break;
	}
	var textAreaObj = topDoc.getElementById('comment');
	var con = textAreaObj.value;
	var pos = getAreaRange(textAreaObj);
	var context = {'start':con.slice(0, pos.start),'end':con.slice(pos.end)};

	//alert(insert);
	textAreaObj.value = context.start + insert + context.end;
	ecshowWindow();
}

function getAreaRange(obj) {
	pos = new Object();
	isIE = (navigator.appName.toLowerCase().indexOf('internet explorer')+1?1:0);
	if (isIE) {
		obj.focus();
		range = topDoc.selection.createRange();
		clone = range.duplicate();
		
		clone.moveToElementText(obj);
		clone.setEndPoint( 'EndToEnd', range );
		pos = {'start':clone.text.length-range.text.length,'end':clone.text.length-range.text.length+range.text.length};
  	}else if(window.top.getSelection()) {
		pos = {'start':obj.selectionStart,'end':obj.selectionEnd};
	}
	return pos;
}





