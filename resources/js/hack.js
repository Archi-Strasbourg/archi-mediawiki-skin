/* jshint esversion: 6 */

/**
 * HACKY THINGS
 */

/**
 * @param $featuredImage
 * @param $container
 * @return {boolean}
 */
function setupHeaderImage($featuredImage, $container) {
	const $featuredThumb = $featuredImage;
	let featuredThumbUrl = $featuredThumb.attr('src');
	if (typeof featuredThumbUrl != 'undefined') {
		let featuredImageUrl;

		if (featuredThumbUrl.substr(0, featuredThumbUrl.lastIndexOf('thumb') >= 0)) {
			featuredThumbUrl = featuredThumbUrl.substr(0, featuredThumbUrl.lastIndexOf('/'));
			featuredImageUrl = featuredThumbUrl.replace(/\/thumb/, '');
		} else {
			featuredImageUrl = featuredThumbUrl;
		}
		$container.removeClass('hide');
		
		$container.find("#header-image2").first().css({
			backgroundImage: 'url(' + featuredImageUrl + ')'
		});
		$featuredThumb.parents('.thumb').hide();
		return true;
	} else {
		return false;
	}
}
// Set up header image
$(document).ready(function(){
	// Generic article page
	if ( setupHeaderImage($('#mw-content-text').find('img.main-image').first(), $('#header-image') ) ) {
		return;
	} else {
		// There is no image, add the no-image class to infobox
		$('.infobox').addClass('no-header-image');
	}
});

