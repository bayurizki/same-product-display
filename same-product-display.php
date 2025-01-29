<?php
/**
 * Plugin Name: Same Product Display
 * Description: Menampilkan produk dengan nama yang sama di halaman produk detail WooCommerce sebelum form variasi.
 * Version: 1.1
 * Author: [Nama Anda]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Jangan diakses langsung.
}

// Hook untuk mendaftarkan dan memuat file CSS di halaman produk WooCommerce.
add_action( 'wp_enqueue_scripts', 'same_product_display_enqueue_styles' );

function same_product_display_enqueue_styles() {
    // Muat hanya di halaman produk WooCommerce.
    if ( is_product() ) {
        wp_enqueue_style(
            'same-product-display-style', // Handle unik untuk file CSS.
            plugin_dir_url( __FILE__ ) . 'assets/style.css', // URL ke file CSS.
            array(), // Dependencies (jika ada).
            '2.0', // Versi file CSS.
            'all' // Media untuk file CSS.
        );
    }
}


// Hook untuk menampilkan produk dengan nama yang sama.
add_action( 'woocommerce_single_product_summary', 'same_product_display', 21 );

function same_product_display() {
    if ( ! is_product() ) {
        return;
    }

    global $post;

    // Dapatkan nama produk saat ini.
    $product_name = get_the_title( $post->ID );

    // Query produk dengan nama yang sama.
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__not_in'   => array( $post->ID ), // Kecualikan produk saat ini.
        's'              => $product_name, // Cari berdasarkan nama.
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        echo '<div class="same-product-display-grid">';
        echo 'Pilih Warna';
        echo '<div class="product-grid">';

        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;

            echo '<div class="product-item">';
            echo '<a href="' . get_the_permalink() . '">';
            echo woocommerce_get_product_thumbnail(); // Menampilkan thumbnail produk.
            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }

    wp_reset_postdata();
}
