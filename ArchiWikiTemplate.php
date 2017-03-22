<?php
/**
 * BaseTemplate class for the Example skin
 *
 * @ingroup Skins
 */
class ArchiWikiTemplate extends BaseTemplate {

	/**
	 * Available langauges to display on website
	 * @var array
	 */
	private $availableLanguages = array(
			"Français"		=> "fr",
			"English" 		=> "en",
			"Deutsch" 		=> "de"
		);

	/**
	 * Social networks
	 */
	private $socialNetworks = array(
		"twitter" 	=> array(
			'handle' 	=> '@ArchiStrasbourg',
			'url' 		=> 'https://twitter.com/ArchiStrasbourg',
			'icon'		=> '<i class="icon ion-social-twitter"></i>',
		),
		"facebook" 	=> array(
			'handle' 	=> 'Archi.Strasbourg',
			'url' 		=> 'https://fr-fr.facebook.com/Archi.Strasbourg/',
			'icon'		=> '<i class="icon ion-social-facebook"></i>',
		),
		"instagram" 	=> array(
			'handle' 	=> 'archi_strasbourg',
			'url' 		=> 'https://www.instagram.com/archi_strasbourg/',
			'icon'		=> '<i class="icon ion-social-instagram"></i>',
		)
	);

	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {

		global $wgOut;

		$this->html( 'headelement' );
		?>
		<div class="wrapper row column">
			
			<!-- Menu -->

			<?php $this->getHeaderBar(); ?>

			<!-- Header image is inserted with Javascript -->
			<div class="header-image-continer hide" id="header-image" style="background-image:url('http://placehold.it/1899x900');">
			</div>
		
			<div class="mw-body" role="main">
				<?php
				if ( $this->data['sitenotice'] ) {
					echo Html::rawElement(
						'div',
						array( 'id' => 'siteNotice' ),
						$this->get( 'sitenotice' )
					);
				}
				if ( $this->data['newtalk'] ) {
					echo Html::rawElement(
						'div',
						array( 'class' => 'usermessage' ),
						$this->get( 'newtalk' )
					);
				}
				echo $this->getIndicators();
				echo Html::rawElement(
					'h1',
					array(
						'class' => 'firstHeading',
						'lang' => $this->get( 'pageLanguage' )
					),
					$this->get( 'title' )
				);

				echo Html::rawElement(
					'div',
					array( 'id' => 'siteSub' ),
					$this->getMsg( 'tagline' )->parse()
				);
				?>

				<div class="mw-body-content">

					<?php echo $this->getArchiWikiToolbox(); ?>

					<?php
					echo Html::openElement(
						'div',
						array( 'id' => 'contentSub' )
					);
					if ( $this->data['subtitle'] ) {
						echo Html::rawelement (
							'p',
							[],
							$this->get( 'subtitle' )
						);
					}
					echo Html::rawelement (
						'p',
						[],
						$this->get( 'undelete' )
					);
					echo Html::closeElement( 'div' );

					$this->html( 'bodycontent' );
					$this->clear();
					echo Html::rawElement(
						'div',
						array( 'class' => 'printfooter' ),
						$this->get( 'printfooter' )
					);
					$this->html( 'catlinks' );
					$this->html( 'dataAfterContent' );
					?>
				</div>
			</div>

			<div id="mw-navigation">

				<?php echo $this->getTranslations(); ?>
				<?php
				// echo Html::rawElement(
				// 	'h2',
				// 	[],
				// 	$this->getMsg( 'navigation-heading' )->parse()
				// );

				// echo $this->getLogo();
				// echo $this->getSearch();

				// User profile links
				// echo Html::rawElement(
				// 	'div',
				// 	array( 'id' => 'user-tools' ),
				// 	$this->getUserLinks()
				// );

				// Page editing and tools
				echo Html::rawElement(
					'div',
					array( 'id' => 'page-tools' ),
					$this->getPageLinks()
				);

				// Site navigation/sidebar
				// echo Html::rawElement(
				// 	'div',
				// 	array( 'id' => 'site-navigation' ),
				// 	$this->getSiteNavigation()
				// );
				?>
			</div>

		</div>


		<div id="mw-footer">
			<div class="row">
				<div class="small-12 medium-12 large-3 text-center columns">
					<a href="<?php echo $this->getSiteUrl();?>"><img src="<?php echo $this->getSkin()->getSkinStylePath( 'resources/img/logo_archi_wiki-white.png' )?>"/></a>
					<?php foreach ( $this->getFooterLinks() as $category => $links ) {
						echo Html::openElement(
							'ul',
							array(
								'id' => 'footer-' . Sanitizer::escapeId( $category ),
								'role' => 'contentinfo'
							)
						);
						foreach ( $links as $key ) {
							echo Html::rawElement(
								'li',
								array(
									'id' => 'footer-' . Sanitizer::escapeId( $category . '-' . $key )
								),
								$this->get( $key )
							);
						}
						echo Html::closeElement( 'ul' );
					} ?>
				</div>
				<div class="small-12 medium-12 large-6 text-center columns">
					<h2><?php echo $this->getMsg('partner-contributions-title'); ?></h2>
					<div class="partners">
						<?php echo $wgOut->parse('{{Partenaire_logos}}'); ?>
					</div>

				</div>
				<div class="small-12 medium-12 large-3 columns">
					<?php foreach ($this->socialNetworks as $network) : ?>
						<a href="<?php echo $network['url'];?>" target="_blank" class="button expanded large hollow"><?php echo $network['icon'];?> <?php echo $network['handle'];?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<?php $this->printTrail() ?>
		</body>
		</html>

		<?php
	}


