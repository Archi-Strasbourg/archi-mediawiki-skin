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

/**
 * Loader
 */
$(document).ready(function(){
	$('#loading').fadeOut(800);
});

/**
 * Google Analytics
 */
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-21024687-1', 'auto');
ga('send', 'pageview');
