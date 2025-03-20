<?php

use CategoryBreadcrumb\CategoryBreadcrumb;
use MediaWiki\MediaWikiServices;

/**
 * BaseTemplate class for the Example skin
 *
 * @ingroup Skins
 */
class ArchiWikiTemplate extends BaseTemplate
{

    /**
     * Available langauges to display on website
     * @var array
     */
    private $availableLanguages = array(
        "Français" => "fr",
        "English" => "en",
        "Deutsch" => "de"
    );

    private $translatableNamespaces = array(
        NS_ADDRESS,
        NS_ADDRESS_NEWS,
        NS_SOURCE,
        NS_SOURCE,
        NS_PERSON
    );
    private $columnLayoutNamespaces = array(
        NS_ADDRESS,
        NS_ADDRESS_NEWS,
        NS_SOURCE,
        NS_SOURCE,
        NS_PERSON
    );
    private $untranslatableActions = array(
        'edit',
        'vedit',
        'history'
    );

    /**
     * Social networks
     */
    private $socialNetworks = array(
        "twitter" => array(
            'handle' => '@ArchiStrasbourg',
            'url' => 'https://twitter.com/ArchiStrasbourg',
            'icon' => '<i class="icon ion-social-twitter"></i>',
        ),
        "facebook" => array(
            'handle' => 'Archi.Strasbourg',
            'url' => 'https://fr-fr.facebook.com/Archi.Strasbourg/',
            'icon' => '<i class="icon ion-social-facebook"></i>',
        ),
        "instagram" => array(
            'handle' => 'archistrasbourg',
            'url' => 'https://www.instagram.com/archistrasbourg/',
            'icon' => '<i class="icon ion-social-instagram"></i>',
        )
    );

