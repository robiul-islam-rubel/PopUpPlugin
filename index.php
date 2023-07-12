<?php
/**
*
* @link    https://www.wpxpo.com/
* @since   1.0.0
* @package wpxpo-cta
*
* Plugin Name:       wpxpo-cta
* Plugin URI:
* Description:       The wpxpo-cta plugin is a brand-new, highly-promising WooCommerce B2B solution to set up a conversion-focused B2B store for selling wholesale products. It offers everything required to operate an effective B2B store.
* Version:           1.1.0
* Author:            wpxpo
* Author URI:        https://wpxpo.com/
* License:           GPLv3
* License URI:       http://www.gnu.org/licenses/gpl-3.0.html
* Text Domain:       popup
* Domain Path:       /languages
**/

add_action( 'wp_footer', 'popup' );

function popup()
 {
    $page_id = get_queried_object_id();
    $meta_value = get_option( 'my_global_meta_value' );
    $post_type = get_post_type( $page_id );
    $ids = get_option( 'my_post_ids' );
    $length = count( $ids );
    $boolarray = array_fill( 0, $length, 'false' );
    foreach ( $ids as $id )
 {
        $data = $meta_value[ $id ][ 'pages' ];
        $status = $meta_value[$id]['status'];
        if ( $data === 'blog' || $data === 'allpage' && $status === 'publish' ) {
            echo '
                <div class="mfp-bg">
                    <div id="try-demo" class="white-popup mfp-hide">
                        <form class="wpxpo-pop" method="post" action="#" data-type="postx">
                            <div class="wpxpo-pop-title">Try PostX Backend Demo Now!</div>
                            <button title="Close (Esc)" type="button" class="close-btn">×</button>
                            <div class="wpxpo-pop-description">Please provide your name and email address to get demo access!</div>
                            <input type="text" name="name" class="wpxpo-pop-name" required="required" placeholder="Your Name">
                            <input type="email" name="email" class="wpxpo-pop-email" required="required" placeholder="Enter Email">
                            <button class="xpo-btn xpo-btn-primary wpxpo-btn-access">Explore Demo</button>
                        </form>
                    </div>
                </div>
                ';
        } else {

            $value = $meta_value[ $id ][ 'specificpage' ];
            if ( in_array( $page_id, $value ) && $status ==='publish')
            {
                echo '
                        <div class="mfp-bg">
                            <div id="try-demo" class="white-popup mfp-hide">
                                <form class="wpxpo-pop" method="post" action="#" data-type="postx">
                                    <div class="wpxpo-pop-title">Try PostX Backend Demo Now!</div>
                                    <button title="Close (Esc)" type="button" class="close-btn">×</button>
                                    <div class="wpxpo-pop-description">Please provide your name and email address to get demo access!</div>
                                    <input type="text" name="name" class="wpxpo-pop-name" required="required" placeholder="Your Name">
                                    <input type="email" name="email" class="wpxpo-pop-email" required="required" placeholder="Enter Email">
                                    <button class="xpo-btn xpo-btn-primary wpxpo-btn-access">Explore Demo</button>
                                </form>
                            </div>
                        </div>
                    ';

            }

        }
    }
    if ( $post_type === 'page' )
   {
        $meta = $meta_value;
        // echo '<pre>';
        // print_r( $meta );
        // echo '</pre>';

    }
    //  echo '<pre>';
    //  print_r($meta_value);
    //  echo '</pre>';
}

