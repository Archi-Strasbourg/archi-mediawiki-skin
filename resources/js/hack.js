
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
	} else {
		// There is no image, add the no-image class to infobox 
		$('.infobox').addClass('no-header-image');
	}
});

$(document).ready(function(){
	
	// Wrap all content html in a nice div
	if (!$('body').hasClass('ns--1') && !$('body').hasClass('ns-4')) {

		$('.mw-body').each(function(){
			$(this).wrapInner('<div class="mw-content-column"></div>');
			$(this).prepend('<div class="mw-info-column"></div>');
		});
		// Add infobox class to infobox table on load
		$('#mw-content-text>table').has('#map_leaflet_1').each(function(){
			$(this).addClass('infobox');
			// Move the infobox to the top of the HTML
			$(this).prependTo('.mw-info-column');
		});

		// Move breadcrumb
		$('#contentSub').each(function(){
			$(this).prependTo('.mw-content-column');
		});

		// Move eleemnts to infobox row
		var $elementsToMove = $('#toc, .translations');
		$elementsToMove.each(function(){
			$(this).appendTo('.mw-info-column');
		});

			
	}

	// Remove all empty 'hr' from table in infobox 
	$('.infobox tr').each(function(){
		if ($(this).find('td').html() == '<hr>') {
			$(this).prev().addClass('end-section');
			$(this).remove();
		}
	});

	// Move comments form to bottom of comments rather than top 
	$('.comments-body form[name="commentForm"]')
		.appendTo('.comments-body')
		.wrap('<div class="comment-form"></div>').each(function(){
			var $commentForm = $(this).closest('.comment-form');
			$commentForm.before('<a href="javascript:void(0);" class="showhide-comments">' + $(this).find('.c-form-title').text() + '</a>');
		}).each(function(){
			$(this).closest('.comment-form').siblings('.showhide-comments').click(function(){
				$(this).next().slideToggle();
			});
	});

	/**
	 * HOMEPAGE things 
	 */
	// $('.mw-special-ArchiHome').find('#mw-content-text').find('h2').first().addBack().nextUntil('a:contains(Discover), a:contains(Découvrir), a:contains(Entdecken)').wrapAll('<div class="spotlight-on"></div>');

});