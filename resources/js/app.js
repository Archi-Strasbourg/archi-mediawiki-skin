// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();


/**
 * Navigate to profile pages on change select
 */
$('select.change-navigate').change(function(){
    var $this = $(this);
    window.location.href = $(this).find(':selected').attr('data-href');  
});

/**
 * Submit search forms
 */
$('form').each(function(){
	var $form = $(this);
	$form.find('.submit-button').click(function(){
		$form.submit();
	});
});


/**
 * Set up header image
 */
$(document).ready(function(){
	// Generic article page
	var featuredThumbUrl = $('#mw-content-text').find('a.image>img').first().attr('src');
	if (typeof featuredThumbUrl != 'undefined') {
		featuredThumbUrl = featuredThumbUrl.substr(0,featuredThumbUrl.lastIndexOf('/'));
		var featuredImageUrl = featuredThumbUrl.replace(/\/thumb/, '');
		$('#header-image').css({
			backgroundImage: 'url(' + featuredImageUrl + ')'
		}).removeClass('hide');
	}


});