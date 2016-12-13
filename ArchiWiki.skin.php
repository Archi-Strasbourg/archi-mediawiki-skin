<?php
/**
 * SkinTemplate class for the ArchiWiki skin
 *
 * @ingroup Skins
 */
class SkinArchiWiki extends SkinTemplate {
	public $skinname = 'archiwiki', $stylename = 'ArchiWiki',
		$template = 'ArchiWikiTemplate', $useHeadElement = true;

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	public function initPage( OutputPage $out ) {

		$out->addMeta( 'viewport', 'width=device-width, initial-scale=1.0' );

		$out->addModuleStyles( array(
			// 'mediawiki.skinning.interface',
			// 'mediawiki.skinning.content.externallinks',
			'skins.archiwiki'
		) );
		$out->addStyle( "//fonts.googleapis.com/css?family=Montserrat:700|Open+Sans:400,400i,600" );
		$out->addModules( array(
			'skins.archiwiki.js'
		) );
	}

	/**
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
	}
}
