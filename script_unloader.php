<?php
//Wordpress plugin informatie, dit word op de plugin pagina getoond.

/*

Plugin Name: Script unloader

Plugin URI:

Description: Een super geweldige plugin, die koffie zet, brood voor je klaar maakt & boven alles zorgt voor een super mega awesome website.

Version: 1.3

Author: Jazzly Geyteman

Author URI: https://burobrein.nl/

Text Domain: Amazing Unloader

*/

//Opties pagina aanmaken binnen het wordpress menu.
function extra_post_info_menu()
{
    //Juiste properties mee geven, zodat het op de juiste plek met de juiste waardes word toegevoegd.
    add_options_page(
        $page_title = 'Een geweldige script unloader',
        $menu_title = 'Script unloader',
        $capability = 'manage_options',
        $menu_slug = 'script-unloader',
        $function = 'Jazz_unloader',
        $icon_url = 'dashicons-media-code'
    );
}

//Functie aanroepen binnen wordpress
add_action('admin_menu', 'extra_post_info_menu');

//Css, javascript inladen.
function Jazz_unloader()
{
    wp_enqueue_style('style', plugins_url('/addons/main.css', __FILE__));
    wp_enqueue_script('script', plugins_url('/addons/script.js', __FILE__));

    ?>
    <!--Form inladen met php functies er in -->
    <form action="" method="POST">
        <h1>Enable or Disable Avada Scripts</h1>
        <div class="wrapper">
            <div class="container">
                <?php
                //Op de manier van wordpress een select statement aanroepen.
                global $wpdb;
                //Wordpress voegt altijd een prefix toe aan alle tabellen.
                $table_name = $wpdb->prefix . 'unload_script';

                $results = $wpdb->get_results(

                    "SELECT * FROM $table_name"

                );
                //Foreach aanroepen zodat als de optie actief is met de juiste classes en style word ingeladen
                foreach ($results as $row) {
                    //kijken of het form gepost is of niet en vanuit daar de active value checken.
                    if ($_POST[$row->active] == 'on' || $row->active == 1) {
                        echo "<div class='input-wrapper add'>";
                    } else {
                        echo "<div class='input-wrapper'>";
                    }

                    if ($_POST[$row->active] == 'on' || $row->active == 1) {
                        //String replacement toepassen zodat het er net wat netter uit ziet in het overzicht.
                        echo "<input type='checkbox' name='$row->id' checked class='active'><label>" . str_replace('-', ' ', $row->scriptName) . "</label></div>";
                    } else {
                        echo "<input type='checkbox' name='$row->id'><label>" . str_replace('-', ' ', $row->scriptName) . "</label></div>";
                    }
                }
                //Kijken of het form gepost word, zodat het geupdate kan worden naar de juiste waardes.
                if ($_SERVER['REQUEST_METHOD'] = $_POST) {
                    foreach ($results as $row) {
                        //Check of de checkbox aanstaat en die linken aan het id, zodat het form weet welke row geupdate moet worden.
                        $checked = $row->id;
                        //Als de checkbox gechecked is, verander de waarde naar 1.
                        if (isset($_POST[$checked])) {
                            $wpdb->update($table_name, array(
                                'active' => 1
                            ),
                                array('id' => $row->id)
                            );
                            //Als de checkbox niet gechecked is, of geunchecked word. Verander de waarde naar 0.
                        } else {
                            $wpdb->update($table_name, array(
                                'active' => 0
                            ),
                                array('id' => $row->id)
                            );
                        }
                    }
                }

                ?>
                <div class="btns">
                    <button class="button-style" onclick="" name='submit' type="submit">Save settings</button>
                    <button class="checkbox-toggle button-style">Check all</button>
                </div>
            </div>
        </div>
    </form>
    <?php
}

