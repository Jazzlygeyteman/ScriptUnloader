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

    //foreach aanroepen zodat er door de waardes heen geloopt word
    foreach ($results as $row) {

        //variables globaal maken zodat ze in de functie gebruikt kunnen worden
        global $row;
        global $naam;

        //waardes toewijzen aan een variable
        $naam = $row->scriptName;

        //er voor zorgen dat de variable binnen de functie wel geupdate word
        $nieuwenaam = $naam;

        //functie in een variable plaatsen, zodat deze geloopt kan worden zonder errors te geven
        //Daarnaast moet de oude en nieuwe variable mee gegeven worden om geupdate te worden
        $variableFunction = function ($naam) use ($nieuwenaam) {

            //nog 1 keer de globale variable declareren
            global $naam;

            //unload de script doormiddel van een dynamische naam uit de database
            Fusion_Dynamic_JS::deregister_script($naam);

            //update variable met de nieuwe waarde
            $naam = $nieuwenaam;
        };

        //voer het script uit en begin weer bovenaan
        add_action('wp_enqueue_scripts', $variableFunction);
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

