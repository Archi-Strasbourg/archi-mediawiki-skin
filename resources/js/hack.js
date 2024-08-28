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

/**
 * Print the iframe when it is loaded
 * @param frame should be the iframe you want to print
 * @param elt is the loading div of the iframe displayed while the iframe is loading
 */
function printElt(frame,elt){
	
	setTimeout(function(){
		if(elt.style.display=="none"){
			$("#loading").hide();
			frame.contentWindow.focus();
			frame.contentWindow.print();
			frame.remove();
			console.log('found and printing');
		}else{
			console.log('waiting for page to load');
			printElt(frame,elt);
		}
	}, 500);
}

function waitForDeferred(id){
	let elt=document.getElementById(id);
	setTimeout(function(){
		if (elt.children.length > 0) {
			printPreviewButton();
		} else {
			console.log('waiting for deferred');
			console.log(elt.children);
			waitForDeferred(id);
		}
	}, 200);
}

function printPreviewButton(){
	//create the pretty preview button with icon
	$('.preview-content').each(function(){
		var classList = $(this).attr('class').split(' ');
		var rowClass = classList.find(function(className) {
			return className.startsWith('row');
		});
		var ul=$('<ul class="preview-content-list archiwiki-toolbox '+rowClass+'" id="preview'+$(this).attr('id')+'"></ul>');
		var span=$('<span>'+$(this).text()+'</span>');
		var i=$('<i class="material-icons">preview</i>');
		var a=$('<a href="#0" onclick="return false;" class="preview-content archiwiki-toolbox-print" id="'+$(this).attr('id')+'"></a>');
		a.append(span);
		a.append(i);
		ul.append($('<li class="archiwiki-toolbox-item"></li>').append(a));
		$(this).replaceWith(ul);
	}); 

	//create the pretty print button with icon
	$('.print-content').each(function(){
		
		
		var span=$('<span>'+$(this).text()+'</span>');
		var i=$('<i class="material-icons">print</i>');
		var a=$('<a href="#0" onclick="return false;" class="print-content archiwiki-toolbox-print" id="'+$(this).attr('id')+'"></a>');
		a.append(span);
		a.append(i);
		var classList = $(this).attr('class').split(' ');
		var rowClass = classList.find(function(className) {
			return className.startsWith('row');
		});
		console.log(rowClass);
		if($(".preview-content-list."+rowClass).length > 0){
			$(".preview-content-list."+rowClass).append($('<li class="archiwiki-toolbox-item"></li>').append(a));
			$(this).remove();
		} else{
			var ul=$('<ul class="preview-content-list archiwiki-toolbox '+rowClass+'"></ul>');
			ul.append($('<li class="archiwiki-toolbox-item"></li>').append(a));
			$(this).replaceWith(ul);
		}
		
	});
	//implémente le bouton preview pour créer une iframe
	$(".preview-content").click(function(e) {
		
		var url = $(this).attr('id');
		
		console.log(url);
		var iframe = document.createElement('iframe');
		iframe.src = url.replace("[[:", "/");
		iframe.style.width="70vw";
		iframe.style.height="100vh";
		iframe.style.position="absolute";
		iframe.style.border="4px solid #d96e5d";
		iframe.style.zIndex="10";
		iframe.style.borderRadius="0px";
		iframe.style.left="3vw";
		iframe.style.translate="0px -20vh";
		var close=$('<p id="close-preview" style="position:absolute; cursor:pointer; padding:5px 10px; background-color:#d96e5d; color:white; border-radius:0px; z-index:11; translate:0px -20vh;left:3vw;">X</p>');
		close.click(function(){
			$('iframe').remove();
			close.remove();
		});
		$(this).closest('tr').append(close);
		$(this).closest('tr').append(iframe);
		e.stopPropagation();
	});

	//implémente le bouton print pour créer une iframe qui permet de print une page
	$('.print-content').click(function(e) {
		$("#loading").show();
		$('<iframe src="'+$(this).attr('id').replace("[[:", "/")+'" style="width:100vw; position:absolute; top:0px; left:0px;" name="frame" id="frame:'+$(this).attr('id')+'"></iframe>').appendTo('div.wrapper');	
		
		var iframe = document.getElementById('frame:' + $(this).attr('id'));
		iframe.onload = function() {
			var elmnt = iframe.contentWindow.document.getElementById("loading");
			
			if (elmnt) {
				printElt(iframe,elmnt);
			} else {
				console.log('elt not found');
			}
		};

		e.stopPropagation();
	});
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
	 $('.mw-special-ArchiHome .latest-changes-recent-change').each(function(){
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

	


	

	// le bouton pour afficher la carte ou le tableau sur les pages de recherche avancée ou catégorie
	if($('#Toggle-map-table-view').length > 0) {
		//attend que la carte se charge correctement avec la bonne taille avant de la cacher
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
				mapView.style.position ='unset';
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

	// implémente le bouton pour envoyer un mail de test
	$('#send-mail').click(function() {
		//paramètre une fenêtre de dialogue avec 3 champs et un bouton pour envoyer le mail
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
		//implémente l'envoie du mail
		sendButton.on('click', function() {
			if (input1.getValue()=='' || input2.getValue()=='' || input3.getValue()=='') {
				alert("renseignez tout les champs");
				return;
			}
			const target = input1.getValue();
			const user = input2.getValue();
			const password = input3.getValue();
			//appel cli.php avec les paramètres récupérés dans la fenêtre dialogue
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
					if (data.includes("success")) {
						alert("mail envoyé");
						dialog.close();
					} else {
						alert("erreur lors de l'envoie du mail");
						dialog.close();
					}
				},
				error: function(data) {
					console.log('error : '+data);
					alert("erreur serveur lors de l'envoie du mail");
					dialog.close();
				}
			});
			
		});

		//créer la fenêtre de dialogue
		var contentLayout = new OO.ui.FieldsetLayout({
			'label': 'Envoir d\'un mail de test (échap pour fermer)'
		});
		contentLayout.addItems([new OO.ui.FieldLayout(input2,{align:'top'}), new OO.ui.FieldLayout(input3,{align:'top'}),new OO.ui.FieldLayout(input1,{align:'top'}), sendButton]);

		MyDialog.prototype.initialize = function () {
			MyDialog.super.prototype.initialize.call(this);
			this.content = contentLayout;
			this.$body.append(this.content.$element);
		};
		
		//affiche la fenêtre de dialogue
		var dialog = new MyDialog({size: 'medium'});
		var windowManager= new OO.ui.WindowManager();
		$('body').append(windowManager.$element);
		windowManager.addWindows([dialog]);
		windowManager.openWindow(dialog);
	});

	//réorganise la page pour l'impression : mets l'infobox et l'image principale au même niveau, en juxtaposition et repositionne le logo du site
	addEventListener("beforeprint", function() {
		
		var UrlImage = $('#header-image2').css('background-image').replace('url(','').replace(')','').replace(/\"/gi, "");
		$(".infobox").wrap('<div id="infobox-div-print"></div>');
		$("#infobox-div-print").prepend('<img src="'+UrlImage+'" id="header-image-print" style="min-width:0px; height:fit-content;">');
		$('#firstHeading').insertBefore('.mw-info-column');
		$('#contentSub').insertBefore('#firstHeading');
		$('.site-logo').insertBefore('#contentSub');
		$('.site-logo').css('width','30vw');
		$('.site-slogan').css('font-size','small');
		$('.site-slogan').css('margin-top','0px');
		$('.site-slogan').css('text-wrap','nowrap');


		console.log(UrlImage);
	});

	//remet la page en état après l'impression
	addEventListener("afterprint", function() {
		$("#header-image-print").remove();
		$('#firstHeading').insertBefore('#siteSub');
		$('#contentSub').insertBefore('#firstHeading');
		$('.top-bar-left').first().append($('.site-logo'));
		$('.site-logo').css('width','');
		$('.site-slogan').css('font-size','');
		$('.site-slogan').css('margin-top','');
		$('.site-slogan').css('text-wrap','');
		$(".infobox").unwrap();

	});

	//toolbox button to print
	$('#archiwiki-toolbox-print').click(function() {
		window.print();
	});

	//démarre le processus pour ajouter des boutons preview et print sur les lignes d'un tableau où c'est possible
	printPreviewButton();

	//close iframe on click
	$(document).on('click', function(e) {
		$('iframe').remove();
		$('#close-preview').remove();
	});

	//dans l'affichage de tableau avec @deferred attend que le tableau s'affiche
	if ($('#deferred-output').length > 0) {
		console.log('deferred-output found');
		
		waitForDeferred('deferred-output');
	}
	
	//cache la section commentaire en mode edit
	if($(".action-edit").length > 0){
		$("#mw-data-after-content").hide();
	} 


	//implémente le bouton pour se désinscrire de l'alerte mail
	$('#bouton-removeAlerteMail').click(function() {
		$.ajax({
			url: 'api.php',
			type: 'GET',
			data: {
				'action': 'RemoveAlerte',
				'user': mw.config.get('wgUserName'),
				'format':'json'
			},
			success: function(data) {
				console.log(data);
				if(data.RemoveAlerte.status == 'ok'){
					alert("désinscrit de l'alerte mail");
					location.reload();
				} else {
					alert("erreur lors de la désinscription de l'alerte mail");
				}
			},
			error: function(data) {
				console.log('error : '+data);
				alert("Erreur serveur lors de la désinscription de l'alerte mail");
			}
		});
	});

	//implémente le bouton pour se réinscrire à l'alerte mail
	$('#bouton-addAlerteMail').click(function() {
		$.ajax({
			url: 'api.php',
			type: 'GET',
			data: {
				'action': 'RemoveAlerte',
				'user': mw.config.get('wgUserName'),
				'cancel':true,
				'format':'json'
			},
			success: function(data) {
				console.log(data);
				if(data.RemoveAlerte.status == 'ok'){
					alert("inscrit à l'alerte mail");
					location.reload();
				} else {
					alert("erreur lors de l'inscription à l'alerte mail");
				}
			},
			error: function(data) {
				console.log('error : '+data);
				alert("Erreur serveur lors de l'inscription à l'alerte mail");
			}
		});
	});


	//permet d'afficher sur la page DésinscriptionAlerteMail du texte qui indique si l'utilisateur est inscrit ou non à l'alerte mail
	if($('#alerteMailStatus').length > 0){
		$.ajax({
			url: 'api.php',
			type: 'GET',
			data: {
				'action': 'query',
				'meta': 'userinfo',
				'uiprop': 'groups|email',
				'format':'json'
			},
			success: function(data) {
				console.log(data);
				if(data.query.userinfo.groups.includes('noAlerteMail')){
					$('#alerteMailStatus').text('désabonné de');
				} else {
					$('#alerteMailStatus').text('abonné à');
				}
				$('#emailAlerteMail').text(data.query.userinfo.email);
			},
			error: function(data) {
				console.log('error : '+data);
				$('#alerteMailStatus').text('Erreur serveur');
			}
		});
	}

});
