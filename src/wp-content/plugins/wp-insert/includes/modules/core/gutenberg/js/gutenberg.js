var wpInsertIconData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUAgMAAADw5/WeAAAABGdBTUEAALGPC/xhBQAAACBjSFJN AAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAADFBMVEX/////AAAAAAD///+x fSgUAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElN RQfiCwMTIix5N7k8AAAAVElEQVQI102NsQ3AMAzDmAI5IU/0ipzgIf6np2gMfGXtdKkHQpAAGqBR 1yd37GFEyBeb5o7omTWHGzL3iTzr4jo0FechP35NralW2riU5nTnl4jnBSBgIy0jtaVeAAAAJXRF WHRkYXRlOmNyZWF0ZQAyMDE4LTExLTA0VDAyOjM0OjQ0LTA3OjAwblAMKwAAACV0RVh0ZGF0ZTpt b2RpZnkAMjAxOC0xMS0wNFQwMjozNDo0NC0wNzowMB8NtJcAAAAASUVORK5CYII=';
var wpInsertAdData = [];
wp.blocks.registerBlockType('wp-insert/wp-insert-gutenberg', {
	title: 'Wp-Insert Ad Unit',
	icon: wp.element.createElement('img', { width: 20, height: 20, src: wpInsertIconData}),
	category: 'embed',
	attributes: {
		adID: {
			type: 'string',
		},
		adType: {
			type: 'string',
		},
	},
	edit: function(props) {
		wp_insert_gutenberg_get_data();
		var options = {
			'inpostads': [],
			'shortcodeads': []
		};
		var inpostadsCount = 0, shortcodeadsCount = 0;
		for(var i = 0; i < wpInsertAdData.length; i++) {
			options[wpInsertAdData[i].type].push(wp.element.createElement('option', {value: wpInsertAdData[i].id, 'data-type': wpInsertAdData[i].type}, wpInsertAdData[i].title));
			if(wpInsertAdData[i].type == 'inpostads') {
				inpostadsCount++;
			} else if(wpInsertAdData[i].type == 'shortcodeads') {
				shortcodeadsCount++;
			}
		}
		if((inpostadsCount > 0) || (shortcodeadsCount > 0)) {			
			var optGroup = [];
			if(inpostadsCount > 0) {
				optGroup[0] = wp.element.createElement('optgroup', {label: 'In-Post Ads'}, options['inpostads']);
			}
			if(shortcodeadsCount > 0) {
				optGroup[1] = wp.element.createElement('optgroup', {label: 'Shortcode Ads'}, options['shortcodeads']);
			};
			return wp.element.createElement('div', {
				className: props.className,
				style: {border: '1px solid #dddddd', backgroundColor: '#fafafa', padding: '20px'},
				value: props.attributes.adID,
			}, 	wp.element.createElement('img', {width: 20, height: 20, style: {cssFloat: 'left'}, src: wpInsertIconData}),
				wp.element.createElement('p', {style: {cssFloat: 'left', fontSize: '18px', fontWeight: 'bold', margin: '0 0 10px 8px', lineHeight: '20px', fontFamily: 'arial'}}, 'Wp-Insert'),
				wp.element.createElement('p', {style: {clear: 'both', fontSize: '12px', fontFamily: 'arial', margin: '0 0 5px'}}, 'Select your Ad Unit'),
				wp.element.createElement('select', {
				value: props.attributes.adID,
				className: 'input widefat',
				style: {height: '44px', fontSize: '18px', padding: '5px'},
				onChange: function(element) {
					props.setAttributes({adID: element.target[element.target.selectedIndex].value});
					props.setAttributes({adType: element.target[element.target.selectedIndex].getAttribute('data-type')});
				}
				}, optGroup),
			);
		} else {
			return wp.element.createElement('div', {
				className: props.className,
				style: {border: '1px solid #dddddd', backgroundColor: '#fafafa', padding: '20px'},
				value: props.attributes.adID,
			}, 	wp.element.createElement('img', {width: 20, height: 20, style: {cssFloat: 'left'}, src: wpInsertIconData}),
				wp.element.createElement('p', {style: {cssFloat: 'left', fontSize: '18px', fontWeight: 'bold', margin: '0 0 10px 8px', lineHeight: '20px', fontFamily: 'arial'}}, 'Wp-Insert'),
				wp.element.createElement('p', {style: {clear: 'both', fontSize: '12px', fontFamily: 'arial', margin: '0 0 5px'}}, 'Please create an Ad Unit from Wp-Insert settings page to use this feature.'),
			);
		}
	},
	save: function(props) {
		if(props.attributes.adType == 'inpostads') {
			return '[wpinsertinpostad id="'+props.attributes.adID+'"]';
		} else if(props.attributes.adType == 'shortcodeads') {
			return '[wpinsertshortcodead id="'+props.attributes.adID+'"]';
		} else {
			return '';
		}
	}
});

jQuery(document).ready(function() {
	wp_insert_gutenberg_get_data();
});

function wp_insert_gutenberg_get_data() {
	jQuery.post(
		jQuery('#wp_insert_gutenberg_admin_ajax').val(), {
			'action': 'wp_insert_gutenberg_get_ad_data',
			'wp_insert_gutenberg_nonce': jQuery('#wp_insert_gutenberg_nonce').val()
		}, function(response) {
			if(response.indexOf('###SUCCESS###') !== -1) {
				wpInsertAdData = jQuery.parseJSON(response.replace('###SUCCESS###', ''));
			}
		}
	);
}