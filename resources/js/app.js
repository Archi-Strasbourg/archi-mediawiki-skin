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
 * Toolbox 
 */
$(document).ready(function(){
	$('.archiwiki-toolbox #archiwiki-toolbox-more').click(function(e){
		$(this).closest('.archiwiki-toolbox').find(' .archiwiki-toolbox-submenu').slideToggle();
		return false;
	});
	$('.archiwiki-toolbox-submenu').click(function(e){
	e.stopPropagation();
	});
});

$(window).click(function(){
	$('.archiwiki-toolbox-submenu').slideUp();
});