function UnloadScripting()
{
    global $wpdb;
    //Op de wordpress manier weer een select aanroepen, maar deze keer word er gekeken naar welke actief in de database staat.
    $table_name = $wpdb->prefix . 'unload_script';
    $results = $wpdb->get_results(

        "SELECT * FROM $table_name WHERE active = 1"
    );

    foreach ($results as $row) {
        $name = $row->scriptName;
        //Door elke actieve optie heen loopen, zodat het juiste script ontkoppeld word.
        switch ($name) {
            case 'avada-comments':
                //De switch zelf spreekt voorzich, alleen daarbinnen in word een functie aangemaakt,
                //zodat deze op een nette manier worden uitgeladen.
                function unloadAvadaComments()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-comments');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaComments');
                break;
            case 'avada-general-footer':
                function unloadAvadaFooter()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-general-footer');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaFooter');
                break;
            case 'avada-mobile-image-hover':
                function unloadAvadaImageHover()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-mobile-image-hover');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaImageHover');
                break;
            case 'avada-quantity':
                function unloadAvadaQuantity()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-quantity');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaQuantity');
                break;
            case 'avada-scrollspy':
                function unloadAvadaScrollSpy()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-scrollspy');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaScrollSpy');
                break;
            case 'avada-select':
                function unloadAvadaSelect()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-select');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaSelect');
                break;
            case 'avada-sidebars':
                function unloadAvadaSideBars()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-sidebars');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaSideBars');
                break;
            case 'avada-tabs-widget':
                function unloadAvadaTabsWidget()
                {
                    Fusion_Dynamic_JS::deregister_script('avada-tabs-widget');
                }

                add_action('wp_enqueue_scripts', 'unloadAvadaTabsWidget');
                break;
            case 'bootstrap-collapse':
                function unloadBootstrapCollapse()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-collapse');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapCollapse');
                break;
            case 'bootstrap-modal':
                function unloadBootstrapModal()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-modal');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapModal');
                break;
            case 'bootstrap-popover':
                function unloadBootstrapPopover()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-popover');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapPopover');
                break;
            case 'bootstrap-scrollspy':
                function unloadBootstrapScrollSpy()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-scrollspy');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapScrollSpy');
                break;
            case 'bootstrap-tab':
                function unloadBootstrapTab()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-tab');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapTab');
                break;
            case 'bootstrap-tooltip':
                function unloadBootstrapTooltip()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-tooltip');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapTooltip');
                break;
            case 'bootstrap-transition':
                function unloadBootstrapTransition()
                {
                    Fusion_Dynamic_JS::deregister_script('bootstrap-transition');
                }

                add_action('wp_enqueue_scripts', 'unloadBootstrapTransition');
                break;
            case 'cssua':
                function unloadCssua()
                {
                    Fusion_Dynamic_JS::deregister_script('cssua');
                }

                add_action('wp_enqueue_scripts', 'unloadCssua');
                break;
            case 'fusion-alert':
                function unloadFusionAlert()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-alert');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionAlert');
                break;
            case 'fusion-blog':
                function unloadFusionBlog()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-blog');

                }

                add_action('wp_enqueue_scripts', 'unloadFusionBlog');
                break;
            case 'fusion-button':
                function unloadFusionButton()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-button');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionButton');
                break;
            case 'fusion-carousel':
                function unloadFusionCarousel()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-carousel');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionCarousel');
                break;
            case 'fusion-chartjs':
                function unloadFusionChart()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-chartjs');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionChart');
                break;
            case 'fusion-column-bg-image':
                function unloadFusionColumn()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-column-bg-image');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionColumn');
                break;
            case 'fusion-count-down':
                function unloadFusionCount()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-count-down');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionCount');
                break;
            case 'fusion-equal-heights':
                function unloadFusionEqual()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-equal-heights');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionEqual');
                break;
            case 'fusion-image-before-after':
                function unloadFusionImageBefore()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-image-before-after');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionImageBefore');
                break;
            case 'fusion-lightbox':
                function unloadFusionLightBox()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-lightbox');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionLightBox');
                break;
            case 'fusion-parallax':
                function unloadFusionParallax()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-parallax');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionParallax');
                break;
            case 'fusion-popover':
                function unloadFusionPopover()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-popover');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionPopover');
                break;
            case 'fusion-recent-posts':
                function unloadFusionRecentPosts()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-recent-posts');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionRecentPosts');
                break;
            case 'fusion-sharing-box':
                function unloadFusionSharingBox()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-sharing-box');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionSharingBox');
                break;
            case 'fusion-syntax-highlighter':
                function unloadFusionSyntaxHighlighter()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-syntax-highlighter');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionSyntaxHighlighter');
                break;
            case 'fusion-title':
                function unloadFusionTitle()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-title');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionTitle');
                break;
            case 'fusion-tooltip':
                function unloadFusionToolTip()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-tooltip');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionToolTip');
                break;
            case 'fusion-video-bg':
                function unloadFusionVideoBg()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-video-bg');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionVideoBg');
                break;
            case 'fusion-video-general':
                function unloadFusionVideoGeneral()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-video-general');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionVideoGeneral');
                break;
            case 'fusion-waypoints':
                function unloadFusionWaypoints()
                {
                    Fusion_Dynamic_JS::deregister_script('fusion-waypoints');
                }

                add_action('wp_enqueue_scripts', 'unloadFusionWaypoints');
                break;
            case 'images-loaded':
                function unloadImagesLoaded()
                {
                    Fusion_Dynamic_JS::deregister_script('images-loaded');
                }

                add_action('wp_enqueue_scripts', 'unloadImagesLoaded');
                break;
            case 'isotope':
                function unloadIsotope()
                {
                    Fusion_Dynamic_JS::deregister_script('isotope');
                }

                add_action('wp_enqueue_scripts', 'unloadIsotope');
                break;
            case 'jquery-appear':
                function unloadJqueryAppear()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-appear');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryAppear');
                break;
            case 'jquery-caroufredsel':
                function unloadJqueryCarou()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-caroufredsel');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryCarou');
                break;
            case 'jquery-count-down':
                function unloadJqueryCountDown()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-count-down');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryCountDown');
                break;
            case 'jquery-count-to':
                function unloadJqueryCountTo()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-count-to');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryCountTo');
                break;
            case 'jquery-easy-pie-chart':
                function unloadJqueryPieChart()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-easy-pie-chart');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryPieChart');
                break;
            case 'jquery-event-move':
                function unloadJqueryEventMove()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-event-move');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryEventMove');
                break;
            case 'jquery-fade':
                function unloadJqueryFade()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-fade');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryFade');
                break;
            case 'jquery-fitvids':
                function unloadJqueryFitVids()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-fitvids');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryFitVids');
                break;
            case 'jquery-fusion-maps':
                function unloadJqueryFusionMaps()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-fusion-maps');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryFusionMaps');
                break;
            case 'jquery-hover-flow':
                function unloadJqueryHoverFlow()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-hover-flow');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryHoverFlow');
                break;
            case 'jquery-hover-intent':
                function unloadJqueryHoverIntent()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-hover-intent');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryHoverIntent');
                break;
            case 'jquery-infinite-scroll':
                function unloadJqueryInfinity()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-infinite-scroll');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryInfinity');
                break;
            case 'jquery-lightbox':
                function unloadJqueryLightBox()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-lightbox');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryLightBox');
                break;
            case 'jquery-mousewheel':
                function unloadJqueryMouseWheel()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-mousewheel');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryMouseWheel');
                break;
            case 'jquery-placeholder':
                function unloadJqueryPlaceHolder()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-placeholder');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryPlaceHolder');
                break;
            case 'jquery-request-animation-frame':
                function unloadJqueryRequestFrame()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-request-animation-frame');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryRequestFrame');
                break;
            case 'jquery-to-top':
                function unloadJqueryToTop()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-to-top');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryToTop');
                break;
            case 'jquery-sticky-kit':
                function unloadJqueryStickyKit()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-sticky-kit');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryStickyKit');
                break;
            case 'jquery-touch-swipe':
                function unloadJqueryTouchSwipe()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-touch-swipe');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryTouchSwipe');
                break;
            case 'jquery-waypoints':
                function unloadJqueryWaypoints()
                {
                    Fusion_Dynamic_JS::deregister_script('jquery-waypoints');
                }

                add_action('wp_enqueue_scripts', 'unloadJqueryWaypoints');
                break;
            case 'lazysizes':
                function unloadLazy()
                {
                    Fusion_Dynamic_JS::deregister_script('lazysizes');
                }

                add_action('wp_enqueue_scripts', 'unloadLazy');
                break;
            case 'packery':
                function unloadPackery()
                {
                    Fusion_Dynamic_JS::deregister_script('packery');
                }

                add_action('wp_enqueue_scripts', 'unloadPackery');
                break;
            case 'vimeo-player':
                function unloadVimeo()
                {
                    Fusion_Dynamic_JS::deregister_script('vimeo-player');
                }

                add_action('wp_enqueue_scripts', 'unloadVimeo');
                break;
        }
    }
}

