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
    add_menu_page('Wooconnector', 'Wooconnector', 'administrator', 'wooconnector-settings', 'wooconnector_admin_page_callback');
    add_action('admin_init', 'wooconnector_register_settings');
    add_action( 'admin_init', 'save_child_brands');
    wp_enqueue_script('test', plugin_dir_url(__FILE__) . 'js.js', array('jquery'));

}


function brand_table_create() {
    global $table_prefix, $wpdb;

    $tblname = 'WCON_child_brands';
    $wp_track_table = $table_prefix . "$tblname ";

    #Check to see if the table exists already, if not, then create it

    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) {

        $sql = "CREATE TABLE $tblname (
      id tinyint(3) NOT NULL AUTO_INCREMENT,
      name varchar(55) DEFAULT '' NOT NULL,
      url varchar(55) DEFAULT '' NOT NULL,
      key varchar(55) DEFAULT '' NOT NULL,
      secret varchar(55) DEFAULT '' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}

 register_activation_hook( __FILE__, 'brand_table_create' );

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

function save_child_brands() {
    // save new child brand
    add_option( 'brand-title');
}
function delete_child_brand() {
    // delete child brand
}
function edit_child_brand() {
    // update child brand
}
function show_child_brands() {
    // delete child brand
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

        
        <table id="brands">
        <h2 style="display: none">Child Brands</h2>
        <!-- Dynamic Rows, list all array items as an individual row-->
            <tr>
                <td><button>X</button></td>
                <td>Adidas</td>
                <td>addidas.wooo.c</td>
                <td>$%#asd8Fas4Eda4!</td>
                <td>************</td>
                <td><a href="#">edit</a></td>
            </tr>
            
            <tr>
                <td>+</td>
                <td><input type="text" placeholder="Name"></td>
                <td><input type="text" placeholder="URL"></td>
                <td><input type="text" placeholder="Key"></td>
                <td><input type="text" placeholder="Secret"></td>
                <td><button>AGREGAR</button></td>
            </tr>
        </table>
        <hr>
        <table id="sync-status" style="display: none">
        <h2 style="display: none">SYNC Status</h2>
            <tr>
                <th>DATETIME</th>
                <th>BRAND</th>
                <th>STATUS</th>
                <th>NEW PRODUCTS</th>
                <th>UPDATED</th>
                <th>DELETED</th>
            </tr>
            <tr>
                <td>17/10 - 00:00</td>
                <td>Adidas</td>
                <td><input type="checkbox" name="" id=""></td>
                <td>10</td>
                <td>5</td>
                <td>0</td>
            </tr>
            <tr>
                <td>17/10 - 00:00</td>
                <td>Adidas</td>
                <td><input type="checkbox" name="" id=""></td>
                <td>10</td>
                <td>5</td>
                <td>0</td>
            </tr>
            <tr>
                <td>17/10 - 00:00</td>
                <td>Adidas</td>
                <td><input type="checkbox" name="" id=""></td>
                <td colspan="3">API KEY Expired </td>
            </tr>
            <tr>
                <td>17/10 - 00:00</td>
                <td>Adidas</td>
                <td><input type="checkbox" name="" id=""></td>
                <td>10</td>
                <td>5</td>
                <td>0</td>
            </tr>
            <tr>
                <td>17/10 - 00:00</td>
                <td>Adidas</td>
                <td><input type="checkbox" name="" id=""></td>
                <td>10</td>
                <td>5</td>
                <td>0</td>
            </tr>
            <tr>
                <td>17/10 - 00:00</td>
                <td>Adidas</td>
                <td><input type="checkbox" name="" id=""></td>
                <td>10</td>
                <td>5</td>
                <td>0</td>
            </tr>
        </table>
    </form>
</div>
<?php }