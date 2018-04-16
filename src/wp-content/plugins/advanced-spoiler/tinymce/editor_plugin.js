(function() {
	// Load plugin specific language pack
//	tinymce.PluginManager.requireLangPack('adv-spoiler');

	tinymce.create('tinymce.plugins.AdvSpoiler', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			if ( typeof _spoiler == 'undefined' ) return;
			// Register commands
			ed.addCommand('mceSpoiler', function() {
				var se = ed.selection;
				// No selection
				if (se.isCollapsed())
					return;
				_spoiler.popup(ed);
			});

			// Register buttons
			ed.addButton('spoiler', {
				title : _spoilerL10n.button_title,
				image : url + '/spoiler.gif',
				cmd : 'mceSpoiler'
			});

			ed.onNodeChange.add(function(ed, cm, n, co) {
				cm.setDisabled('spoiler', co);
				cm.setActive('spoiler', !n.name);
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : "Advanced Spoiler",
				author : '082net',
				authorurl : 'http://082net.com/',
				infourl : 'http://082net.com/tag/advanced-spoiler/',
				version : "2.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('spoiler', tinymce.plugins.AdvSpoiler);
})();