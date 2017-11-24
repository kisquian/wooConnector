<?php 
/**
* Plugin Name: WooConnector for Sttila
* Plugin URI: https://sttila.com/
* Author: Cosmic Desarrollo Web
* Description: Plugin que conecta tiendas para el sistema STTILA.
* Version: 2.0
* License: GPLv2
**/

/*
 * add admin page
 */
add_action('admin_menu', 'wooconnector_admin_page');
function wooconnector_admin_page(){
    add_menu_page('WooConnector', 'WooConnector', 'administrator', 'wooconnector-settings', 'wooconnector_admin_page_callback');
    add_submenu_page( 'wooconnector-settings', 'Child brands', 'Child brands', 'administrator', 'wooconnector-settings-brands', 'select_child_brand' );
    add_submenu_page( 'wooconnector-settings', 'SYNC', 'SYNC', 'administrator', 'wooconnector-settings-sync', 'select_sync' );

}

add_action('admin_init', 'wooconnector_register_settings');


/*
 * register the settings
 */

function wooconnector_register_settings(){
    register_setting( 'wooconnector_settings_group', 'wooconnector_url' );
    register_setting( 'wooconnector_settings_group', 'wooconnector_key' );
    register_setting( 'wooconnector_settings_group', 'wooconnector_secret' );
    register_setting( 'wooconnector_settings_group', 'wooconnector_shedule1' );
    register_setting( 'wooconnector_settings_group', 'wooconnector_shedule2' );
    add_settings_section('wooconnector_settings', 'Settings', 'wooconnector_settings_fn', 'wooconnector-settings' );
    add_settings_field( '_url', 'Url', 'wooconnector_settings_url', 'wooconnector-settings', 'wooconnector_settings');   
    add_settings_field( '_key', 'Key', 'wooconnector_settings_key', 'wooconnector-settings', 'wooconnector_settings');
    add_settings_field( '_secret', 'Secret', 'wooconnector_settings_secret', 'wooconnector-settings', 'wooconnector_settings');
    add_settings_field( '_shedule1', 'Shedule 1', 'wooconnector_settings_shedule1', 'wooconnector-settings', 'wooconnector_settings');  
    add_settings_field( '_shedule2', 'Shedule 2', 'wooconnector_settings_shedule2', 'wooconnector-settings', 'wooconnector_settings');  
}


function wooconnector_settings_fn() {
    echo 'Customize your WooConnector';
}
function wooconnector_settings_url() {
    $url = esc_attr(get_option('wooconnector_url'));
    echo '<input type="text" name="wooconnector_url" value="'.$url.'" placeholder="URL">';
}
function wooconnector_settings_key() {
    $key = esc_attr(get_option('wooconnector_key'));
    echo '<input type="text" name="wooconnector_key" value="'.$key.'" placeholder="KEY">';
}
function wooconnector_settings_secret() {
    $secret = esc_attr(get_option('wooconnector_secret'));
    echo '<input type="text" name="wooconnector_secret" value="'.$secret.'" placeholder="SECRET KEY">';
}
function wooconnector_settings_shedule1() {
    $shedule1 = esc_attr(get_option('wooconnector_shedule1'));
    echo '<input type="time" name="wooconnector_shedule1" value="'.$shedule1.'" placeholder="SHEDULE 1">';
}
function wooconnector_settings_shedule2() {
    $shedule2 = esc_attr(get_option('wooconnector_shedule2'));
    echo '<input type="time" name="wooconnector_shedule2" value="'.$shedule2.'" placeholder="SHEDULE 2">';
}

function brand_table_create() {
    $jal_db_version = '1.0';
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'WCONchildbrands';
    $table_name2 = $wpdb->prefix . 'WCONsync';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          url varchar(50),
          name varchar(50),
          keyvalue varchar(50),
          secret varchar(50),
          PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );


    $sql2 = "CREATE TABLE $table_name2 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          dataTime datetime,
          brand varchar(50),
          status bit(0) NOT NULL,
          newproducts varchar(50),
          updated bit(0) NOT NULL,
          deleted bit(0) NOT NULL,
          PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta( $sql2 );

    add_option( 'jal_db_version', $jal_db_version );
}

register_activation_hook( __FILE__, 'brand_table_create' );