    /**
     * Outputs the entire contents of the page
     * @throws MWException
     */
    public function execute()
    {

        global $wgOut, $wgRequest;
        ?>
        <div class="wrapper column">

            <!-- Menu -->

            <?php $this->getHeaderBar(); ?>

            <!-- Header image is inserted with Javascript -->
            <?php if ($this->getThisTitle()->getNamespace() !== -1) : ?>
                <?php
                $pageTitle = $this->getThisTitle()->getFullText();
                $api = new ApiMain(
                    new DerivativeRequest(
                        $wgRequest,
                        [
                            'action' => 'ask',
                            'query' => '[[' . $pageTitle . ']]|?Image principale centrée',
                        ]
                    )
                );
                $api->execute();
                $results = $api->getResult()->getResultData();

                $classes = '';
                if (isset($results['query']['results'][$pageTitle]['printouts']['Image principale centrée'][0])
                    && $results['query']['results'][$pageTitle]['printouts']['Image principale centrée'][0] == 't'
                ) {
                    $classes = 'header-image-center';
                }
                ?>
                <div class="header-image-continer hide " id="header-image" style="">
                    <?php if ($this->isColumnLayout()) echo $this->getTabs(); ?>
                    <div id="header-image2" class="<?php echo $classes; ?>"></div>
                </div>
                <?php if (!$this->isColumnLayout()) : ?>
                    <div class="row column prelative">
                        <?php echo $this->getTabs(); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php
                $query = $wgRequest->getQueryValuesOnly();
                $bodyclasses = ['mw-body'];
                if (isset($query['Recherche_avancée']['carte']['value'])) 
                {
                    $bodyclasses[] = 'map-layout';
                }
            ?>
            <div class="<?php echo implode(' ', $bodyclasses); ?>" role="main" id="content">

                <div id="loading">
                    <img id="loading-image"
                         src="/skins/archi-wiki/resources/img/ajax-loader.gif"
                         alt="Loading..."/>
                </div>


                <?php
                if ($this->data['sitenotice']) {
                    echo Html::rawElement(
                        'div',
                        array('id' => 'siteNotice'),
                        $this->get('sitenotice')
                    );
                }
                if ($this->data['newtalk']) {
                    echo Html::rawElement(
                        'div',
                        array('class' => 'usermessage'),
                        $this->get('newtalk')
                    );
                }
                echo $this->getIndicators();
                echo Html::rawElement(
                    'h1',
                    array(
                        'class' => 'firstHeading',
                        'id' => 'firstHeading',
                        'lang' => $this->get('pageLanguage')
                    ),
                    $this->get('title')
                );

                echo Html::rawElement(
                    'div',
                    array('id' => 'siteSub'),
                    $this->getMsg('tagline')->parse()
                );

                $bodyclasses = ['mw-body-content'];

                $query = $wgRequest->getQueryValuesOnly();
                if (isset($query['Recherche_avancée']['carte']['value'])) 
                {
                    $bodyclasses[] = 'map-layout';
                }
                ?>

                <div class="<?php echo implode(' ', $bodyclasses); ?>" id="bodyContent">

                    <?php echo $this->getArchiWikiToolbox(); ?>

                    <?php
                    echo Html::openElement(
                        'div',
                        array('id' => 'contentSub')
                    );

                    /*
                     * Le hook SkinTemplateOutputPageBeforeExec n'existe plus
                     * donc on doit appeler cette méthode manuellement.
                     */
                    CategoryBreadcrumb::main($this->getSkin(), $this);
                    if ($this->data['subtitle']) {
                        echo Html::rawelement(
                            'p',
                            [],
                            $this->get('subtitle')
                        );
                    }
                    echo Html::rawelement(
                        'p',
                        [],
                        $this->get('undelete')
                    );
                    echo Html::closeElement('div');

                    

                    $this->html('bodycontent');
                    $this->clear();
                    echo Html::rawElement(
                        'div',
                        array('class' => 'printfooter'),
                        $this->get('printfooter')
                    );
                    $this->html('catlinks');
                    $this->html('dataAfterContent');
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
                // echo Html::rawElement(
                // 	'div',
                // 	array( 'id' => 'page-tools' ),
                // 	$this->getPageLinks()
                // );

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
                    <a href="<?php echo $this->getSiteUrl(); ?>"><img
                                src="/skins/archi-wiki/resources/img/logo_archi_wiki-white.png"/></a>
                    <?php foreach ($this->getFooterLinks() as $category => $links) {
                        echo Html::openElement(
                            'ul',
                            array(
                                'id' => 'footer-' . Sanitizer::escapeIdForAttribute($category),
                                'role' => 'contentinfo'
                            )
                        );
                        foreach ($links as $key) {
                            echo Html::rawElement(
                                'li',
                                array(
                                    'id' => 'footer-' . Sanitizer::escapeIdForAttribute($category . '-' . $key)
                                ),
                                $this->get($key)
                            );
                        }
                        echo Html::closeElement('ul');
                    } ?>
                </div>
                <div class="small-12 medium-12 large-6 text-center columns">
                    <?php echo $wgOut->parseAsInterface('{{Partenaire_logos}}'); ?>

                </div>
                <div class="small-12 medium-12 large-3 columns">
                    <?php foreach ($this->socialNetworks as $network) : ?>
                        <a href="<?php echo $network['url']; ?>" target="_blank"
                           class="button expanded large hollow"><?php echo $network['icon']; ?><?php echo $network['handle']; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php
    }


