/**
 * JS for navigation menus
 */

 jQuery(document).ready(function($){

 	// Openclose function
 	$('[data-openclose]').click(function(){
 		var selector = $(this).attr('data-target');
 		var $target = $(selector);
 		var $this = $(this);
		var $context = $this.parents('[data-openclose-context]');


 		// Close other open toggles in context
 		if ($context.length > 0) {
 			$context.find('[data-openclose].toggled').not($this).click();
 		}
 		$target.toggleClass('hide');
 		$this.toggleClass('toggled');

 		// Change color to target background-color if setting is true
 		if ($this.attr('data-openclose-colorchange') === 'true') {

 			if ($this.hasClass('toggled')) {
	 			// Save current background color
	 			$this.attr('data-original-color', $this.css('background-color'));

	 			// Set to target background color
	 			$this.css('background-color', $target.css('background-color'));
 			} else {
	 			$this.css('background-color', $this.attr('data-original-color'));
 			}
 		}
 	});

 	// Close on blur from header
 	$('[data-openclose-context]').blur(function(){
 		$(this).find('[data-openclose].toggled').click();
 	});


 	// Set language module
 	$('[data-set-language]').click(function(){
		var language_code = $(this).attr('data-set-language');
		mw.uls.changeLanguage(language_code);
		return false;
 	});

 	// Submit search form
 	$('.form-submit').click(function(){
 		$(this).closest('form').submit();
 	});
 });
