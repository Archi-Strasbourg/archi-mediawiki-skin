<?php

/**
 * SkinTemplate class for the ArchiWiki skin
 *
 * @ingroup Skins
 */
class SkinArchiWiki extends SkinTemplate
{
    public $skinname = 'archiwiki', $stylename = 'archi-wiki',
        $template = 'ArchiWikiTemplate', $useHeadElement = true;

    /**
     * Add CSS via ResourceLoader
     *
     * @param $out OutputPage
     */
    public function initPage(OutputPage $out)
    {

        global $wgScriptPath;

        $out->addMeta('viewport', 'width=device-width, initial-scale=1.0');

        // Google Fonts
        $out->addStyle("//fonts.googleapis.com/css?family=Montserrat:700|Open+Sans:400,400i,600");
        $out->addStyle("//fonts.googleapis.com/icon?family=Material+Icons");
        $out->addStyle("//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css");

        //Manually load ULS mobile style
        $out->addStyle(
            $wgScriptPath . '/extensions/UniversalLanguageSelector/lib/jquery.uls/css/jquery.uls.mobile.css'
        );

        /*
         * Si pas chargé, DiscussionTools le charge en AJAX
         * et il écrase notre CSS.
         */
        $out->addModuleStyles('mediawiki.skinning.interface');
    }

    /**
     * @param $out OutputPage
     */
    function setupSkinUserCss(OutputPage $out)
    {
        parent::setupSkinUserCss($out);
    }
}
