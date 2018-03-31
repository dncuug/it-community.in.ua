/*
Element.extend({
	addClass: Element.prototype.addClassName,
	removeClass: Element.prototype.removeClassName,
	toggleClass: Element.prototype.toggleClassName,
	hasClass: Element.prototype.toggleClassName
});
*/
var Spoiler = {
	timer: 0,
	setOption: function(options) {
		var newOptions = Object.extend(options||{}, arguments[1]||{});
		return newOptions;
	},
	start: function() {
		$$('a.spoiler-tgl').each(function(tgl){
			var el = tgl.id.replace('_tgl', '');
			el = $(el);
			var opt = tgl.getAttribute('rev').split('||');
			tgl.onclick = function(){Spoiler.plugin(el, tgl, opt); return false;};	
			if(el.style.display != 'none') {
				var hider = function(){Spoiler.plugin(el, tgl, opt)};
				this.timer += 60;
				hider.delay(this.timer);
			}
		}, this);
	},
	set: function(el,h,o){
		if(h) el.setStyle('height', 0);
		if(o) el.setStyle('opacity', 0);
		this.showhide(el);
	},
	reset: function(el,h,d){
		if(d !== 'no') this.showhide(el);
		if(h) el.style.height = '';
		el.style.visibility = '';
		el.style.overflow = '';
		if (window.ActiveXObject) el.style.filter = '';
		else el.style.opacity = '';
	},
	showhide: function(el){
		if(el.getStyle('display') != 'none') el.setStyle('display', 'none');
		else el.setStyle('display', '');
	},
	//Effects
	Simple: function(el){
		el = $(el);
		return this.showhide(el);
	},
	H: function(el, options) {
		$(el).setStyle('overflow', 'hidden');
		return new Fx.Style(el, 'height', options);
	},
	Appear: function(el){
		var options = this.setOption({onComplete: (function(){Spoiler.reset(el,false,'no')})}, arguments[1]||{});
		this.set(el, false, true);
		new Fx.Style(el, 'opacity', options).start(0,1);
	},
	Fade: function(el){
		var options = this.setOption({onComplete: (function() {Spoiler.reset(el)})}, arguments[1]||{});
		new Fx.Style(el, 'opacity', options).start(1,0);
	},
	SlideDown: function(el){
		this.set(el, true);
		el.setStyle('margin', '');
		var options = this.setOption({onComplete: (function(){Spoiler.reset(el,true,'no')})}, arguments[1]||{});
		this.H(el,options).start(0, el.scrollHeight);
/*		var slider = Fx.Slide(el, {onComplete: (function(){el.parentNode.replaceWith(el);})});
		el.setStyle('display', '');
		slider.hide();
		slider.slideIn();*/
	},
	SlideUp: function(el){
		var slider = new Fx.Slide(el, {onComplete: (function(){el.style.display = 'none';el.parentNode.replaceWith(el);})});
		slider.slideOut();
	},
	BackDown: function(el){
		this.set(el, true);
		var options = this.setOption({transition: Fx.Transitions.backOut, onComplete: (function(){Spoiler.reset(el,true,'no')})}, arguments[1]||{});
		this.H(el,options).start(0, el.scrollHeight);
	},
	BackUp: function(el){
		var options = this.setOption({transition: Fx.Transitions.backIn, onComplete: (function() {Spoiler.reset(el,true)})}, arguments[1]||{});
		this.H(el,options).start(el.offsetHeight, 0);
	},
	PhaseIn: function(el){
		var options = this.setOption(arguments[1]||{});
		this.set(el,true, true);
		new Fx.Style(el, 'opacity', options).start(0,1);
		options.transition = Fx.Transitions.backOut;
		options.onComplete = (function(){Spoiler.reset(el,true,'no')});
		this.H(el,options).start(0, el.scrollHeight);
	},
	PhaseOut: function(el){
		var options = this.setOption(arguments[1]||{});
		new Fx.Style(el, 'opacity', options).start(1,0);
		options.transition = Fx.Transitions.backIn;
		options.onComplete = (function(){Spoiler.reset(el,true)});
		this.H(el, options).start(el.offsetHeight, 0);
	},
	PAIR: {
	'slide': ['SlideDown','SlideUp'],
	'blind': ['BackDown','BackUp'],
	'appear': ['Appear','Fade'],
	'phase': ['PhaseIn','PhaseOut'],
	'simple': ['Simple','Simple'],
	'apblind': ['Appear','BackUp']
	},
	toggle: function(el, effect) {
	el = $(el);
	effect = (effect || 'appear').toLowerCase();
	Spoiler[(el.style.display != 'none')?Spoiler.PAIR[effect][1]:Spoiler.PAIR[effect][0]](el, arguments[2]||{});
	},
	Collapse: function(el, effect, me) {
		el = $(el);
		Spoiler.toggle(el, effect, arguments[3] || {});
		$(me).toggleClass("collapsed");
	},
	plugin: function(el, tid, opt){
		if (el.style.display != 'none') {
			tid.innerHTML = opt[1];
		} else {
			tid.innerHTML = opt[2];
		}
		tid.toggleClass("collapsed");
		Spoiler.toggle(el, opt[0], {duration: parseInt(opt[3])});
	}
};
window.addEvent('domready', Spoiler.start);
