<?php
/**
 * BaseTemplate class for the Example skin
 *
 * @ingroup Skins
 */
class ArchiWikiTemplate extends BaseTemplate {
	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {
		$this->html( 'headelement' );
		?>
		<div class="wrapper">
			
			<!-- Menu -->

			<?php $this->getHeaderBar(); ?>

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
				<?php
				echo Html::rawElement(
					'h2',
					[],
					$this->getMsg( 'navigation-heading' )->parse()
				);

				echo $this->getLogo();
				echo $this->getSearch();

				// User profile links
				echo Html::rawElement(
					'div',
					array( 'id' => 'user-tools' ),
					$this->getUserLinks()
				);

				// Page editing and tools
				echo Html::rawElement(
					'div',
					array( 'id' => 'page-tools' ),
					$this->getPageLinks()
				);

				// Site navigation/sidebar
				echo Html::rawElement(
					'div',
					array( 'id' => 'site-navigation' ),
					$this->getSiteNavigation()
				);
				?>
			</div>

			<div id="mw-footer">
				<?php
				echo Html::openElement(
					'ul',
					array(
						'id' => 'footer-icons',
						'role' => 'contentinfo'
					)
				);
				foreach ( $this->getFooterIcons( 'icononly' ) as $blockName => $footerIcons ) {
					echo Html::openElement(
						'li',
						array(
							'id' => 'footer-' . Sanitizer::escapeId( $blockName ) . 'ico'
						)
					);
					foreach ( $footerIcons as $icon ) {
						echo $this->getSkin()->makeFooterIcon( $icon );
					}
					echo Html::closeElement( 'li' );
				}
				echo Html::closeElement( 'ul' );

				foreach ( $this->getFooterLinks() as $category => $links ) {
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
				}
				$this->clear();
				?>
			</div>
		</div>

		<?php $this->printTrail() ?>
		</body>
		</html>

		<?php
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

	private function getThisPageURL() {
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
	 * Generates the top header bar
	 * @return string html
	 */
	private function getHeaderBar(){
		?>
			<!-- Title Bar -->
			<div class="title-bar" data-responsive-toggle="main-navigation" data-hide-for="large">
				<div class="title-bar-title title-bar-logo"><img src="<?php echo $this->getSkin()->getSkinStylePath( 'resources/img/logo_archi_wiki-white.png' )?>/"></div>
				<button class="" type="button" data-toggle><i class="material-icons">menu</i><?php echo $this->getMsg( 'menu' ); ?></button>
			</div>
			
			<div class="hide-for-large">
				<?php $this->getNavigation( true ); ?>
			</div>
			<div class="show-for-large">
				<?php $this->getNavigation( false ); ?>
			</div>



		<?php
	}

	private function getNavigation( $mobile = false ) {

		global $wgOut;
		global $wgUser;
		global $wgTitle;

		?>
			
			<!-- Navigation -->
			<nav class="main-navigation mega-menu" id="main-navigation">
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
					<div class="column small-12 large-2">
						<?php if ($wgUser->mId > 0) :?>
							<ul class="menu vertical" <?php echo ($mobile ? 'data-accordion-menu': '' );?> >
								<li>
									<?php $this->getProfile( ); ?>
								</li>
							</ul>
						<?php endif; ?>
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
				<li><a href="<?php echo wfAppendQuery($this->getThisPageURL(), ['action'=>'vedit']) ?>"><?php echo $this->getMsg('edit-page');?></a></li>
				<li><a href="<?php echo wfAppendQuery($this->getThisPageURL(), ['action'=>'edit']) ?>"><?php echo $this->getMsg('edit-page-code');?></a></li>
			<?php endif;?>
			<li><a href="<?php echo wfAppendQuery($this->getThisPageURL(), ['action'=>'history']) ?>"><?php echo $this->getMsg('get-page-history');?></a></li>
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
		
		?>
		<div class="profile-box">
			<img class="profile-box-image profile-image show-for-large" src="http://placehold.it/115x115" width=115 height=115>
			<ul class="menu vertical">
				<li><a href="<?php echo Title::newFromText('Utilisateur:'.$wgUser->mName)->getFullURL();?>"><?php echo $this->getMsg('your-profile');?></a></li>
				<li><a href="<?php echo $this->getPersonalTools()['logout']['links'][0]['href']; ?>"><?php echo $this->getMsg('log-out');?></a></li>
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
}
