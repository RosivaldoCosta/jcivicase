function jevEditPopup(url,popupw, popuph){
	SqueezeBox.initialize({});
	SqueezeBox.setOptions(SqueezeBox.presets,{'handler': 'iframe','size': {'x': popupw, 'y': popuph},'closeWithOverlay': 0});
	SqueezeBox.url = url;		
	SqueezeBox.setContent('iframe', SqueezeBox.url );
	return;
}