$(document).ready(function(){

	// Wrap all content html in a nice div
	if ($('body').is('.ns-4000, .ns-4100, .ns-4002, .ns-4004, .ns-4006') && !$('body').is('.action-edit, .action-vedit, .action-history') && (!mw.Uri || (mw.Uri && !mw.Uri().query.veaction))) {

		$('.mw-body').each(function(){
			$(this).wrapInner('<div class="mw-content-column"></div>');
			$(this).prepend('<div class="mw-info-column"></div>');
		});
		$('.mw-body').addClass('has-archi-columns');

		const $tables = $('#mw-content-text > .mw-parser-output > table, #mw-content-text > table');
		// Add infobox class to infobox table on load
		$tables.has('#map_leaflet_1').each(function(){
			$(this).addClass('infobox');
			// Move the infobox to the top of the HTML
			$(this).prependTo('.mw-info-column');
		});

		// Find infobox on Person page
		$tables.filter('.infobox').each(function(){
			// Move the infobox to the top of the HTML
			$(this).prependTo('.mw-info-column');
		});

		// Move breadcrumb
		$('#contentSub').each(function(){
			$(this).prependTo('.mw-content-column');
		});

		// Move elements to infobox row
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
		.prependTo('.comments-body')
		.wrap('<div class="comment-form"></div>').each(function(){
			var $commentForm = $(this).closest('.comment-form');
			$commentForm.before('<a href="javascript:void(0);" class="showhide-comments">' + $(this).find('.c-form-title').text() + '</a>');
		}).each(function(){
			$(this).closest('.comment-form').siblings('.showhide-comments').click(function(){
				$(this).next().slideToggle();
			});
	});
	// Put the "modifier critères" button in the right place
	$('#modifier_criteres').appendTo('#boutons-vers-recherche');
	/**
	 * HOMEPAGE things
	 */
	// Setup header image on Latest-changes
	 $('.latest-changes .latest-changes-recent-change').each(function(){
	 	var $headerImage = $(this).find('a.image>img').first();
		var url = $(this).find('p > a').attr('href');
		$(this).prepend('<a href="' + url + '"><div class="header-image hide"><div id="header-image2"></div></div></a>').find('.header-image').each(function(){
	 		setupHeaderImage($headerImage, $(this));
	 	});
	 });

	 $('.mw-special-ArchiHome').each(function(){
	 	$(this).find('#mw-content-text .header-row').prepend('<div class="header-image-continer" style=""><div id="header-image" class="hide"><div id="header-image2"></div></div></div>');
	 	setupHeaderImage( $(this).find('#mw-content-text').find('a.image>img').first(), $(this).find('#header-image') );
	 });

	 //Force reload page after Visual Editor is closed
	 mw.hook('ve.deactivationComplete').add(
		 function () {
			 window.location.reload();
		 }
	 );

	 //Force ULS mobile mode
	 mw.hook('mw.uls.settings.open').add(function () {
		 setTimeout(function () {
			 $('.uls-menu').addClass('uls-mobile');
		 }, 500);
	 });

	
	

	// make the "Afficher sur la carte" button work
	if($('#Toggle-map-table-view').length > 0) {
		setTimeout(function() {
			$('#map-view').hide();
		}, 500);

		document.getElementById('Toggle-map-table-view').addEventListener('click', function() {
			var tableView=document.getElementById('table-view');
			var mapView=document.getElementById('map-view');
			if (tableView.style.display === 'none') {
				tableView.style.display = 'unset';
				mapView.style.display = 'none';
				this.innerHTML = 'Afficher sous forme de carte';
			} else {
				tableView.style.display = 'none';
				mapView.style.display = 'unset';
				this.innerHTML = 'Afficher sous forme de tableau';
		    }
    	});
		$('#boutons-vers-recherche').append(document.getElementById('Toggle-map-table-view'));
	}

	
	// Show the "Afficher sur la carte" button on the advanced research page
	if($('#boutons-mode-recherche').is('.bouton-normal')){
		$('#bouton-recherche-avancée-carte').show();
	}

	// Add a button to send a test mail to a target
	$('#send-mail').click(function() {
		function MyDialog(config) {
			MyDialog.super.call(this, config);
		}
		OO.inheritClass(MyDialog, OO.ui.Dialog);
		MyDialog.static.name = 'envoir mail test';
		MyDialog.static.title = 'envoyer un mail de test';
		var input1= new OO.ui.TextInputWidget({
			label: 'Cible du mail',
			placeholder: 'Renseigner qui va recevoir le mail',
			required: true
		});
		var input2= new OO.ui.TextInputWidget({
			label: 'Nom d\'utilisateur',
			placeholder: 'Renseigner votre nom d\'utilisateur',
			required: true
		});
		var input3= new OO.ui.TextInputWidget({
			label: 'Password',
			placeholder: 'Renseigner votre mot de pass',
			required: true,
			type: 'password'
		});

		
		var sendButton = new OO.ui.ButtonWidget({
			label: 'envoie',
			flags: 'primary'
		});
		sendButton.on('click', function() {
			if (input1.getValue()=='' || input2.getValue()=='' || input3.getValue()=='') {
				alert("renseignez tout les champs");
				return;
			}
			const target = input1.getValue();
			const user = input2.getValue();
			const password = input3.getValue();
			$.ajax({
				url: 'cli.php',
				type: 'GET',
				data: {
					'groupby': 'parentheses',
					'apiUrl' : 'https://www.archi-wiki.org/api.php',
					'title' : '[TEST] Alerte mail hebdomadaire',
					'username' : user,
					'password' : password,
					'namespaces' : '4000,4006',
					'nsgroupby' : '4000',
					'intro' : 'MediaWiki:Alerte mail hebdomadaire',
					'target' : target,
					'debug' : true
				},
				success: function(data) {
					console.log(data);
					if(data!="success"){
						alert("erreur lors de l'envoie du mail");
						dialog.close();
					}
					else{
						alert("mail envoyé");
						dialog.close();
					}
				},
				error: function(data) {
					console.log("error: "+data);
					alert("erreur lors de l'envoie du mail");
					dialog.close();
				}
			});
			
		});

		var contentLayout = new OO.ui.FieldsetLayout({
			'label': 'Envoir d\'un mail de test (échap pour fermer)'
		});
		contentLayout.addItems([new OO.ui.FieldLayout(input1,{align:'top'}), new OO.ui.FieldLayout(input2,{align:'top'}), new OO.ui.FieldLayout(input3,{align:'top'}), sendButton]);

		MyDialog.prototype.initialize = function () {
			MyDialog.super.prototype.initialize.call(this);
			this.content = contentLayout;
			this.$body.append(this.content.$element);
		};
		
		
		var dialog = new MyDialog({size: 'medium'});
		var windowManager= new OO.ui.WindowManager();
		$('body').append(windowManager.$element);
		windowManager.addWindows([dialog]);
		windowManager.openWindow(dialog);
	});
});
