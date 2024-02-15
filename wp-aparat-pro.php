<?php
/*
Plugin Name: WP-APARAT-PRO
Plugin URI: https://github.com/HoseinNikmaramOfficial/WP-APARAT-PRO
Description: a wordpress plugin for display videos from aparat.
Version: 1.0
Author: Hosein Nikmaram
Author URI: https://github.com/HoseinNikmaramOfficial
*/

define("PLUGIN_URI", plugin_dir_url(__FILE__));
define("CSS", trailingslashit(PLUGIN_URI . "assets/css"));
define("JS", trailingslashit(PLUGIN_URI . "assets/js"));

add_action('wp_enqueue_scripts','load_assets');

function load_assets(){
      if (!is_admin()) {
        wp_enqueue_style('style', CSS . "style.css");
        wp_enqueue_script('main', JS . "main.js");
    }
}

// Register the block
function aparat_gutenberg_block() {
    wp_register_script(
        'aparat-gutenberg-block',
        plugins_url( 'assets/js/block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor' )
    );

    register_block_type( 'aparat/aparat-block', array(
        'editor_script' => 'aparat-gutenberg-block',
    ) );
}
add_action( 'init', 'aparat_gutenberg_block' );

add_shortcode('aparat_block', 'aparat_block_shortcode');

function aparat_block_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'username' => 'example',
            'videocount' => '1',
        ),
        $atts,
        'aparat_block'
    );

    $username = $atts['username'];
    $video_number = $atts['videocount'];
    $api_url = "https://www.aparat.com/etc/api/videoByUser/username/{$username}/perpage/{$video_number}";
    // Fetch data from API
    $response = wp_remote_get($api_url);

    // Check for errors
    if (is_wp_error($response)) {
        return;
    }

    // Parse response body
    $body = wp_remote_retrieve_body($response);
    $videos = json_decode($body, true);

    // Check if response is valid
    if (empty($videos) || empty($videos['videobyuser'])) {
        return;
    }

    // Prepare HTML for displaying videos
    $output = '<div class="wp-aparat-pro">';
    $output .= '<ul>';
    foreach ($videos['videobyuser'] as $video) {
        $output .= '<li>';
        $output .= '<img src="' . esc_url($video['small_poster']) . '" alt="' . esc_attr($video['title']) . '" />';
        $output .= '<a target="_blank" href="https://aparat.com/v/' . $video['uid'] . '">' . esc_html($video['title']) . '</a>';
        $output .= '</li>';
    }
    $output .= '</ul>';
    $output .= '</div>';

    return $output;
}

?>