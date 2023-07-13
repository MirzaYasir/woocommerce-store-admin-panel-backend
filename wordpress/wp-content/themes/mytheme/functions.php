<?php

function enqueue_scripts_and_styles() {
    // Enqueue the main stylesheet
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());

    // Enqueue Lightgallery CSS
    wp_enqueue_style('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css');

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');

    // Enqueue Popper.js
    wp_enqueue_script('popper', get_template_directory_uri() . '/assets/js/popper.min.js', array('jquery'), '2.11.6', true);

    // Enqueue Bootstrap JavaScript
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('popper'), '5.3.0', true);

    // Enqueue Lightgallery JavaScript
    wp_enqueue_script('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/js/lightgallery-all.min.js', array('jquery'), '1.10.0', true);

    // Enqueue Custom Script
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/custom-script.js', array('jquery', 'lightgallery'), false, true);
}
add_action('wp_enqueue_scripts', 'enqueue_scripts_and_styles');




// function enqueue_bootstrap_scripts_and_styles() {
//     // Enqueue the main stylesheet
//     wp_enqueue_style('my-theme-style', get_stylesheet_uri());

//     // Enqueue Lightgallery CSS
//     wp_enqueue_style('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/css/lightgallery.min.css');

//     // Enqueue Bootstrap CSS
//     wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');

//     // Enqueue Popper.js
//     wp_enqueue_script('popper', get_template_directory_uri() . '/assets/popper.min.js', array('jquery'), '2.10.2', true);

//     // Enqueue Lightgallery JavaScript
//     wp_enqueue_script('lightgallery', 'https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.10.0/js/lightgallery-all.min.js', array('jquery'), '1.10.0', true);

//     // Enqueue Custom Script
//     wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/custom-script.js', array('jquery', 'lightgallery'), false, true);

//     // Enqueue Bootstrap JavaScript
//     wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '4.5.0', true);
// }
// add_action('wp_enqueue_scripts', 'enqueue_bootstrap_scripts_and_styles');


function enqueue_lazysizes_script() {
    wp_enqueue_script( 'lazysizes', get_template_directory_uri() . '/assets/lazy-loading/lazysizes.min.js', array(), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_lazysizes_script' );

function remove_google_fonts() {
    wp_dequeue_style('google-fonts-1-css');
    wp_deregister_style('google-fonts-1-css');
}
add_action('wp_enqueue_scripts', 'remove_google_fonts', 999);

function search_action_callback() {
    if (isset($_POST['searchTerm'])) {
        $searchTerm = $_POST['searchTerm'];
        $searchType = $_POST['type'];

        $data = [];
        if($searchType == 'brand_search') {
            global $wpdb;
            $searchTerm = $_POST['searchTerm'];
            $query = $wpdb->prepare("SELECT * FROM brands WHERE name LIKE %s", "%$searchTerm% LIMIT 5");
            $data = $wpdb->get_results($query, ARRAY_A);
        }

        // Example response
        $response = array(
            'status' => 'success',
            'message' => 'Search term: ' . $searchTerm,
            'data' => $data
        );

        // Send the response back to the client
        wp_send_json($response);
    }

    // If the search term is not set or the request is invalid, send an error response
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request'
    );
    wp_send_json($response);
}
add_action('wp_ajax_search_action', 'search_action_callback');
add_action('wp_ajax_nopriv_search_action', 'search_action_callback');