	/**
	 * Generates the side floating toolbox of buttons for changing the article
	 * @return string html
	 */
	private function getArchiWikiToolbox() {

		$toolBoxItems = array(
			've-edit' => array(
				'icon' => '<i class="material-icons">mode_edit</i>'
			),
			'watch' => array(
				'icon' => '<i class="material-icons">star_border</i>'
			),
			'more'	=> array(
				'history' => array(
					'icon'	=> '<i class="material-icons">timer</i>'
				),
				'edit' => array(
					'icon'	=> '<i class="material-icons">code</i>'
				),
				'print' => array(
					'icon'	=> '<i class="material-icons">print</i>'
				)
			)
		);

		$moreToolBoxData = array(
			'more' 	=> array(
				'href'	=> '#',
				'class'	=> 'archiwiki-toolbox-more',
				'id'	=> 'archiwiki-toolbox-more',
				'text'	=> $this->getMsg('more-message')->text()
			)
		);
		$printToolBoxData = array(
			'print'	=> array(
				'href'	=> $this->getThisPageUrl() . '&printable=yes',
				'class'	=> 'archiwiki-toolbox-print',
				'id'	=> 'archiwiki-toolbox-print',
				'text'	=> $this->getMsg('Print')->text()
			)
		);
		$toolboxData = array_merge($this->data['content_navigation']['views'], $this->data['content_navigation']['actions'], $printToolBoxData);
		$innerHTML = '';
		foreach( $toolBoxItems as $key => $item ) {
			if ( $key === 'more' ) {
				$moreInfo = array(
					'icon'	=> '<i class="material-icons">settings</i>'
				);
				$innerHTML .= $this->buildToolBoxItem( 'more' , $moreInfo, $moreToolBoxData);
				foreach ( $item as $moreItemKey => $moreItem ) {
					$moreHTML .= $this->buildToolBoxItem( $moreItemKey, $moreItem, $toolboxData );
				}
				$innerHTML .= Html::rawElement('ul', array('class' => 'archiwiki-toolbox-submenu'), $moreHTML);
			} else {
				$innerHTML .= $this->buildToolBoxItem( $key, $item, $toolboxData);
			}
			
		}
		if (!empty($innerHTML)) {
			$html = Html::rawElement(
			'ul',
			array(
					'class' 	=> 'archiwiki-toolbox'
				),
			$innerHTML);
		}

		return $html;
	}

