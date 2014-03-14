<?php 
function add_processing_script()
{
    // Processing script
    // Enable the canvas to run processing
    wp_register_script( 'processing-script', plugins_url( 'js/processing.js', __FILE__ ) );
    wp_register_script( 'processing-script', get_template_directory_uri() . '/js/processing.js' );
    wp_enqueue_script( 'processing-script' );

    // Jrocessing script 
    //Enale automatic full screen and responsive
    wp_register_script( 'jprocessing-script', plugins_url( 'js/jprocessing.js', __FILE__ ) );
    wp_register_script( 'jprocessing-script', get_template_directory_uri() . '/js/jprocessing.js' );
    wp_enqueue_script( 'jprocessing-script' );


}


add_action( 'wp_enqueue_scripts', 'add_processing_script' );