    /**
     * Generates the side floating toolbox of buttons for changing the article
     * @return string html
     */
    private function getArchiWikiToolbox()
    {

        $showOnNamespaces = array(
            NS_ADDRESS,
            NS_TEMPLATE,
            NS_ADDRESS_NEWS,
            NS_SOURCE,
            NS_PERSON,
            NS_FILE,
            NS_MEDIAWIKI,
            NS_CATEGORY
        );

        if (!in_array($this->getThisTitle()->getNamespace(), $showOnNamespaces)) {
            return '';
        }

        $toolBoxItems = array(
            've-edit' => array(
                'icon' => '<i class="material-icons">mode_edit</i>'
            ),
            'watch' => array(
                'icon' => '<i class="material-icons">star_border</i>'
            ),
            'more' => array(
                'history' => array(
                    'icon' => '<i class="material-icons">timer</i>'
                ),
                'edit' => array(
                    'icon' => '<i class="material-icons">code</i>'
                ),
                'print' => array(
                    'icon' => '<i class="material-icons">print</i>'
                ),
                'delete' => array(
                    'icon' => '<i class="material-icons">delete</i>'
                )
            )
        );

        $moreToolBoxData = array(
            'more' => array(
                'href' => '#',
                'class' => 'archiwiki-toolbox-more',
                'id' => 'archiwiki-toolbox-more',
                'text' => $this->getMsg('more-message')->text()
            )
        );
        $printToolBoxData = array(
            'print' => array(
                'href' => '#',
                'class' => 'archiwiki-toolbox-print',
                'id' => 'archiwiki-toolbox-print',
                'text' => $this->getMsg('Print')->text()
            )
        );
        $toolboxData = array_merge($this->data['content_navigation']['views'], $this->data['content_navigation']['actions'], $printToolBoxData);
        $innerHTML = '';
        foreach ($toolBoxItems as $key => $item) {
            if ($key === 'more') {
                $moreInfo = array(
                    'icon' => '<i class="material-icons">settings</i>'
                );
                $innerHTML .= $this->buildToolBoxItem('more', $moreInfo, $moreToolBoxData);
                $moreHTML = '';
                foreach ($item as $moreItemKey => $moreItem) {
                    $moreHTML .= $this->buildToolBoxItem($moreItemKey, $moreItem, $toolboxData);
                }
                $innerHTML .= Html::rawElement('ul', array('class' => 'archiwiki-toolbox-submenu'), $moreHTML);
            } else {
                $innerHTML .= $this->buildToolBoxItem($key, $item, $toolboxData);
            }

        }
        if (!empty($innerHTML)) {
            $html = Html::rawElement(
                'ul',
                array(
                    'class' => 'archiwiki-toolbox'
                ),
                $innerHTML);
        }

        return $html;
    }

    private function isColumnLayout()
    {
        return in_array($this->getThisTitle()->getNamespace(), $this->columnLayoutNamespaces);
    }