	private function buildToolBoxItem( $key, $item, $toolboxData ){
		if (isset($toolboxData[$key]) && !empty($toolboxData[$key])) {
			$itemData = $toolboxData[$key];
			return Html::rawElement(
				'li',
				array(
					'class'	=> 'archiwiki-toolbox-item'
				),
				Html::rawElement(
					'a',
					array(
						'href'	=> $itemData['href'],
						'class' => $itemData['class'],
						'id'	=> $itemData['id']
					),
					Html::rawElement('span', [], $itemData['text'] ). ' ' . $item['icon']
				)
			);
		} else {
			return '';
		}
	} 
	/**
	 * Generates a single sidebar portlet of any kind
	 * @return string html
	 */
	private function getPortlet( $box ) {
		if ( !$box['content'] ) {
			return;
		}

		$html = Html::openElement(
			'div',
			array(
				'role' => 'navigation',
				'class' => 'mw-portlet',
				'id' => Sanitizer::escapeId( $box['id'] )
			) + Linker::tooltipAndAccesskeyAttribs( $box['id'] )
		);
		$html .= Html::element(
			'h3',
			[],
			isset( $box['headerMessage'] ) ? $this->getMsg( $box['headerMessage'] )->text() : $box['header'] );
		if ( is_array( $box['content'] ) ) {
			$html .= Html::openElement( 'ul' );
			foreach ( $box['content'] as $key => $item ) {
				$html .= $this->makeListItem( $key, $item );
			}
			$html .= Html::closeElement( 'ul' );
		} else {
			$html .= $box['content'];
		}
		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Generates the logo and (optionally) site title
	 * @return string html
	 */
	private function getLogo( $id = 'p-logo', $imageOnly = false ) {
		$html = Html::openElement(
			'div',
			array(
				'id' => $id,
				'class' => 'mw-portlet',
				'role' => 'banner'
			)
		);
		$html .= Html::element(
			'a',
			array(
				'href' => $this->data['nav_urls']['mainpage']['href'],
				'class' => 'mw-wiki-logo',
			) + Linker::tooltipAndAccesskeyAttribs( 'p-logo' )
		);
		if ( !$imageOnly ) {
			$html .= Html::element(
				'a',
				array(
					'id' => 'p-banner',
					'class' => 'mw-wiki-title',
					'href'=> $this->data['nav_urls']['mainpage']['href']
				) + Linker::tooltipAndAccesskeyAttribs( 'p-logo' ),
				$this->getMsg( 'sitetitle' )->escaped()
			);
		}
		$html .= Html::closeElement( 'div' );

		return $html;
	}
	private function getThisTitle() {
		return Title::newFromText($this->data['thispage']);
	}

	private function getThisPageUrl() {
		$title = $this->getThisTitle();
		return $title->getFullURL();
	}

	/**
	 * Generates the search form
	 * @return string html
	 */
	private function getSearch() {
		$html = Html::openElement(
			'form',
			array(
				'action' => htmlspecialchars( $this->get( 'wgScript' ) ),
				'role' => 'search',
				'class' => 'mw-portlet',
				'id' => 'p-search'
			)
		);
		$html .= Html::hidden( 'title', htmlspecialchars( $this->get( 'searchtitle' ) ) );
		$html .= Html::rawelement(
			'h3',
			[],
			Html::label( $this->getMsg( 'search' )->escaped(), 'searchInput' )
		);
		$html .= $this->makeSearchInput( array( 'id' => 'searchInput' ) );
		$html .= $this->makeSearchButton( 'go', array( 'id' => 'searchGoButton', 'class' => 'searchButton' ) );
		$html .= Html::closeElement( 'form' );

		return $html;
	}

	/**
	 * Generates the sidebar
	 * Set the elements to true to allow them to be part of the sidebar
	 * @return string html
	 */
	private function getSiteNavigation() {
		$html = '';

		$sidebar = $this->getSidebar();

		$sidebar['SEARCH'] = false;
		$sidebar['TOOLBOX'] = true;
		$sidebar['LANGUAGES'] = true;

		foreach ( $sidebar as $boxName => $box ) {
			if ( $boxName === false ) {
				continue;
			}
			$html .= $this->getPortlet( $box, true );
		}

		return $html;
	}

	/**
	 * Returns an array of active languages except the current language
	 * @return array other languages
	 */
	private function getOtherLanguages() {
		$other_languages = array_filter($this->availableLanguages, function($value){
			return $value !== $this->data['lang'];
		});

		return $other_languages;	
	}

	/**
	 * Displays the language menu chooser <li> items
	 * @return string html
	 */
	private function getLanguageMenuItems() {
		$html = '';
		foreach ($this->getOtherLanguages() as $language_name => $language_code) {
			$url = wfAppendQuery($this->getThisPageUrl(), array( 'uselang' => $language_code ) );
			$html .= "<li><a href='$url' data-set-language='$language_code'>$language_name</a></li>";
		}

		return $html;	
	}
	
	
	/**
	 * Generates the top header bar
	 * @return string html
	 */
	private function getHeaderBar(){
		global $wgOut;
		?>
		<header class="site-header" id="site-header" data-openclose-context>
			<!-- Title Bar -->
			<div class="title-bar" data-responsive-toggle="main-navigation-mobile" data-hide-for="large">
				<div class="title-bar-title title-bar-logo"><a href="<?php echo $this->getSiteUrl();?>"><img src="<?php echo $this->getSkin()->getSkinStylePath( 'resources/img/logo_archi_wiki-white.png' )?>"/></a></div>
				<button class="" type="button" data-toggle><i class="material-icons">menu</i><?php echo $this->getMsg( 'menu' ); ?></button>
			</div>
			
			<div class="hide-for-large">
				<?php $this->getNavigation( true ); ?>
			</div>

			<!-- Desktop nav -->
			<div class="show-for-large">
				<div class="top-bar top-bar-inverted">
					<div class="row column">
						
						<div class="top-bar-left">
							<div class="site-logo">
								<a href="<?php echo $this->getSiteUrl();?>"><img src="<?php echo $this->getSkin()->getSkinStylePath( 'resources/img/logo_archi_wiki.png' )?>"/></a>
								<p class="site-slogan"><?php echo $this->getMsg('site-slogan');?></p>
							</div>
						</div>
						<div class="top-bar-left">
							<ul class="menu">
								<?php echo $wgOut->parse('{{MenuQuickLinks}}'); ?>
								<?php echo $this->getLanguageMenuItems();?>
							</ul>
						</div>
						<div class="top-bar-right">
							
							<ul class="menu">
								<li><a class="search-button" data-openclose data-target="#search-modal" data-openclose-colorchange="true"><i class="material-icons">search</i></a></li>
								<li><a class="menu-button" data-openclose data-target="#main-navigation" data-openclose-colorchange="true"><i class="material-icons">menu</i><?php echo $this->getMsg( 'menu' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>

				<?php $this->getNavigation( false ); ?>

				<?php $this->getSearchModal();?>
			</div>
		</header>



		<?php
	}

	private function getNavigation( $mobile = false ) {

		global $wgOut;
		global $wgUser;
		global $wgTitle;

		?>
			
			<!-- Navigation -->
			<nav class="main-navigation mega-menu <?php echo ($mobile ? '' : 'hide'); ?> " id="main-navigation<?php echo ($mobile ? '-mobile' : ''); ?>">
				<div class="row">
					<div class="column small-12 large-3">
						<ul class="menu vertical" <?php echo ($mobile ? 'data-accordion-menu': '' );?> >
							<li>
								<a href="#"><?php echo $this->getMsg( 'association' ); ?></a>
								<?php echo $wgOut->parse('{{MenuAssociation}}'); ?>
							</li>
						</ul>
					</div>
					<div class="column small-12 large-4">
						<ul class="menu vertical" <?php echo ($mobile ? 'data-accordion-menu': '' );?> >
							<li>
								<a href="#"><?php echo $this->getMsg( 'contribuer' ); ?></a>
								<?php $this->getContributionMenu(); ?>
							</li>
						</ul>
					</div>
					<div class="column small-12 large-3">
						<?php if ($wgUser->mId > 0) :?>
							<ul class="menu vertical" <?php echo ($mobile ? 'data-accordion-menu': '' );?> >
								<li>
									<a href="#"><?php echo $this->getMsg( 'profile' ); ?></a>
									<?php $this->getProfileMenu( ); ?>
								</li>
							</ul>
						<?php endif; ?>
					</div>
					<!-- Mobile only items -->
					<div class="columns small-12 hide-for-large">
						<ul class="menu vertical">
							<li><a href="<?php echo Title::newFromText('Open Data')->getFullURL(); ?>"><?php echo $this->getMsg('open-data');?></a></li>
							<li><a href="<?php echo Title::newFromText('Faire un don')->getFullURL(); ?>"><?php echo $this->getMsg('donate');?></a></li>
							<li><a href="#" class="uls-trigger"><?php echo $this->getMsg('change-language');?></a></li>
						</ul>
					</div>
					<!-- /Mobile Only Items -->
					<div class="column small-12 large-2">
						<ul class="menu vertical" <?php echo ($mobile ? 'data-accordion-menu': '' );?> >
							<li>
								<?php $this->getProfile(); ?>
							</li>
						</ul>
					</div>
				</div>
			</nav>

		<?php
	}

	private function getSearchModal( $startHidden = true ) {

		?>
			
			<!-- Navigation -->
			<nav class="search-modal color-panel color-panel-earth <?php echo ($startHidden ? 'hide' : '');?>" id="search-modal">
				<div class="row">
					<div class="column">
						<h3 class="text-center search-title">
							<?php echo $this->getMsg('search-title');?>
						</h3>
					</div>
				</div>
				<div class="row">
					<div class="column large-7 large-offset-2">
						<form action="<?php echo $this->get( 'wgScript' ) ?>">
							<div class="input-group">
								<input type="search" class="search-input input-group-field" placeholder="<?php echo $this->getMsg('search-placeholder');?>" name="search">
								<div class="input-group-button">
									<a class="button" class="form-submit">
										<i class="material-icons">search</i>
									</a>
								</div>
							</div>
						</form>
					</div>
					<div class="column large-2 end">
						<a href="<?php echo wfAppendQuery(Title::newFromText('Spécial:Recherche')->getFullURL(), array('profile' => 'advanced')) ;?>" class="advanced-search-link"><?php echo $this->getMsg('advanced-search');?></a>
					</div>
				</div>
			</nav>

		<?php
	}


	/**
	 * Generates page-related tools/links
	 * @return string html
	 */
	private function getPageLinks() {
		$html = $this->getPortlet( array(
			'id' => 'p-namespaces',
			'headerMessage' => 'namespaces',
			'content' => $this->data['content_navigation']['namespaces'],
		) );
		$html .= $this->getPortlet( array(
			'id' => 'p-variants',
			'headerMessage' => 'variants',
			'content' => $this->data['content_navigation']['variants'],
		) );
		$html .= $this->getPortlet( array(
			'id' => 'p-views',
			'headerMessage' => 'views',
			'content' => $this->data['content_navigation']['views'],
		) );
		$html .= $this->getPortlet( array(
			'id' => 'p-actions',
			'headerMessage' => 'actions',
			'content' => $this->data['content_navigation']['actions'],
		) );

		return $html;
	}

	private function getSiteURL() {
		return $this->data['serverurl'] . $this->data['scriptpath'];
	}
	

	private function getContributionMenu() {
		
		global $wgOut;
		global $wgUser;

		?> 
		<ul class="menu vertical">
			<?php if ( in_array( 'createpage', $wgUser->mRights )) :?>
				<li><a href="<?php echo Title::newFromText('Spécial:AjouterPage')->getFullURL(); ?>"><?php echo $this->getMsg('create-page');?></a></li>
			<?php endif;?>
			<?php if ( in_array( 'createpage', $wgUser->mRights )) :?>
				<li><a href="<?php echo wfAppendQuery($this->getThisPageUrl(), ['action'=>'vedit']) ?>"><?php echo $this->getMsg('edit-page');?></a></li>
				<li><a href="<?php echo wfAppendQuery($this->getThisPageUrl(), ['action'=>'edit']) ?>"><?php echo $this->getMsg('edit-page-code');?></a></li>
			<?php endif;?>
			<li><a href="<?php echo wfAppendQuery($this->getThisPageUrl(), ['action'=>'history']) ?>"><?php echo $this->getMsg('get-page-history');?></a></li>
			<?php $contribution_title = Title::newFromText('Aide à la contribution');?>
			<li><a href="<?php echo $contribution_title->getFullURL();?>"><?php echo $contribution_title->getText();?></a></li>
		</ul>

		<?php
	}

	private function getProfileMenu() {
		global $wgUser;

		?>
		<ul class="menu vertical">
			<li><a href="<?php echo Title::newFromText('Spécial:Liste_de_suivi')->getFullURL();?>"><?php echo $this->getMsg('followed-list');?></a></li>
			<li><a href="<?php echo $this->getPersonalTools()['mycontris']['links'][0]['href']; ?>"><?php echo $this->getMsg('contribs-list');?></a></li>
			<li><a href="<?php echo Title::newFromText('Utilisateur:'.$wgUser->mName.'/Brouillon')->getFullURL(); ?>"><?php echo $this->getMsg('user-sandbox');?></a></li>
			<li><a href="<?php echo Title::newFromText('Discussion_utilisateur:'.$wgUser->mName)->getFullURL(); ?>"><?php echo $this->getMsg('user-discussion');?></a></li>
			<li><a href="<?php echo $this->getPersonalTools()['preferences']['links'][0]['href']; ?>"><?php echo $this->getMsg('your-prefs');?></a></li>
		</ul>

		<?php
	}

	private function getProfile() {
		global $wgUser;
		global $wgOut;
		
		?>

		<div class="profile-box">
			<?php if ($wgUser->mId > 0) : ?> 
				<?php $avatar_url = $this->getUserAvatar(115); ?>
				<?php if ($avatar_url) : ?>
					<img src="<?php echo $avatar_url ?>" alt="Avatar <?php echo $wgUser->mRealName; ?>" />
				<?php else : ?>
					<i class="material-icons">account_circle</i>
				<?php endif;?>
			<?php else : ?>
				<i class="material-icons">account_circle</i>
			<?php endif; ?>
				<ul class="menu vertical">
					<?php if ($wgUser->mId > 0) : ?> 
						<li><a href="<?php echo Title::newFromText('Utilisateur:'.$wgUser->mName)->getFullURL();?>"><?php echo $this->getMsg('your-profile');?></a></li>
						<li><a href="<?php echo $this->getPersonalTools()['logout']['links'][0]['href']; ?>"><?php echo $this->getMsg('log-out');?></a></li>
					<?php else : ?>
						<li><a href="<?php echo $this->getPersonalTools()['createaccount']['links'][0]['href']; ?>"><?php echo $this->getMsg('create-account');?></a></li>
						<li><a href="<?php echo $this->getPersonalTools()['login']['links'][0]['href']; ?>"><?php echo $this->getMsg('log-in');?></a></li>
					<?php endif; ?>
				</ul>
		</div>
		<?php

	}

	/**
	 * Generates user tools menu
	 * @return string html
	 */
	private function getUserLinks() {
		return $this->getPortlet( array(
			'id' => 'p-personal',
			'headerMessage' => 'personaltools',
			'content' => $this->getPersonalTools(),
		) );
	}

	/**
	 * Outputs a css clear using the core visualClear class
	 */
	private function clear() {
		echo '<div class="visualClear"></div>';
	}

	/**
	 * Get the current user avatar
	 * @param  int $width Desired width in pixels
	 * @return string|null Relative URL
	 */
	private function getUserAvatar($width) {
		global $wgUser, $wgRequest;
		$userPagename = 'Utilisateur:'.$wgUser->getName();
		$userTitle = Title::newFromText($userPagename);
		$api = new \ApiMain(
			new \DerivativeRequest(
				$wgRequest,
				array(
					'action'=>'ask',
					'query'=>'[['.$userPagename.']]|?Avatar'
				)
			)
		);
		$api->execute();
		$results = $api->getResult()->getResultData();
		if (isset($results['query']['results'][$userPagename]['printouts']['Avatar'][0])) {
			$avatar = $results['query']['results'][$userPagename]['printouts']['Avatar'][0];
			$avatarTitle = Title::newFromText($avatar['fulltext']);
			$avatarFile = wfFindFile($avatarTitle->getText());

			return $avatarFile->createThumb($width);
		}
	}

	private function getTranslations() {
		if ($this->getThisTitle()->mNamespace >= 0) {
			$translationsHTML = '';
			foreach ($this->availableLanguages as $language => $code) {
				$translationsHTML .= Html::rawElement('li', array(), 
						Html::rawElement('a', array(
								'href'		=> $this->getThisPageUrl().'/'.$code
							),
							$language)
					);
			}
			// echo $translationsHTML;
			$listHTML = Html::rawElement(
				'ul',
				array(
					'class' 	=> 'translations-list'
					),
				$translationsHTML);
			$html = Html::rawElement( 
				'div',
				array( 'class' => 'translations' ),
				Html::rawElement(
					'h2',
					array('class' => 'translations-title'),
					$this->getMsg('translations-title')
					)
				.$listHTML
			);
		} else {
			$html = '';
		}
		

		

		return $html;
		
	}
}
