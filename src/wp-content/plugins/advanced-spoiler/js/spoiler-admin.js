var _spoiler = {
	effects : ['simple', 'blind', 'slide', 'phase', 'appear', 'apblind'],
	row_pre: function(title){
		return '<tr valign="top"><th scope="row">'+title+'</th><td>';
	},
	row_post: function(){
		return '</td></tr>';
	},
	setup: function(appendTo) {
		_spoiler.ID = 'spoiler-dialog';

		// appendTo is what we attach the message HTML to
		if (!appendTo) appendTo = 'body';

		// Inject the message structure
		delete _spoiler.effects[_spoilerL10n.default_effect];
		var html = '<div class="hidden"><div id="'+_spoiler.ID+'" class="spoiler-dialog"><form name="spoiler-form" id="spoiler-form" method="post" action=""><table class="form-table">';
		html += _spoiler.row_pre(_spoilerL10n.effect_title);
		html += '<select name="spoiler-effect"><option value="'+_spoilerL10n.default_effect+'">'+_spoilerL10n.default_effect+'</option>';
		for ( var i = 0; i < _spoiler.effects.length; i++ ) {
			html += '	<option value="'+_spoiler.effects[i]+'">'+_spoiler.effects[i]+'</option>';
		}
		html += _spoiler.row_post();
		html += _spoiler.row_pre(_spoilerL10n.showtext_title);
		html += '<input type="text" name="spoiler-showtext" size="12" value="'+_spoilerL10n.showtext+'" />';
		html += _spoiler.row_post();
		html += _spoiler.row_pre(_spoilerL10n.hidetext_title);
		html += '<input type="text" name="spoiler-hidetext" size="12" value="'+_spoilerL10n.hidetext+'" />';
		html += _spoiler.row_post();
		html += '</table></form></div></div>';

		jQuery(appendTo).append(html);
		jQuery('#spoiler-form').submit(function(){_spoiler.okay(); return false;});
		var okay = _spoilerL10n.okay;
		var cancel = _spoilerL10n.cancel;
		var buttons = { okay: _spoiler.okay, cancel: _spoiler.close };
		jQuery('#'+_spoiler.ID).dialog({ autoOpen: false, width: 410, minWidth: 380, height: 240, minHeight: 240, maxHeight: 240, title: _spoilerL10n.title, buttons: buttons });
		// add quicktag button
		jQuery("#ed_toolbar").append('<input type="button" class="ed_button" onclick="_spoiler.popup()" title="'+_spoilerL10n.button_title+'" value="spoiler" />');
	},
	popup: function(){
		jQuery('#'+_spoiler.ID).dialog("open");
	},
	okay: function(){
		var options = jQuery('#spoiler-form').formToArray();
		var text='[spoiler';
		for (var i=0; i < options.length; i++) {
			switch(options[i].name) {
				case 'spoiler-effect':
					if (options[i].value != _spoilerL10n.default_effect)
						text += ' effect="'+options[i].value+'"';
				break;
				case 'spoiler-showtext':
					if (options[i].value != _spoilerL10n.showtext)
						text += ' show="'+options[i].value+'"';
				break;
				case 'spoiler-hidetext':
					if (options[i].value != _spoilerL10n.hidetext)
						text += ' hide="'+options[i].value+'"';
				break;
				default:
					continue;
				break;
			}
		}
		text += ']';
		if ( typeof tinyMCE != 'undefined' && ( ed = tinyMCE.activeEditor ) && !ed.isHidden() ) {
			ed.focus();
			if (tinymce.isIE)
				ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark);
			text = '<div>'+text;
			text += ed.selection.getContent();// getRng() strips all blocks :(
			text += '[/spoiler]</div>';
			ed.execCommand(tinymce.isGecko ? 'insertHTML' : 'mceInsertContent', false, text);
		} else {
			text += _spoiler.getRng(edCanvas);
			text += '[/spoiler]';
			edInsertContent(edCanvas, text);
		}
		_spoiler.close();
	},
	close: function(){
//		jQuery('#spoiler-form').resetForm();
		jQuery('#'+_spoiler.ID).dialog("close");
	},
	getRng: function(field) {
		var r= '';
		if (document.selection) {
			field.focus();
			sel = document.selection.createRange();
			r = sel.text;
		}
		//MOZILLA/NETSCAPE support
		else if (field.selectionStart || field.selectionStart == '0') {
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			if (startPos != endPos)
				r = field.value.substring(startPos, endPos);
		}
		return r;
	}
};

jQuery(document).ready( function() {
	_spoiler.setup();
});