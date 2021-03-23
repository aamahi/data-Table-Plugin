<?php
/**
 * Plugin Name:       Data Table
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdullah Mahi
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       data-table
 * Domain Path:       /languages
 */
if ( ! defined("ABSPATH")) {
    exit;
}

require_once ('class.persons-table.php');

add_action( 'admin_menu', 'datatable_manupage' );

function datatable_filter_sex($item){

    $sex = $_REQUEST['filter_s'] ?? 'all';

    if( 'all' == $sex ){
        return true;
    } else{
        if( $sex == $item['sex'] ){
            return true;
        }
    }
    return false;
}

function datatable_search_by_name( $item ) {
    $name        = strtolower( $item['name'] );
    $search_name = sanitize_text_field( $_REQUEST['s'] );
    if ( strpos( $name, $search_name ) !== false ) {
        return true;
    }

    return false;
}

function datatable_dispaly_page() {
    require_once ('dataset.php');
    $table = New Persons_Table();

    if ( isset( $_REQUEST['s'] ) && !empty( $_REQUEST['s'] ) ) {
        $data = array_filter( $data, 'datatable_search_by_name' );
    }

    if ( isset( $_REQUEST['filter_s'] ) && !empty($_REQUEST['filter_s']) ) {
        $data = array_filter( $data, 'datatable_filter_sex' );
    }

    $orderBy = $_REQUEST['orderby'] ?? '';
    $order = $_REQUEST['order'] ?? '';

    if ( 'age' == $orderBy ) {
        if ( 'asc'== $order ) {
            usort( $data, function ( $item1, $item2 ){
                return $item2['age'] <=> $item1['age'];
            } );
        }else{
            usort( $data, function ( $item1, $item2 ){
                return $item1['age'] <=> $item2['age'];
            } );
        }
    }

    if ( 'name' == $orderBy ) {
        if ( 'asc'== $order ) {
            usort( $data, function ( $item1, $item2 ){
                return $item2['name'] <=> $item1['name'];
            } );
        }else{
            usort( $data, function ( $item1, $item2 ){
                return $item1['name'] <=> $item2['name'];
            } );
        }
    }

    $table->set_data($data);
    $table->prepare_items();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php _e('Persons', 'datatable' );?></h1>
        <form method="GET">
            <?php
                $table->search_box('search' ,'search_id');
                $table->display();
            ?>
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>">
        </form>
    </div>
    <?php
}
function datatable_manupage() {
    add_menu_page(
        __( "Data Table", 'data-table' ),
        __( "Data Table", 'data-table' ),
        'manage_options',
        'data-table',
        'datatable_dispaly_page',
        'dashicons-media-spreadsheet',
        '25'
    );
}