    private function buildToolBoxItem($key, $item, $toolboxData)
    {
        if (isset($toolboxData[$key]) && !empty($toolboxData[$key])) {
            $itemData = $toolboxData[$key];
            // Force disable instant switching to visual editor
            if ($itemData['id'] == 'ca-ve-edit') {
                $itemData['id'] = 'ca-ve-edit-noswitch';
            }
            return Html::rawElement(
                'li',
                array(
                    'class' => 'archiwiki-toolbox-item'
                ),
                Html::rawElement(
                    'a',
                    array(
                        'href' => $itemData['href'],
                        'class' => $itemData['class'],
                        'id' => $itemData['id']
                    ),
                    Html::rawElement('span', [], $itemData['text']) . ' ' . $item['icon']
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
    private function getPortlet($box)
    {
        if (!$box['content']) {
            return;
        }

        $html = Html::openElement(
            'div',
            array(
                'role' => 'navigation',
                'class' => 'mw-portlet',
                'id' => Sanitizer::escapeIdForAttribute($box['id'])
            ) + Linker::tooltipAndAccesskeyAttribs($box['id'])
        );
        $html .= Html::element(
            'h3',
            [],
            isset($box['headerMessage']) ? $this->getMsg($box['headerMessage'])->text() : $box['header']);
        if (is_array($box['content'])) {
            $html .= Html::openElement('ul');
            foreach ($box['content'] as $key => $item) {
                $html .= $this->makeListItem($key, $item);
            }
            $html .= Html::closeElement('ul');
        } else {
            $html .= $box['content'];
        }
        $html .= Html::closeElement('div');

        return $html;
    }

    /**
     * Generates the logo and (optionally) site title
     * @return string html
     */
    private function getLogo($id = 'p-logo', $imageOnly = false)
    {
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
            ) + Linker::tooltipAndAccesskeyAttribs('p-logo')
        );
        if (!$imageOnly) {
            $html .= Html::element(
                'a',
                array(
                    'id' => 'p-banner',
                    'class' => 'mw-wiki-title',
                    'href' => $this->data['nav_urls']['mainpage']['href']
                ) + Linker::tooltipAndAccesskeyAttribs('p-logo'),
                $this->getMsg('sitetitle')->escaped()
            );
        }
        $html .= Html::closeElement('div');

        return $html;
    }

    private function getThisTitle()
    {
        return Title::newFromText($this->data['thispage']);
    }

    private function getThisPageUrl()
    {
        $title = $this->getThisTitle();
        return $title->getFullURL();
    }

    /**
     * Generates the search form
     * @return string html
     */
    private function getSearch()
    {
        $html = Html::openElement(
            'form',
            array(
                'action' => htmlspecialchars($this->get('wgScript')),
                'role' => 'search',
                'class' => 'mw-portlet',
                'id' => 'p-search'
            )
        );
        $html .= Html::hidden('title', htmlspecialchars($this->get('searchtitle')));
        $html .= Html::rawelement(
            'h3',
            [],
            Html::label($this->getMsg('search')->escaped(), 'searchInput')
        );
        $html .= $this->makeSearchInput(array('id' => 'searchInput'));
        $html .= $this->makeSearchButton('go', array('id' => 'searchGoButton', 'class' => 'searchButton'));
        $html .= Html::closeElement('form');

        return $html;
    }

    /**
     * Generates the sidebar
     * Set the elements to true to allow them to be part of the sidebar
     * @return string html
     */
    private function getSiteNavigation()
    {
        $html = '';

        $sidebar = $this->getSidebar();

        $sidebar['SEARCH'] = false;
        $sidebar['TOOLBOX'] = true;
        $sidebar['LANGUAGES'] = true;

        foreach ($sidebar as $boxName => $box) {
            if ($boxName === false) {
                continue;
            }
            $html .= $this->getPortlet($box, true);
        }

        return $html;
    }

    /**
     * Returns an array of active languages except the current language
     * @return array other languages
     */
    private function getOtherLanguages(): array
    {
        return array_filter($this->availableLanguages, function ($value) {
            return $value !== $this->data['lang'];
        });
    }

    /**
     * @return string
     */
    private function getLanguageName(): string
    {
        $language = array_filter($this->availableLanguages, function ($value) {
            return $value == $this->data['lang'];
        });

        return current(array_keys($language));
    }

    /**
     * Displays the language menu chooser <li> items
     * @return string html
     */
    private function getLanguageMenuItems(): string
    {
        $html = '';
        $currentLangName = $this->getLanguageName();
        $html .= '<li class="menu-text current-language">' . $currentLangName . '</li>';
        foreach ($this->getOtherLanguages() as $language_name => $language_code) {
            $url = wfAppendQuery($this->getThisPageUrl(), array('uselang' => $language_code));
            $html .= "<li><a href='$url' data-set-language='$language_code'>$language_name</a></li>";
        }

        return sprintf('<li><a href="#">%s</a><ul class="menu">%s</ul></li>', $this->getMsg('languages'), $html);
    }


    /**
     * Generates the top header bar
     * @return string html
     * @throws MWException
     */
    private function getHeaderBar()
    {
        global $wgOut;
        ?>
        <header class="site-header" id="site-header" data-openclose-context>
            <!-- Title Bar -->
            <div class="title-bar" data-responsive-toggle="main-navigation-mobile" data-hide-for="large">
                <div class="title-bar-title title-bar-logo"><a href="<?php echo $this->getSiteUrl(); ?>"><img
                                src="/skins/archi-wiki/resources/img/logo_archi_wiki-white.png"/></a>
                </div>
                <button class="" type="button" data-toggle><i
                            class="material-icons">menu</i><?php echo $this->getMsg('menu'); ?></button>
            </div>

            <div class="hide-for-large">
                <?php $this->getNavigation(true); ?>
            </div>

            <!-- Desktop nav -->
            <div class="show-for-large">
                <div class="top-bar top-bar-inverted">
                    <div class="row column">

                        <div class="top-bar-left">
                            <div class="site-logo">
                                <a href="<?php echo $this->getSiteUrl(); ?>"><img
                                            src="/skins/archi-wiki/resources/img/logo_archi_wiki.png"/></a>
                                <p class="site-slogan"><?php echo $this->getMsg('site-slogan'); ?></p>
                            </div>
                        </div>
                        <div class="top-bar-left">
                            <ul class="menu dropdown" data-dropdown-menu>
                                <?php echo $wgOut->parseAsInterface('{{MenuQuickLinks}}'); ?>
                                <?php echo $this->getLanguageMenuItems(); ?>
                            </ul>
                        </div>
                        <div class="top-bar-right">

                            <ul class="menu">
                                <li><a class="search-button" data-openclose data-target="#search-modal"
                                       data-openclose-colorchange="true"><i class="material-icons">search</i></a></li>
                                <?php
                                $tools = $this->getSkin()->getPersonalToolsForMakeListItem($this->get('personal_urls'));
                                if (isset($tools['notifications-alert'])) {
                                    echo $this->getSkin()->makeListItem('notifications-alert', $tools['notifications-alert']);
                                }
                                if (isset($tools['notifications-notice'])) {
                                    echo $this->getSkin()->makeListItem('notifications-notice', $tools['notifications-notice']);
                                }
                                ?>
                                <li><a class="menu-button" data-openclose data-target="#main-navigation"
                                       data-openclose-colorchange="true"><i
                                                class="material-icons">menu</i><?php echo $this->getMsg('menu'); ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php $this->getNavigation(false); ?>

                <?php $this->getSearchModal(); ?>
            </div>
        </header>


        <?php
    }

    /**
     * @throws MWException
     */
    private function getNavigation($mobile = false)
    {

        global $wgOut;
        $user = RequestContext::getMain()->getUser();

        ?>

        <!-- Navigation -->
        <nav class="main-navigation mega-menu <?php echo($mobile ? '' : 'hide'); ?> "
             id="main-navigation<?php echo($mobile ? '-mobile' : ''); ?>">
            <div class="row">
                <div class="column small-12 large-3">
                    <ul class="menu vertical" <?php echo($mobile ? 'data-accordion-menu' : ''); ?> >
                        <li>
                            <a href="#"><?php echo $this->getMsg('association'); ?></a>
                            <?php echo $wgOut->parseAsInterface('{{MenuAssociation}}'); ?>
                        </li>
                    </ul>
                </div>
                <div class="column small-12 large-4">
                    <ul class="menu vertical" <?php echo($mobile ? 'data-accordion-menu' : ''); ?> >
                        <li>
                            <a href="#"><?php echo $this->getMsg('contribuer'); ?></a>
                            <?php $this->getContributionMenu(); ?>
                        </li>
                    </ul>
                </div>
                <div class="column small-12 large-3">
                    <?php if ($user->mId > 0) : ?>
                        <ul class="menu vertical" <?php echo($mobile ? 'data-accordion-menu' : ''); ?> >
                            <li>
                                <a href="#"><?php echo $this->getMsg('profile'); ?></a>
                                <?php $this->getProfileMenu(); ?>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
                <!-- Mobile only items -->
                <div class="columns small-12 hide-for-large">
                    <ul class="menu vertical">
                        <li>
                            <a href="<?php echo Title::newFromText('Open Data')->getFullURL(); ?>"><?php echo $this->getMsg('open-data'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo Title::newFromText('Faire un don')->getFullURL(); ?>"><?php echo $this->getMsg('donate'); ?></a>
                        </li>
                        <li><a href="#" class="uls-trigger"><?php echo $this->getMsg('change-language'); ?></a></li>
                    </ul>
                </div>
                <!-- /Mobile Only Items -->
                <div class="column small-12 large-2">
                    <ul class="menu vertical" <?php echo($mobile ? 'data-accordion-menu' : ''); ?> >
                        <li>
                            <?php $this->getProfile(); ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php
    }

    /**
     * @param string[] $tab
     * @return string
     */
    private function getTabLink(array $tab): string
    {
        return '<li class="' . $tab['class'] . '"><a href="' . $tab['href'] . '">' . $tab['text'] . '</a></li>';
    }

    /**
     * @return string
     */
    private function getTabs(): string
    {
        $html = '';

        foreach ($this->data['content_navigation']['namespaces'] as $tab) {
            $html .= $this->getTabLink($tab);
        }
        if (isset($this->data['content_navigation']['views']['addsection'])) {
            $html .= $this->getTabLink($this->data['content_navigation']['views']['addsection']);
        }

        return sprintf('<div class="article-tabs"><ul class="menu show-for-medium">%s</ul></div>', $html);
    }

    /**
     * @throws MWException
     */
    private function getSearchModal($startHidden = true)
    {

        global $wgOut;

        ?>

        <!-- Navigation -->
        <nav class="search-modal search-box color-panel color-panel-earth <?php echo($startHidden ? 'hide' : ''); ?>"
             id="search-modal">
            <div class="row">
                <div class="column">
                    <h3 class="text-center search-title">
                        <?php printf($this->getMsg('search-title'), strip_tags($wgOut->parseAsInterface('{{PAGESINNAMESPACE:' . NS_ADDRESS . '}}')), strip_tags($wgOut->parseAsInterface('{{PAGESINNAMESPACE:6}}'))); ?>
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="column large-7 large-offset-2">
                    <form action="<?php echo $this->get('wgScript') ?>">
                        <div class="input-group">
                            <input type="search" id="searchInput" class="search-input input-group-field"
                                   placeholder="<?php echo $this->getMsg('search-placeholder'); ?>" name="search">
                            <input type="hidden" name="profile" value="default">
                            <div class="input-group-button">
                                <a class="button form-submit">
                                    <i class="material-icons">search</i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="column large-2 end">
                    <a href="<?php echo Title::newFromText('Spécial:RunQuery/Recherche_avancée')->getFullURL(); ?>"
                       class="advanced-search-link"><?php echo $this->getMsg('advanced-search'); ?></a>
                    <a href="<?php echo "/Spécial:RunQueryCache/Recherche_avancée?pfRunQueryFormName=Recherche avancée&Recherche_avancée[carte][value]=1&Recherche_avancée[carte][is_checkbox]=true"; ?>" 
                       class="advanced-search-link">recherche cartographique</a>
                </div>
            </div>
        </nav>

        <?php
    }


    /**
     * Generates page-related tools/links
     * @return string html
     */
    private function getPageLinks()
    {
        $html = $this->getPortlet(array(
            'id' => 'p-namespaces',
            'headerMessage' => 'namespaces',
            'content' => $this->data['content_navigation']['namespaces'],
        ));
        $html .= $this->getPortlet(array(
            'id' => 'p-variants',
            'headerMessage' => 'variants',
            'content' => $this->data['content_navigation']['variants'],
        ));
        $html .= $this->getPortlet(array(
            'id' => 'p-views',
            'headerMessage' => 'views',
            'content' => $this->data['content_navigation']['views'],
        ));
        $html .= $this->getPortlet(array(
            'id' => 'p-actions',
            'headerMessage' => 'actions',
            'content' => $this->data['content_navigation']['actions'],
        ));

        return $html;
    }

    private function getSiteURL()
    {
        return $this->data['serverurl'] . $this->data['scriptpath'];
    }


    private function getContributionMenu()
    {
        $user = RequestContext::getMain()->getUser();

        ?>
        <ul class="menu vertical">
            <?php $about_title = Title::newFromText("Archi-Wiki, c'est quoi ?"); ?>
            <li><a href="<?php echo $about_title->getFullURL(); ?>"><?php echo $about_title->getText(); ?></a></li>
            <?php if (in_array('createpage', $user->mRights)) : ?>
                <li>
                    <a href="<?php echo Title::newFromText('Nouvelle page')->getFullURL(); ?>"><?php echo $this->getMsg('create-page'); ?></a>
                </li>
            <?php endif; ?>
            <?php if (in_array('edit', $user->mRights) && $this->getThisTitle()->getNamespace() != -1) : ?>
                <li>
                    <a href="<?php echo wfAppendQuery($this->getThisPageUrl(), ['veaction' => 'edit']) ?>"><?php echo $this->getMsg('edit-page'); ?></a>
                </li>
                <li>
                    <a href="<?php echo wfAppendQuery($this->getThisPageUrl(), ['action' => 'edit']) ?>"><?php echo $this->getMsg('edit-page-code'); ?></a>
                </li>
            <?php endif; ?>
            <li>
                <a href="<?php echo wfAppendQuery($this->getThisPageUrl(), ['action' => 'history']) ?>"><?php echo $this->getMsg('get-page-history'); ?></a>
            </li>
            <?php $contribution_title = Title::newFromText('Aide à la contribution'); ?>
            <li>
                <a href="<?php echo $contribution_title->getFullURL(); ?>"><?php echo $contribution_title->getText(); ?></a>
            </li>
            <?php if (in_array('sysop', $user->getGroups())):; ?>
                <li><a href="<?php echo Title::newFromText('Aide:AdminAW')->getFullURL(); ?>">Administration</a></li>
            <?php endif; ?>
        </ul>

        <?php
    }

    private function getProfileMenu()
    {
        $user = RequestContext::getMain()->getUser();

        ?>
        <ul class="menu vertical">
            <li>
                <a href="<?php echo Title::newFromText('Spécial:Liste_de_suivi')->getFullURL(); ?>"><?php echo $this->getMsg('followed-list'); ?></a>
            </li>
            <li>
                <a href="<?php echo $this->getPersonalTools()['mycontris']['links'][0]['href']; ?>"><?php echo $this->getMsg('contribs-list'); ?></a>
            </li>
            <li>
                <a href="<?php echo Title::newFromText('Utilisateur:' . $user->mName . '/Brouillon')->getFullURL(); ?>"><?php echo $this->getMsg('user-sandbox'); ?></a>
            </li>
            <li>
                <a href="<?php echo Title::newFromText('Discussion_utilisateur:' . $user->mName)->getFullURL(); ?>"><?php echo $this->getMsg('user-discussion'); ?></a>
            </li>
            <li>
                <a href="<?php echo $this->getPersonalTools()['preferences']['links'][0]['href']; ?>"><?php echo $this->getMsg('your-prefs'); ?></a>
            </li>
        </ul>

        <?php
    }

    private function getProfile()
    {
        $user = RequestContext::getMain()->getUser();

        ?>

        <div class="profile-box">
            <?php if ($user->mId > 0) : ?>
                <?php $avatar_url = $this->getUserAvatar(115); ?>
                <?php if ($avatar_url) : ?>
                    <img src="<?php echo $avatar_url ?>" alt="Avatar <?php echo $user->mRealName; ?>"/>
                <?php else : ?>
                    <i class="material-icons">account_circle</i>
                <?php endif; ?>
            <?php else : ?>
                <i class="material-icons">account_circle</i>
            <?php endif; ?>
            <ul class="menu vertical">
                <?php if ($user->mId > 0) : ?>
                    <li>
                        <a href="<?php echo Title::newFromText('Utilisateur:' . $user->mName)->getFullURL(); ?>"><b><?php echo $user->getName(); ?></b></a>
                    </li>
                    <li>
                        <a href="<?php echo Title::newFromText('Utilisateur:' . $user->mName)->getFullURL(); ?>"><?php echo $this->getMsg('your-profile'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo $this->getPersonalTools()['logout']['links'][0]['href']; ?>"><?php echo $this->getMsg('log-out'); ?></a>
                    </li>
                <?php else : ?>
                    <li>
                        <a href="<?php echo $this->getPersonalTools()['createaccount']['links'][0]['href']; ?>"><?php echo $this->getMsg('create-account'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo $this->getPersonalTools()['login']['links'][0]['href']; ?>"><?php echo $this->getMsg('log-in'); ?></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php

    }

    /**
     * Generates user tools menu
     * @return string html
     */
    private function getUserLinks()
    {
        return $this->getPortlet(array(
            'id' => 'p-personal',
            'headerMessage' => 'personaltools',
            'content' => $this->getPersonalTools(),
        ));
    }

    /**
     * Outputs a css clear using the core visualClear class
     */
    private function clear()
    {
        echo '<div class="visualClear"></div>';
    }

    /**
     * Get the current user avatar
     * @param int $width Desired width in pixels
     * @return string|null Relative URL
     */
    private function getUserAvatar($width)
    {
        global $wgRequest;
        $user = RequestContext::getMain()->getUser();

        $userPagename = 'Utilisateur:' . $user->getName();
        $userTitle = Title::newFromText($userPagename);
        $api = new \ApiMain(
            new \DerivativeRequest(
                $wgRequest,
                array(
                    'action' => 'ask',
                    'query' => '[[' . $userPagename . ']]|?Avatar'
                )
            )
        );
        $api->execute();
        $results = $api->getResult()->getResultData();
        if (isset($results['query']['results'][$userPagename]['printouts']['Avatar'][0])) {
            $avatar = $results['query']['results'][$userPagename]['printouts']['Avatar'][0];
            $avatarTitle = Title::newFromText($avatar['fulltext']);
            $avatarFile = MediaWikiServices::getInstance()->getRepoGroup()->findFile($avatarTitle->getText());

            return $avatarFile->createThumb($width);
        }
    }

    private function getTranslations()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = '';
        }
        if (in_array($this->getThisTitle()->getNamespace(), $this->translatableNamespaces) && !in_array($action, $this->untranslatableActions)) {
            $translationsHTML = '';
            $curCode = $this->getThisTitle()->getSubpageText();
            if ($curCode == $this->getThisTitle()->getBaseText()) {
                $curCode = 'fr';
            }
            foreach ($this->availableLanguages as $language => $code) {
                if ($code != $curCode) {
                    if ($code == 'fr') {
                        $subpage = '';
                    } else {
                        $subpage = '/' . $code;
                    }
                    $title = Title::newFromText($this->getThisTitle()->getNsText() . ':' . $this->getThisTitle()->getBaseText() . $subpage);
                    $translationsHTML .= Html::rawElement('li', array(),
                        Html::rawElement('a', array(
                            'href' => $title->getFullURL()
                        ),
                            $language)
                    );
                }
            }
            // echo $translationsHTML;
            $listHTML = Html::rawElement(
                'ul',
                array(
                    'class' => 'translations-list'
                ),
                $translationsHTML);
            $html = Html::rawElement(
                'div',
                array('class' => 'translations'),
                Html::rawElement(
                    'h2',
                    array('class' => 'translations-title'),
                    $this->getMsg('translations-title')
                )
                . $listHTML
            );
        } else {
            $html = '';
        }


        return $html;

    }
}