function enqueue_custom_script() {
    // $meta_value =  get_post_meta( get_the_ID(), 'my_meta_key', true );
    $meta_value = get_option( 'my_global_meta_value' );
    wp_enqueue_script( 'custom_script', plugins_url( 'assets/index.js', __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_localize_script( 'custom_script', 'metavalue', $meta_value );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_script' );
add_action( 'admin_enqueue_scripts', 'enqueue_custom_script' );

function enqueue_custom_style() {
    wp_enqueue_style( 'custom_style', plugins_url( 'assets/style.css', __FILE__ ), array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_style' );
add_action( 'admin_menu', 'menu_cta' );

function menu_cta() {
    add_menu_page(
        'PopUp',
        'CTA',
        'read',
        'cta',
        'my_menu_callback',
        'dashicons-admin-appearance',
    );
}

function my_menu_callback() {
    echo 'Welcome';
}

function my_meta_box_callback( $post ) {
    $pages = get_pages();
    $meta_value = get_option( 'my_global_meta_value' );
    $data = $meta_value[ $post->ID ][ 'pages' ];
    $post_meta = get_post_meta( $post->ID, 'my_meta_key', true );
    $meta = $post_meta[ 'pages' ];
    ?>
       <select name = 'pages' id = 'pages'>
    <?php
        $allpages = array( 'allpage', 'specific', 'exclude', 'blog' );
        $up = strtoupper( $meta );
        echo "<option value = '$up' selected>$up</option>";
        foreach ( $allpages as $page ) {
            $pageup = strtoupper( $page );
            echo "<option value = '$page'>$pageup</option>";
        }
    ?>
    </select>
    <?php
    ?>
        <div id = 'specific-page-dropdown' style = 'display: none;' name = 'specific-page-dropdown'>
            <select name = 'specific-page-dropdown[]' multiple id = 'specific-page-dropdown'>
                <option value = '' selected>Select a page</option>
                <?php
                    foreach ( $pages as $page ) {
                        $val = $page->post_title;
                        echo "<option value='$page->ID' >$val</option>";
                    }
                ?>
            </select>
        </div>
    <?php
}

function save_my_meta_box_data( $post_id ) {
    if ( isset( $_POST[ 'pages' ] )  && isset( $_POST[ 'time' ] ) ) {
        $selected_values = isset( $_POST[ 'specific-page-dropdown' ] ) ? $_POST[ 'specific-page-dropdown' ] : array();
        $id = get_the_ID();
        $post_type = get_post_type( $id );
        if ( $post_type === 'post' )
        {
            $current_status = get_post_status ( $id );
            $post_ids = get_option( 'my_post_ids', array() );
            $post_ids[ $id ] = $id;
            update_option( 'my_post_ids', $post_ids );
        }
        $meta_value = array(
            'pages' => sanitize_text_field( $_POST[ 'pages' ] ),
            'time' => sanitize_text_field( $_POST[ 'time' ] ),
            'specificpage' => $selected_values,
            'status' =>$current_status,
        );
        if ( $post_type === 'post')
        {
            $existing_meta_values = get_option( 'my_global_meta_value', array() );
            $existing_meta_values[ $post_id ] = $meta_value;
            update_option( 'my_global_meta_value', $existing_meta_values );
        }
        update_post_meta( $post_id, 'my_meta_key', $meta_value );
    }
}
add_action( 'save_post', 'save_my_meta_box_data' );
add_action( 'add_meta_boxes', 'my_add_meta_box' );

function my_add_meta_box() {
    $post_types = array( 'post' );
    add_meta_box(
        'my-meta-box',
        'Conditions',
        'my_meta_box_callback',
        'post',
        'side',
        'default'
    );
    add_meta_box(
        'my-meta-box1',
        'All Posts',
        'display_all_posts',
        'post',
        'side',
        'default'
    );
    add_meta_box(
        'my-meta-box2',
        'Time',
        'my_meta_box_callback2',
        'post',
        'side',
        'default'
    );

}
function my_meta_box_callback2( $post ) {
    $post_meta = get_post_meta( $post->ID, 'my_meta_key', true );
    $meta = $post_meta[ 'time' ];
    echo " <input type = 'text' name = 'time' class = 'wpxpo-pop-name' required = 'required' placeholder = 'Time' id = 'wpxpo-pop-name' value = '$meta'/>";
}

function display_all_posts() {
    $query = new WP_Query( array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    ) );
    $options = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $options[ $post_id ] = $post_title;
        }
        wp_reset_postdata();
    }

    ?>
    <select name = 'posts' id = 'posts'>
    <option value = 'allposts'>Select an option</option>
    <?php
    foreach ( $options as $id => $title ) {
        echo "<option value='$title'>$title</option>";
    }
    ?>
    </select>
    <?php

}