// this function generates the shortcode output
function select_child_brand() {
     global $wpdb;

    if ( isset( $_POST["sendbtn"] )) {
    $table = $wpdb->prefix."WCONchildbrands";
    $name = $_POST["namebrand"];
    $key = $_POST["keybrand"];
    $url = $_POST["urlbrand"];
    $secret = $_POST["secretbrand"];

    $wpdb->insert( 
        $table, 
        array( 
            'name' => $name,
            'url' => $url,
            'keyvalue' => $key,
            'secret' => $secret
        )
    );
    }

   
    // Shortcodes RETURN content, so store in a variable to return
    $content = '<form method="POST"><table id="brands" border="1"><h2>Child Brands</h2>';
    $content .= '<tr><th class">&nbsp;</th><th class">name</th><th class">url</th><th class">key</th><th class">secret</th></tr>';
    $results = $wpdb->get_results( 'SELECT * FROM wp_WCONchildbrands' );
    foreach ( $results AS $row ) {
        $content .= '<tr>';
        // Modify these to match the database structure
        //$content .= '<td>' . $row->id . '</td>';
        $content .= '<td><button>X</button></td>';
        $content .= '<td>' . $row->name . '</td>';
        $content .= '<td>' . $row->url . '</td>';
        $content .= '<td>' . $row->keyvalue . '</td>';
        $content .= '<td>' . $row->secret . '</td>';
        $content .= '<td>EDITAR</td>';
        $content .= '</tr>';
    }
    $content .= '<tr><td>&nbsp;</td><td><input name="namebrand" id="namebrand" type="text" placeholder="Name"></td>
    <td><input name="urlbrand" type="text" placeholder="Url"></td>
    <Td><input name="keybrand" type="text" placeholder="Key"></td>
    <td><input name="secretbrand" type="text" placeholder="Secret"></td>
    <td><input type="submit" name="sendbtn" value="enviar"></td></tr>';
    $content .= '</table></form>';

    // return the table
    echo $content;

}

function select_sync() {
     global $wpdb;

    if ( isset( $_POST["sendbtn"] )) {
    $table = $wpdb->prefix."WCONchildbrands";
    $name = $_POST["namebrand"];
    $key = $_POST["keybrand"];
    $url = $_POST["urlbrand"];
    $secret = $_POST["secretbrand"];

    $wpdb->insert( 
        $table, 
        array( 
            'name' => $name,
            'url' => $url,
            'keyvalue' => $key,
            'secret' => $secret
        )
    );
    }

   
    // Shortcodes RETURN content, so store in a variable to return
    $content = '<form method="POST"><table id="brands" border="1"><h2>Child Brands</h2>';
    $content .= '<tr><th class">&nbsp;</th><th class">name</th><th class">url</th><th class">key</th><th class">secret</th></tr>';
    $results = $wpdb->get_results( 'SELECT * FROM wp_WCONchildbrands' );
    foreach ( $results AS $row ) {
        $content .= '<tr>';
        // Modify these to match the database structure
        //$content .= '<td>' . $row->id . '</td>';
        $content .= '<td><button>X</button></td>';
        $content .= '<td>' . $row->name . '</td>';
        $content .= '<td>' . $row->url . '</td>';
        $content .= '<td>' . $row->keyvalue . '</td>';
        $content .= '<td>' . $row->secret . '</td>';
        $content .= '<td>EDITAR</td>';
        $content .= '</tr>';
    }
    $content .= '<tr><td>&nbsp;</td><td><input name="namebrand" id="namebrand" type="text" placeholder="Name"></td>
    <td><input name="urlbrand" type="text" placeholder="Url"></td>
    <Td><input name="keybrand" type="text" placeholder="Key"></td>
    <td><input name="secretbrand" type="text" placeholder="Secret"></td>
    <td><input type="submit" name="sendbtn" value="enviar"></td></tr>';
    $content .= '</table></form>';

    // return the table
    echo $content;

}



// plugin settings page
function wooconnector_admin_page_callback() { ?>

    <div class="wrap">
        
    <h2>WooConnector for Sttila</h2>
    <form action="options.php" method="post"><?php
        settings_fields( 'wooconnector_settings' );
        do_settings_sections( __FILE__ );

        settings_errors();
        settings_fields( 'wooconnector_settings_group' );
        do_settings_sections('wooconnector-settings' );
        submit_button(); ?>
      
    </form>
</div>
<?php }