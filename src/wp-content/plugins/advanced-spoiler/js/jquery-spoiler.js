var Spoiler = {
	timer: 0,
	setOption: function(options) {
		var newOptions = Object.extend(options||{}, arguments[1]||{});
		return newOptions;
	},
	_start: function() {
		jQuery('a.spoiler-tgl').each(function(i){
			var el = jQuery('#'+jQuery(this).attr('id').replace('_tgl', ''));
			var opt = jQuery(this).attr('rev').split('||');
			this.onclick = function(){Spoiler.plugin(el, jQuery(this), opt); return false;};	
			if(el.css('display') != 'none') {
				Spoiler.plugin(el, jQuery(this), opt);
			}
		});
	},
	start: function() {
		jQuery('a.spoiler-tgl').each(function(i){
			var tglr = jQuery(this);
			var el = tglr.parent('p:first').next('div.spoiler-body:first');
			if (!el.length)
				return;
			var opt = tglr.attr('rev').split('||');
			tglr.click(function(){
				Spoiler.plugin(el, jQuery(this), opt); return false;
			}).focus(function(){this.blur()});
			if(el.css('display') != 'none') {
				tglr.toggleClass("collapsed");
				el.hide();
			}
		});
	},
	showhide: function(el){
		if(el.css('display') != 'none') el.css('display', 'none');
		else el.css('display', '');
	},
	//Effects
	Simple: function(el){
		this.showhide(el);
	},
	Appear: function(el, dur){
		el.fadeIn(dur);
	},
	Fade: function(el, dur){
		el.fadeOut(dur);
	},
	SlideDown: function(el, dur){
		el.slideDown(dur);
	},
	SlideUp: function(el, dur){
		el.slideUp(dur);
	},
	BlindDown: function(el, dur){
		el.css({marginTop:-el.outerHeight()}).show().wrap('<div class="spoiler_outer_box"></div>').
			animate({marginTop:'0'}, dur, function(){
				jQuery(this).css({marginTop:''}).parent('.spoiler_outer_box').before(this).remove();
		});
	},
	BlindUp: function(el, dur){
		el.wrap('<div class="spoiler_outer_box"></div>').
			animate({marginTop: -el.outerHeight()}, dur, function(){
				jQuery(this).hide().css({marginTop:''}).parent('.spoiler_outer_box').before(this).remove();
		});
	},
	PhaseIn: function(el, dur){
		el.animate({height: "show", opacity: "show"}, dur);
	},
	PhaseOut: function(el, dur){
		el.animate({height: "hide", opacity: "hide"}, dur);
	},
	PAIR: {
	'slide': ['SlideDown','SlideUp'],
	'blind': ['BlindDown','BlindUp'],
	'appear': ['Appear','Fade'],
	'phase': ['PhaseIn','PhaseOut'],
	'simple': ['Simple','Simple'],
	'apblind': ['Appear','BlindUp']
	},
	toggle: function(el, effect, dur) {
		if (!dur) dur = 'normal';// 0 or undefined
		if (dur < 0) dur = dur * 1000;
		if (dur < 10) dur = dur * 100;
		if (dur > 700) dur = 'slow';
		if (dur < 200) dur = 'fast';
		effect = (effect || 'appear').toLowerCase();
		Spoiler[(el.css('display') != 'none')?Spoiler.PAIR[effect][1]:Spoiler.PAIR[effect][0]](el, dur||400);
	},
	Collapse: function(el, effect, me, dur) {
		Spoiler.toggle(el, effect, dur);
		jQuery(me).toggleClass("collapsed");
	},
	plugin: function(el, tid, opt){
		if (el.css('display') != 'none') {
			tid.html(opt[1]);
		} else {
			tid.html(opt[2]);
		}
		tid.toggleClass("collapsed");
		Spoiler.toggle(el, opt[0], parseInt(opt[3]));
	}
};

jQuery(document).ready(function() { Spoiler.start(); });
