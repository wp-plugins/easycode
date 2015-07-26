ecAdmin = {
	'chageTab':
	function(setop){
		tablelist=new Array('general','appearance');//All possible pages
		for(i in tablelist){
			if(setop==tablelist[i]){
				document.getElementById('op-'+tablelist[i]).style.display='block';
				document.getElementById('tab-'+tablelist[i]).className='nav-tab nav-tab-active';
			}else{
				document.getElementById('op-'+tablelist[i]).style.display='none';
				document.getElementById('tab-'+tablelist[i]).className='nav-tab';
			}
		}
	},

	'changeStyleRadio':
	function(radioValue){
		var o1=document.getElementById('default_style'),o2=document.getElementById('inputcolor');
		var ros=document.getElementById('use_user_style').getElementsByTagName('input');
		if(ros[0].checked==false && radioValue===undefined) radioValue=1;
		if(radioValue){
			o1.style.display='none';
			o2.style.display='';
		}else{
			o1.style.display='';
			o2.style.display='none';
		}
	},


	'colorHandler':
	function(inputobj){
		if (!inputobj.value) return;
		color=this.colorFilter(inputobj.value);
		colorShowNode = inputobj.parentNode.getElementsByTagName('span')[0];
		if (color.length!=0){
			inputobj.value = color;
			this.colorShow(colorShowNode,color);
		}else{
			input.value = colorFilter(colorShowNode.style.backgroundColor);
		}

	},

	'colorFilter':
	function(input){
		input=input.toUpperCase().replace(/[^0-9A-F]/g,'');
		if(input.length>=6){
			input=input.slice(0,6);
		}else if(input.length<6 && input.length>=3){
			input=input.slice(0,3);
		}else if(input.length<3 && input.length>0){
			fc=input.charAt(0);
			input=fc+fc+fc;
		}else{
			input='fff';
		}
		return input;
	},

	'colorShow':
	function(node,color){
		if (!color)  return;
		node.style.backgroundColor='#'+color;
	},

	'refreshColorShow':
	function(){
		tagList = document.getElementById('inputcolor').getElementsByTagName('input');
		for(i=0; i<=5;i++){
			this.colorHandler(tagList[i]);
		}
	}
}