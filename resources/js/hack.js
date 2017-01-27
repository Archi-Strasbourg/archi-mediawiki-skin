
/**
 * HACKY THINGS
 */
// Set up header image
$(document).ready(function(){
	// Generic article page
	var $featuredThumb = $('#mw-content-text').find('a.image>img').first()
	var featuredThumbUrl = $featuredThumb.attr('src');
	if (typeof featuredThumbUrl != 'undefined') {
		featuredThumbUrl = featuredThumbUrl.substr(0,featuredThumbUrl.lastIndexOf('/'));
		var featuredImageUrl = featuredThumbUrl.replace(/\/thumb/, '');
		$('#header-image').css({
			backgroundImage: 'url(' + featuredImageUrl + ')'
		}).removeClass('hide');
		$featuredThumb.parents('.thumb').hide();
	}
});

$(document).ready(function(){
	
	// Wrap all content html in a nice div
	$('.mw-body').each(function(){
		$(this).wrapInner('<div class="mw-content-column"></div>');
		$(this).prepend('<div class="mw-info-column"></div>');
	})
	// Add infobox class to infobox table on load
	$('#mw-content-text>table').has('#map_leaflet_1').each(function(){
		$(this).addClass('infobox');
		// Move the infobox to the top of the HTML
		$(this).prependTo('.mw-info-column');
	});

	// Move breadcrumb
	$('#contentSub').each(function(){
		$(this).prependTo('.mw-content-column');
	})

	// Move Table of Contents
	$('#toc').each(function(){
		$(this).appendTo('.mw-info-column');
	})

});