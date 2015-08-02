// Docu : http://wiki.moxiecode.com/index.php/TinyMCE:Create_plugin/3.x#Creating_your_own_plugins

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('easycode_box');
	 
	tinymce.create('tinymce.plugins.easycode_box', {
		
		init : function(ed, dir) {
		// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('easycode_box_cmd', function() {
				ed.windowManager.open({
					file : dir + '/inspage.php',
					width : 360 + ed.getLang('easycode_box.delta_width', 0),
					height : 360 + ed.getLang('easycode_box.delta_height', 0),
					inline : 1
				}, {
					plugin_url : dir
				});
			});


			// Register example button
			ed.addButton('easycode_box', {
				title : 'EasyCode Add Code Box',
				cmd : 'easycode_box_cmd',
				image : dir + '/codebox_button.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('easycode_box', n.nodeName == 'IMG');
			});
		},

        createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {longname: 'EasyCode CodeBox',author: 'Bob',infourl:'',version : "1.0"};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('easycode_box', tinymce.plugins.easycode_box);
})();


