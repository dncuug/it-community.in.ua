// closure to avoid namespace collision
(function () {
	// create the plugin
	tinymce.create("tinymce.plugins.wellthemesShortcodes", {	
		
		// creates control instances based on the control's id.
		// our button's id is "wellthemes_button"
		
		createControl: function ( btn, e ) {
			if ( btn == "wellthemes_button" ) {	
				
				var a = this;
				
				// creates the button
				var btn = e.createSplitButton('wellthemes_button', {
                    title: "Insert Shortcodes", // title of the button
					image: wellthemesShortCodes.plugin_folder +"/tinymce/images/icon.png", // path to the button's image
					icons: false
                });
				
				//Render a DropDown Menu
                btn.onRenderMenu.add(function (c, b) {
                	
					c = b.addMenu({
						title: "Message Boxes"
					});
					a.addImmediate( c, "Doc", "[box style='doc'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Download", "[box style='download'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Error", "[box style='error'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Help", "[box style='help'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Info", "[box style='info'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Media", "[box style='media'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "New", "[box style='new'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Note", "[box style='note'] Content goes here... [/box]<br /><br />");					
					a.addImmediate( c, "Success", "[box style='success'] Content goes here... [/box]<br /><br />");
					a.addImmediate( c, "Warning", "[box style='warning'] Content goes here... [/box]<br /><br />");
					
					c = b.addMenu({
						title: "Buttons"
					});
					a.addImmediate( c, "Default", "[button url='#' size='small' style='default'] Button text... [/button]");
					a.addImmediate( c, "Black", "[button url='#' size='small' style='black'] Button text... [/button]");
					a.addImmediate( c, "Blue", "[button url='#' size='small' style='blue'] Button text... [/button]");
					a.addImmediate( c, "Brown", "[button url='#' size='small' style='brown'] Button text... [/button]");
					a.addImmediate( c, "Coral", "[button url='#' size='small' style='coral'] Button text... [/button]");	
					a.addImmediate( c, "Dark Brown", "[button url='#' size='small' style='dark-brown'] Button text... [/button]");					
					a.addImmediate( c, "Dark Green", "[button url='#' size='small' style='dark-green'] Button text... [/button]");
					a.addImmediate( c, "Green", "[button url='#' size='small' style='green'] Button text... [/button]");
					a.addImmediate( c, "Magenta", "[button url='#' size='small' style='magenta'] Button text... [/button]");
					a.addImmediate( c, "Maroon", "[button url='#' size='small' style='maroon'] Button text... [/button]");					
					a.addImmediate( c, "Orange", "[button url='#' size='small' style='orange'] Button text... [/button]");					
					a.addImmediate( c, "Pink", "[button url='#' size='small' style='pink'] Button text... [/button]");
					a.addImmediate( c, "Purple", "[button url='#' size='small' style='purple'] Button text... [/button]");
					a.addImmediate( c, "Red", "[button url='#' size='small' style='red'] Button text... [/button]");
					a.addImmediate( c, "Royal Blue", "[button url='#' size='small' style='royal-blue'] Button text... [/button]");
					a.addImmediate( c, "Sienna", "[button url='#' size='small' style='sienna'] Button text... [/button]");
					a.addImmediate( c, "Silver", "[button url='#' size='small' style='silver'] Button text... [/button]");
					a.addImmediate( c, "Sky Blue", "[button url='#' size='small' style='sky-blue'] Button text... [/button]");
					a.addImmediate( c, "Teal", "[button url='#' size='small' style='teal'] Button text... [/button]");
					a.addImmediate( c, "Yellow", "[button url='#' size='small' style='yellow'] Button text... [/button]");
					
					c = b.addMenu({
						title: "Highlight"
					});
					a.addImmediate( c, "Default", "[highlight style='default'] Content goes here... [/highlight]");
					a.addImmediate( c, "Alice Blue", "[highlight style='alice-blue'] Content goes here... [/highlight]");
					a.addImmediate( c, "Beige", "[highlight style='beige'] Content goes here... [/highlight]");
					a.addImmediate( c, "Buff", "[highlight style='buff'] Content goes here... [/highlight]");
					a.addImmediate( c, "Bubbles", "[highlight style='bubbles'] Content goes here... [/highlight]");
					a.addImmediate( c, "Cream", "[highlight style='cream'] Content goes here... [/highlight]");
					a.addImmediate( c, "Cyan", "[highlight style='cyan'] Content goes here... [/highlight]");
					a.addImmediate( c, "Magenta", "[highlight style='magenta'] Content goes here... [/highlight]");
					a.addImmediate( c, "Misty Rose", "[highlight style='misty-rose'] Content goes here... [/highlight]");
					a.addImmediate( c, "Pearl", "[highlight style='pearl'] Content goes here... [/highlight]");
					a.addImmediate( c, "Peach", "[highlight style='peach'] Content goes here... [/highlight]");
					a.addImmediate( c, "Pear Green", "[highlight style='pear-green'] Content goes here... [/highlight]");
					a.addImmediate( c, "Pink", "[highlight style='pink'] Content goes here... [/highlight]");
					a.addImmediate( c, "Platinum", "[highlight style='platinum'] Content goes here... [/highlight]");
					a.addImmediate( c, "Salmon", "[highlight style='salmon'] Content goes here... [/highlight]");
					a.addImmediate( c, "Seashell", "[highlight style='seashell'] Content goes here... [/highlight]");
					a.addImmediate( c, "Smoke", "[highlight style='smoke'] Content goes here... [/highlight]");
					a.addImmediate( c, "Yellow", "[highlight style='yellow'] Content goes here... [/highlight]");
					a.addImmediate( c, "Vanilla", "[highlight style='vanilla'] Content goes here... [/highlight]");
					a.addImmediate( c, "Wheat", "[highlight style='wheat'] Content goes here... [/highlight]");
					
					c = b.addMenu({
						title: "Lists"
					});
					a.addImmediate( c, "Arrow List", "[list style='arrow']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Circle List", "[list style='circle']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Square List", "[list style='square']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Star List", "[list style='star']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Diamond List", "[list style='diamond']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Pencil List", "[list style='pencil']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Check List", "[list style='check']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Check Green List", "[list style='check-green']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");					
					a.addImmediate( c, "Plus List", "[list style='plus']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Plus Green List", "[list style='plus-green']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Minus List", "[list style='minus']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");	
					a.addImmediate( c, "Minus Red List", "[list style='minus-red']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");					
					a.addImmediate( c, "Doc List", "[list style='doc']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Info List", "[list style='info']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					a.addImmediate( c, "Error list", "[list style='error']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");					
					a.addImmediate( c, "Warning List", "[list style='warning']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					
					a.addImmediate( c, "Delete List", "[list style='delete']<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 1...&nbsp;[/list_item]<br />&nbsp;&nbsp;&nbsp;&nbsp;[list_item]&nbsp;Item 2...&nbsp;[/list_item]<br />[/list]<br /><br />");
					
								
					c = b.addMenu({
						title: "Social"
					});
					a.addImmediate( c, "Facebook", "[facebook]");
					a.addImmediate( c, "Goole+", "[gplus]");
					a.addImmediate( c, "Twitter", "[twitter]");
					a.addImmediate( c, "LinkedIn", "[linkedin]");
					a.addImmediate( c, "StumbleUpon", "[stumbleupon]");
					a.addImmediate( c, "Digg", "[digg]");
					

					c = b.addMenu({
						title: "Videos"
					});
					a.addImmediate( c, "Youtube", "[video type='youtube' id='Bag1gUxuU0g' width='500' height='300'  /]");
					a.addImmediate( c, "Vimeo", "[video type='vimeo' id='33716408' width='500' height='300' /]");
															
					c = b.addMenu({
						title: "Dropcaps"
					});
					
					a.addImmediate( c, "Default", "[dropcap style='default']A[/dropcap]");
					a.addImmediate( c, "Circle Background", "[dropcap style='circle']A[/dropcap]");
					a.addImmediate( c, "Square Background", "[dropcap style='square']A[/dropcap]");
					a.addImmediate( c, "Round Background", "[dropcap style='round']A[/dropcap]");
					
					a.addImmediate( b, "Lightbox Image", "[lightbox_image src='' bigimage='' title='Image']<br>" );
					
				});
                
                return btn;
			}
			
			return null;
		},		
		
		//Insert shortcode into content
		addImmediate: function ( ed, title, sc) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand( "mceInsertContent", false, sc )
				}
			})
		},
		
		// credits
		getInfo: function () {
			return {
				longname : "Well Themes Shortcodes",
				author : 'Well Themes',
				authorurl : 'http://wellthemes.com/',
				infourl : 'http://wellthemes.com/',
				version : "1.0"
			};
		}
	});
	
	// add wellthemes plugin
	tinymce.PluginManager.add("wellthemesShortcodes", tinymce.plugins.wellthemesShortcodes);
})();