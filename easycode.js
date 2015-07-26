<!--
function CreatEasyCodeClass(input,output){
	//if(input == undefined){return;}

	switch(typeof input){
	case 'string':
		o = document.getElementById(input);
		break;
	case 'number':
		o = document.getElementById('easycodebox-'+input);
		break;
	case 'object':
		if(input.className=='headbar'||'main'){
			o=input.parentNode;
		}else if(input.nodeName=='OL'){
			o=input.parentNode.parentNode;
		}
		break;
	}

	if (!this._initialized){
		this.HtmlTo=function(text,u){
			text = text.replace(/<[^\n<>]+>/igm,'');
			if(u){
				text = text.replace(/&quot;/g,'"').replace(/&#34;/g,'"');
				text = text.replace(/&apos;/g,"'").replace(/&#39;/g,"'");
				text = text.replace(/&amp;/g,'&').replace(/&#38;/g,'&');
				text = text.replace(/&lt;/g,'<').replace(/&#60;/g,'<');
				text = text.replace(/&gt;/g,'>').replace(/&#62;/g,'>');
				text = text.replace(/&acute;/g,'´').replace(/&#180;/g,'´');
				text = text.replace(/&nbsp;/g,' ').replace(/&#160;/g,' ');
				text = text.replace(/[\r\n]+/igm, "\n");
				text = text.replace(/\n\n/g, "\n");
			}
			return text;
		}

		this.ShowCode = function(){
			if(o.className == 'easycodebox hidden'){
				o.className = 'easycodebox';
			}else{
				o.className = 'easycodebox hidden';
			}
		}

		this.TextAreaBox=function(mode,message,time){
			textareaobj = document.getElementById('EasyCodeTextAreaBox');
			if(mode){
				var olo = o.getElementsByTagName('ol')[0], text = this.HtmlTo(olo.innerHTML,1);
				textareaobj.style.display = 'block';
				ot = textareaobj.getElementsByTagName('textarea')[0];
				ot.value = text;
				ot.select();
			}else{
				textareaobj.style.display = 'none';
			}
		}

		this.HighLightCode = function(){
			lang = o.className.replace('hidden','').replace(' ','').replace('easycodebox','');
			if(!lang) lang='None'
			alert('The language is '+lang);
		}

		this._initialized = true;
	}
}

function EasyCodeClass(a){
	return new CreatEasyCodeClass(a);
}

EasyCodeGlobal = {
	pluginDir:'',
	ECshowHelp:function (){
		var x = (screen.width-500)/2,y = (screen.height-300)/2;
		features = 'menubar=no,resizable=no,status=no,location=no,toolbar=no,'+',height='+height+',width='+width+',top='+y+',left='+x;
		objWin = window.open(EasyCodeGlobal.pluginDir+'/template/showhelp.htm','showhelp',features);
	}
}

//document.body.onload=function(){EasyCodeHL.hightLightAll();}

//-->