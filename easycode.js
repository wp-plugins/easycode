<!--
function CreatEasyCodeClass(input,output){
	this.HtmlTo=function(text,dir){
		//When dir is true,remove HTML tags.
		if(dir){
			text = text.replace(/<[^\n<>]+>/igm,'');
			text = text.replace(/&quot;/g,'"').replace(/&#34;/g,'"');
			text = text.replace(/&apos;/g,"'").replace(/&#39;/g,"'");
			text = text.replace(/&amp;/g,'&').replace(/&#38;/g,'&');
			text = text.replace(/&lt;/g,'<').replace(/&#60;/g,'<');
			text = text.replace(/&gt;/g,'>').replace(/&#62;/g,'>');
			text = text.replace(/&acute;/g,'´').replace(/&#180;/g,'´');
			text = text.replace(/&nbsp;/g,' ').replace(/&#160;/g,' ');
			text = text.replace(/[\r\n]+/igm, "\n");
			text = text.replace(/\n\n/g, "\n");
		}else{//for<pre>
			//text = text.replace(/&/g,'&amp;');
			//text = text.replace(/"/g,'&quot;');
			//text = text.replace(/'/g,'&apos;');
			text = text.replace(/</g,'&lt;');
			text = text.replace(/>/g,'&gt;');
			text = text.replace(/ /g,'&nbsp;');
			text = text.replace(/[\r\n]+/igm, "\n");
			text = text.replace(/\n\n/g, "\n");
		}
		return text;
	}	

	if(!input){//operation on `editcode` textarea
		textareaobj = document.getElementById('EasyCodeTextAreaBox');

		this.closeEdit = function(){
			textareaobj.style.display = 'none';
		}

		this.printCode =function(auto){
			features = 'menubar=no,resizable=yes,status=no,location=no,toolbar=no,height=800,width=600';
			objWin = window.open('','_blank',features);
			if(auto===undefined) auto=true;
			written = '<html>\n<head>\n<title>[EasyCode]Print Code</title>\n</head>\n<body onload="'+(auto?'window.print();':'')+'">';
			written = written+'<small>[EasyCode] Press Ctrl+P to <a href="javascript:window.print();">Print</a></small>\n';
			written = written+'<pre>'+this.HtmlTo(textareaobj.getElementsByTagName('textarea')[0].value,false)+'</pre></body></html>';
			objWin.document.write(written);
			objWin.document.close();
		}

	}else{//oprate on `codebox`

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
			this.childNodes = new Object;
			this.getChildNode = function(target){
				switch (target){
				case 'ol':
					if (!this.childNodes.hasOwnProperty('ol')) this.childNodes.ol = o.getElementsByTagName('ol')[0];
					return this.childNodes.ol;
					break;
				
				}
			}

			this.getInfo = function(target){
				if(target=='lang'){
					result = o.className.replace('hidecode','').replace(/ /g,'').replace('easycodebox','');
				}

				return result;
			}


			this.ShowCode = function(){
				if(o.className == 'easycodebox hidecode'){
					o.className = 'easycodebox';
				}else{
					o.className = 'easycodebox hidecode';
				}
			}

			this.editCode=function(mode,message,time){
				textareaobj = document.getElementById('EasyCodeTextAreaBox');
				if(mode){
					var olo = this.getChildNode('ol'), text = this.HtmlTo(olo.innerHTML,1);
					textareaobj.style.display = 'block';
					ot = textareaobj.getElementsByTagName('textarea')[0];
					ot.value = text;
					ot.select();
				}else{
					textareaobj.style.display = 'none';
				}
			}

			this.HighLightCode = function(){
				lang = this.getInfo('lang');
				if(lang){
					//alert('The language is '+lang);
					olo = this.getChildNode('ol');
					olo.innerHTML = EasyCodeHighLight.codeHandler(olo.innerHTML,lang);
				}
			}

			this._initialized = true;
		}
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

//document.body.onload=function(){EasyCodeHighLight.hightLightAll();}

//-->