//De functie uitvoeren binnen de hele website. Hiermee worden alle scripts daarwerkelijk geunload.
UnloadScripting();

//De activation hook van wordpress gebruiken om een functie aan te roepen die de database tabel maakt.
register_activation_hook(__FILE__, 'my_plugin_create_db');

//De activation hook van wordpress gebruiken, die zorgt dat als de plugin gedisabled word, de database tabel weer verwijdert word.
register_deactivation_hook(__FILE__, 'delete_Deactivation');

//Functie die database aanmaakt
function my_plugin_create_db()
{
    global $wpdb;
    //Op de wordpress manier een database tabel aanmaken.
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'unload_script';

    $sql = "CREATE TABLE $table_name (
		id int(255) AUTO_INCREMENT,
		scriptName VARCHAR(455) NOT NULL,
		script VARCHAR(455) NOT NULL,
		active int(255) NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    //De daadwerkelijke query aanroepen en uitvoeren.
    dbDelta($sql);

    //Op moment van activatie word deze functie aangeroepen, om de database ook daadwerkelijk te vullen.
    insert_onload();

}

function insert_onload()
{
    //Op de manier van wordpress een row vullen.
    global $wpdb;
    $table_name = $wpdb->prefix . 'unload_script';
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-comments',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-comments)',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-general-footer',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-general-footer);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-mobile-image-hover',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-mobile-image-hover);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-quantity',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-quantity);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-scrollspy',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-scrollspy);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-select',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-select);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-sidebars',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-sidebars);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'avada-tabs-widget',
        'script' => 'Fusion_Dynamic_JS::deregister_script(avada-tabs-widget);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-collapse',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-collapse);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-modal',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-modal);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-popover',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-popover);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-scrollspy',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-scrollspy);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-tab',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-tab);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-tooltip',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-tooltip);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'bootstrap-transition',
        'script' => 'Fusion_Dynamic_JS::deregister_script(bootstrap-transition);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'cssua',
        'script' => 'Fusion_Dynamic_JS::deregister_script(cssua);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-alert',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-alert);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-blog',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-blog);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-button',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-button);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-carousel',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-carousel);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-chartjs',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-chartjs);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-column-bg-image',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-column-bg-image);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-count-down',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-count-down);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-equal-heights',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-equal-heights);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-image-before-after',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-image-before-after);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-lightbox',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-lightbox);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-parallax',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-parallax);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-popover',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-popover);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-recent-posts',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-recent-posts);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-sharing-box',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-sharing-box);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-syntax-highlighter',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-syntax-highlighter);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-title',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-title);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-tooltip',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-tooltip);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-video-bg',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-video-bg);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-video-general',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-video-general);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'fusion-waypoints',
        'script' => 'Fusion_Dynamic_JS::deregister_script(fusion-waypoints);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'images-loaded',
        'script' => 'Fusion_Dynamic_JS::deregister_script(images-loaded);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'isotope',
        'script' => 'Fusion_Dynamic_JS::deregister_script(isotope);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-appear',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-appear);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-caroufredsel',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-caroufredsel);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-count-down',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-count-down);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-sticky-kit',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-sticky-kit);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-count-to',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-count-to);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-easy-pie-chart',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-easy-pie-chart);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-event-move',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-event-move);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-fade',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-fade);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-fitvids',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-fitvids);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-fusion-maps',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-fusion-maps);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-hover-flow',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-hover-flow);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-hover-intent',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-hover-intent);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-infinite-scroll',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-infinite-scroll);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-lightbox',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-lightbox);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-mousewheel',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-mousewheel);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-placeholder',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-placeholder);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-request-animation-frame',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-request-animation-frame);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-to-top',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-to-top);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-touch-swipe',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-touch-swipe);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'jquery-waypoints',
        'script' => 'Fusion_Dynamic_JS::deregister_script(jquery-waypoints);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'lazysizes',
        'script' => 'Fusion_Dynamic_JS::deregister_script(lazysizes);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'packery',
        'script' => 'Fusion_Dynamic_JS::deregister_script(packery);',
        'active' => 0,
    ));
    $wpdb->insert($table_name, array(
        'id' => NULL,
        'scriptName' => 'vimeo-player',
        'script' => 'Fusion_Dynamic_JS::deregister_script(vimeo-player);',
        'active' => 0,
    ));


}

//De deactivatie plugin. Op het moment dat de plugin gedeactivate word, laat deze geen data en andere rotzooi achter.
function delete_Deactivation()
{
    //Op de manier van wordpress een droptable statement aanroepen.
    global $wpdb;

    $table_name = $wpdb->prefix . 'unload_script';

    $sql = "DROP TABLE " . $table_name;

    $wpdb->query($sql);
}

