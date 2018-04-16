//for more toggle effects
Effect.PAIRS = {
	'slide': ['SlideDown','SlideUp'],
	'blind': ['BlindDown','BlindUp'],
	'appear': ['Appear','Fade'],
	'phase': ['AjPhase','AjPhase'],
	'simple': ['Simple','Simple'],
	'apblind': ['Appear','BlindUp']
};
//toggle classname
function toggleClassName(element, classname) {
	if(Element.hasClassName(element, classname)) Element.removeClassName(element, classname);
	else Element.addClassName(element, classname);
}

//plugin script
Effect.plugin = function(element, effect, me, show, hide) {
	element = $(element);
	if (element.style.display != 'none') {
		me.innerHTML = show;
	} else {
		me.innerHTML = hide;
	}
	toggleClassName(me, 'collapsed');
	new Effect.toggle(element, effect, arguments[5] || {});
};

//combo effect for blog menu or ...
Effect.Collapse = function(element, effect, me) {
    element = $(element);
	new Effect.toggle(element, effect, arguments[3] || {});
	toggleClassName(me, 'collapsed');
};

//just show/hide
Effect.Simple = function(element) {
  element = $(element);
	if (element.style.display == 'none') 
	element.style.display = 'block';
	else	element.style.display = 'none'; 
};

//from k2(http://getk2.com) theme
Effect.AjPhaseIn = function(element) {
  element = $(element);
  new Effect.BlindDown(element, arguments[1] || {});
  new Effect.Appear(element, '', arguments[2] || arguments[1] || {});
};

Effect.AjPhaseOut = function(element) {
  element = $(element);
  new Effect.Fade(element, arguments[1] || {});
  new Effect.BlindUp(element, '', arguments[2] || arguments[1] || {});
};

//to make 'Phase' work with toggle
Effect.AjPhase = function(element) {
  element = $(element);
  if (element.style.display == 'none')
    new Effect.AjPhaseIn(element, arguments[1] || {}, arguments[2] || arguments[1] || {});
  else new Effect.AjPhaseOut(element, arguments[1] || {}, arguments[2] || arguments[1] || {});
};

function init_Spoiler() {
	$$('a.spoiler-tgl').each(function(tgl) {
		var el = tgl.up(0).next('div.spoiler-body', 0);
		if(!el)
			throw $continue;
		var opt = tgl.getAttribute('rev').split('||');
		tgl.onclick = function(){
			new Effect.plugin(el, opt[0], tgl, opt[1], opt[2], {duration: parseInt(opt[3])/1000});
			return false;
		}
		if(el.style.display != 'none') {
			new Effect.plugin(el, opt[0], tgl, opt[1], opt[2], {duration: parseInt(opt[3])/1000});
		}
	});
}

var Spoiler = Effect;

Event.observe(window, 'load', init_Spoiler, false);