<?php

/*************************************************
 * Translation
 *************************************************/ 

// Make theme available for translation
// Translations can be filed in the /lang/ directory
load_theme_textdomain( 'glidingghost', TEMPLATEPATH . '/lang' );
 
$locale = get_locale();
$locale_file = TEMPLATEPATH . "/lang/$locale.php";
if ( is_readable($locale_file) )
	require_once($locale_file);


/*************************************************
 * Menus
 *************************************************/ 
add_theme_support( 'menus' );
register_nav_menus(  
        array( 
			'topbar'               => __('Tob Bar Menu'), 
            'tofc1'               => __('Table of Contents Left Menu'),
            'tofc2'               => __('Table of Contents Right Menu')
        ));  


class MV_Cleaner_Walker_Nav_Menu extends Walker {
    var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
    function start_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ol class=\"post-list\">\n";
    }
    function end_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ol>\n";
    }
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes = in_array( 'current-menu-item', $classes ) ? array( 'current-menu-item' ) : array();
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = strlen( trim( $class_names ) ) > 0 ? ' class="' . esc_attr( $class_names ) . '"' : '';
        $id = apply_filters( 'nav_menu_item_id', '', $item, $args );
        $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '<li' . $id . $value . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
    function end_el(&$output, $item, $depth) {
        $output .= "</li>\n";
    }
}
function mv_custom_menu_classes( $c )
{
    $c[] = 'post-stub';
    return $c;
}
add_filter( 'nav_menu_css_class', 'mv_custom_menu_classes' );


/*******************************************************************************
 * Prevent Media Gallery from inserting height/width (to keep imges responsive)
 *******************************************************************************/ 
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}


/*************************************************
 * Deregister WP Jquery And Add Our Own
 *************************************************/ 
add_filter( 'wp_default_scripts', 'dequeue_jquery_migrate' );

function dequeue_jquery_migrate( &$scripts){
	if(!is_admin()){
		$scripts->remove( 'jquery');
		$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
	}
}


 /*************************************************
 * Register Top Bar Widget Area
 *************************************************/ 
function glidingghost_widgets_init() {

	register_sidebar( array(
		'name' => 'Top Bar',
		'id' => 'top_bar',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
}
add_action( 'widgets_init', 'glidingghost_widgets_init' );


/*************************************************
 * Loads TGM Plugin Activation Script
 *************************************************/ 
require_once dirname( __FILE__ ) . '/includes/theme-activation.php';



add_filter( "term_links-portfolio", 'limit_terms');

function limit_terms($val) {
    return array_splice($val, 0, 1);
}


/*************************************************
 * Shortcodes to show post counts
 *************************************************/ 
function pu_post_count() {
	global $wpdb;
		
	return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->posts . ' WHERE post_status = "publish" AND post_type = "post"');
}
add_shortcode('number_of_posts', 'pu_post_count');
function pu_portfolio_count() {
	global $wpdb;
		
	return (int) $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->posts . ' WHERE post_status = "publish" AND post_type = "portfolio"');
}
add_shortcode('number_of_portfolio', 'pu_portfolio_count');


/*************************************************
 * Special Button Styles in Wordpress Editor
 *************************************************/ 
add_filter('mce_css', 'tuts_mcekit_editor_style');
function tuts_mcekit_editor_style($url) {

    if ( !empty($url) )
        $url .= ',';

    // Retrieves the plugin directory URL
    // Change the path here if using different directories
    $url .= trailingslashit( plugin_dir_url(__FILE__) ) . '/editor-styles.css';

    return $url;
}

	//  Add "Styles" drop-down
 
add_filter( 'mce_buttons_2', 'tuts_mce_editor_buttons' );

function tuts_mce_editor_buttons( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}


	// Add styles/classes to the "Styles" drop-down
 
add_filter( 'tiny_mce_before_init', 'tuts_mce_before_init' );

function tuts_mce_before_init( $settings ) {

    $style_formats = array(
      array( 'title' => __('Buttons', 'glidingghost')),
        array(
            'title' => __('Blue Button'),
            'selector' => 'a',
            'classes' => 'boxbutton blue-button'
            ),
        array(
            'title' => __('Gray Button'),
            'selector' => 'a',
            'classes' => 'boxbutton gray-button'
            ),
        array(
            'title' => __('Green Button'),
            'selector' => 'a',
            'classes' => 'boxbutton green-button'
            ),
        array(
            'title' => __('Highlight Color Button'),
            'selector' => 'a',
            'classes' => 'boxbutton highlight-button'
            ),
        array(
            'title' => __('Red Button'),
            'selector' => 'a',
            'classes' => 'boxbutton red-button'
            ),

       array( 'title' => __('Columns', 'glidingghost')),
       array( 'title' => __('1/2 Col.', 'glidingghost'),      'block'    => 'div',  'classes' => 'one_half' ),
       array( 'title' => __('1/2 Col. Last', 'glidingghost'), 'block'    => 'div',  'classes' => 'one_half last' ),
       array( 'title' => __('1/3 Col.', 'glidingghost'),      'block'    => 'div',  'classes' => 'one_third' ),
       array( 'title' => __('1/3 Col. Last', 'glidingghost'), 'block'    => 'div',  'classes' => 'one_third last' ),
       array( 'title' => __('2/3 Col.', 'glidingghost'),      'block'    => 'div',  'classes' => 'two_third' ),
       array( 'title' => __('2/3 Col. Last', 'glidingghost'), 'block'    => 'div',  'classes' => 'two_third last' ),
    );



    $settings['style_formats'] = json_encode( $style_formats );

    return $settings;

}

/*
 * Add custom stylesheet to the website front-end with hook 'wp_enqueue_scripts'
 */
add_action('wp_enqueue_scripts', 'tuts_mcekit_editor_enqueue');

/*
 * Enqueue stylesheet, if it exists.
 */
function tuts_mcekit_editor_enqueue() {
  $StyleUrl = plugin_dir_url(__FILE__).'editor-styles.css'; // Customstyle.css is relative to the current file
  wp_enqueue_style( 'myCustomStyles', $StyleUrl );
}


/*************************************************
 * Clean Up Wordpress Header
 *************************************************/ 
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);


/*************************************************
 * Next/Previous Buttons Styles And Output
 *************************************************/ 

add_filter('next_post_link', 'post_link_attributes1');
add_filter('previous_post_link', 'post_link_attributes2');

function post_link_attributes1($output) {
    $injection = 'class="newer-posts"';
    return str_replace('<a href=', '<a '.$injection.' href=', $output);
}
function post_link_attributes2($output) {
    $injection = 'class="older-posts"';
    return str_replace('<a href=', '<a '.$injection.' href=', $output);
}


/*************************************************
 * Enqueue CSS And Javascript
 *************************************************/ 
function glidingghost_scripts_styles() {

	// Loads JavaScript file with functionality specific to Ghost Writer.
	wp_deregister_script( 'jquery' );
	wp_enqueue_script( 'jquery', 'http://code.jquery.com/jquery-latest.js', array(), '1', true  );
	wp_enqueue_script( 'glidingghost-jquery-history', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), '1', true );

	// Loads our main stylesheet.
	wp_enqueue_style( 'glidingghost-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300' );	
	wp_enqueue_style( 'glidingghost-normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
	wp_enqueue_style( 'glidingghost-loading', get_template_directory_uri() . '/assets/css/nprogress.css' );
	wp_enqueue_style( 'glidingghost-style', get_stylesheet_uri(), array(), '' );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'glidingghost-ie', get_template_directory_uri() . '/assets/css/ie.css', array( 'glidingghost-style' ), '2013-07-18' );
	wp_style_add_data( 'glidingghost-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'glidingghost_scripts_styles' );



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 
	//   This is the start of the Gliding Ghost Theme Customizer Options.
	//
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Adding installation functions to the Wordpress Theme Customizer:
    function glidingghost_theme_customizer( $wp_customize ) {

    // Allows the site title and tagline t auto-refresh:
    $wp_customize->get_setting('blogname')->transport='postMessage';
    $wp_customize->get_setting('blogdescription')->transport='postMessage';
	$wp_customize->remove_section( 'title_tagline');

	// Create the different sections. (not including default Wordpress sections)

    $wp_customize->add_section( 'postlist', array(
        'title' => __('Post List', 'glidingghost'), // The title of section
        'description' => __('Styles and options for the list of posts.', 'glidingghost'), // The description of section
		'priority'       => 180,
    ) );
	
    $wp_customize->add_section( 'tofc', array(
        'title' => __('Table of Contents', 'glidingghost'), // The title of section
        'description' => __('Styles and options for the Table of Contents page.', 'glidingghost'), // The description of section
		'priority'       => 190,
    ) );

    $wp_customize->add_section( 'footer', array(
        'title' => __('Footer', 'glidingghost'), // The title of section
        'description' => __('Set your footer and credits.', 'glidingghost'), // The description of section
		'priority'       => 200,
    ) );

    $wp_customize->add_section( 'custom-css', array(
        'title' => __('Custom CSS', 'glidingghost'), // The title of section
        'description' => __('Add any Custom CSS you have here.', 'glidingghost'), // The description of section
		'priority'       => 210,
    ) );

    // Adding installation functions to the Wordpress Theme Customizer:
    $wp_customize->add_section( 'google-analytics', array(
        'title' => __('Google Analytics', 'glidingghost'), // The title of section
        'description' => __('Enter your Google Analytics ID code here.', 'glidingghost'), // The description of section
		'priority'       => 210,
    ) );

	// Remove the /// comment lines below to remove the Tagline field from the Title & Tagline section. Not recommended.
	/// $wp_customize->remove_control( 'blogdescription');


    //////////////////////////
    // Various Color Selectors
    //////////////////////////

    // Page Background Color Selector.
    $wp_customize->add_setting( 'glidingghost_color_bkg', array(
        'default' => '#fefefe',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_bkg', array(
        'label'   => __('Page Background Color', 'glidingghost'),
        'section' => 'colors',
		'priority'       => 10,	
		'setting' => 'glidingghost_color_bkg',
    ) ) );


    // Main Link Color Selector.
    $wp_customize->add_setting( 'glidingghost_color_highlight', array(
        'default' => '#618C7C',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_highlight', array(
        'label'   => __('Highlight Button Color', 'glidingghost'),
        'section' => 'colors',
		'priority'       => 20,
		'setting' => 'glidingghost_color_highlight'
    ) ) );


    // Main Link Color Selector.
    $wp_customize->add_setting( 'glidingghost_color_highlighttext', array(
        'default' => '#FFFFFF',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_highlighttext', array(
        'label'   => __('Highlight Text Color'),
        'section' => 'colors',
		'priority'       => 30,
		'setting' => 'glidingghost_color_highlighttext'
    ) ) );
	
    // Main Link Hover Color Selector.
    $wp_customize->add_setting( 'glidingghost_color_highlighthover', array(
        'default' => '#121212',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_highlighthover', array(
        'label'   => __('Highlight Button Hover', 'glidingghost'),
        'section' => 'colors',
		'priority'       => 40,
		'setting' => 'glidingghost_color_highlighthover'
    ) ) );

    // Main Link Hover Color Selector.
    $wp_customize->add_setting( 'glidingghost_color_highlighthovertext', array(
        'default' => '#FFFFFF',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_highlighthovertext', array(
        'label'   => __('Highlight Button Text Hover', 'glidingghost'),
        'section' => 'colors',
		'priority'       => 50,
		'setting' => 'glidingghost_color_highlighthovertext'
    ) ) );


    // Post List Link Color.
    $wp_customize->add_setting( 'glidingghost_color_listlinktext', array(
        'default' => '#424242',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_listlinktext', array(
        'label'   => __('Post List Text', 'glidingghost'),
        'section' => 'postlist',
		'priority'=> 10,
		'setting' => 'glidingghost_color_listlinktext'
    ) ) );

    // Post List Link Text Hover Color.
    $wp_customize->add_setting( 'glidingghost_color_listlinktexthover', array(
        'default' => '#618C7C',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_listlinktexthover', array(
        'label'   => __('Post List Text Hover', 'glidingghost'),
        'section' => 'postlist',
		'priority'=> 20,
		'setting' => 'glidingghost_color_listlinktexthover'
    ) ) );


    // Post List Link BG Hover Color.
    $wp_customize->add_setting( 'glidingghost_color_listlinkbghover', array(
        'default' => '#FCF5F5',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_listlinkbghover', array(
        'label'   => __('Post List Background Hover', 'glidingghost'),
        'section' => 'postlist',
		'priority'=> 30,
		'setting' => 'glidingghost_color_listlinkbghover'
    ) ) );

    // Font Color Selector.
    $wp_customize->add_setting( 'glidingghost_color_fonts', array(
        'default' => '#424242',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_fonts', array(
        'label'   => __('Site Font Color', 'glidingghost'),
        'section' => 'colors',
		'priority'       => 60,
		'setting' => 'glidingghost_color_fonts'
    ) ) );

    // Date Byline Color
    $wp_customize->add_setting( 'glidingghost_color_byline', array(
        'default' => '#AEADAD',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_byline', array(
        'label'   => __('Subtitle Byline Color', 'glidingghost'),
        'section' => 'colors',
		'priority'       => 70,
		'setting' => 'glidingghost_color_byline'
    ) ) );

	// Settings for section: TABLE OF CONTENTS

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // This creates the dropdown list of pages in the Theme Customizer and outputs the slug into the link instead of the page_ID.
	$list_pages = array();
	$list_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$list_pages[''] = __('Select a page:', 'glidingghost');
	foreach ($list_pages_obj as $page) {
		$list_pages[$page->post_name] = $page->post_title;
	}
    $wp_customize->add_setting('glidingghost_toc', array(
        'capability'     => 'edit_theme_options',
        'type'           => 'option',

    ));
 
    $wp_customize->add_control('glidingghost_toc_link', array(
        'label'      => __('Page for the Table of Contents', 'glidingghost'),
        'section' => 'tofc',
		'priority'=> 10,
        'type' => 'select',
        'choices' => $list_pages,
        'settings'   => 'glidingghost_toc',
    ));

    // Table of Contents Background Color.
    $wp_customize->add_setting( 'glidingghost_color_tocbody', array(
        'default' => '#618C7C',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_tocbody', array(
        'label'   => __('Background Color', 'glidingghost'),
        'section' => 'tofc',
		'priority'=> 20,
		'setting' => 'glidingghost_color_tocbody'
    ) ) );

    // Table of Contents Font Color.
    $wp_customize->add_setting( 'glidingghost_color_tocfontcolor', array(
        'default' => '#ffffff',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_tocfontcolor', array(
        'label'   => __('Font Color', 'glidingghost'),
        'section' => 'tofc',
		'priority'=> 30,
		'setting' => 'glidingghost_color_tocfontcolor'
    ) ) );

    // Left Column Title
	$wp_customize->add_setting( 'glidingghost_tofc_titleleft', array(
 	   'default' => 'Explore This Site',
 	   'type' => 'option',
 	   'transport' => 'postMessage'
	) );
	$wp_customize->add_control( 'glidingghost_tofc_titleleft', array(
	    'label' => __('Left Column Title', 'glidingghost'),
	    'section' => 'tofc',
		'priority'=> 40,		
	) );

    // Right Column Title
	$wp_customize->add_setting( 'glidingghost_tofc_titleright', array(
 	   'default' => 'Social',
 	   'type' => 'option',
 	   'transport' => 'postMessage'
	) );
	$wp_customize->add_control( 'glidingghost_tofc_titleright', array(
	    'label' => __('Right Column Title', 'glidingghost'),
	    'section' => 'tofc',
		'priority'=> 50,		
	) );

    // Table of Contents Link Color.
    $wp_customize->add_setting( 'glidingghost_color_toclink', array(
        'default' => '#fefefe',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_toclink', array(
        'label'   => __('Link Color', 'glidingghost'),
        'section' => 'tofc',
		'priority'=> 60,
		'setting' => 'glidingghost_color_toclink'
    ) ) );

    // Table of Contents Link Text Hover Color.
    $wp_customize->add_setting( 'glidingghost_color_toclinktexthover', array(
        'default' => '#618C7C',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_toclinktexthover', array(
        'label'   => __('Link Text Hover Color', 'glidingghost'),
        'section' => 'tofc',
		'priority'=> 70,
		'setting' => 'glidingghost_color_toclinktexthover'
    ) ) );


    // Table of Contents Link BG Hover Color.
    $wp_customize->add_setting( 'glidingghost_color_toclinkbghover', array(
        'default' => '#FCF5F5',
        'type' => 'option',
        'sanitize_callback'    => 'sanitize_hex_color_no_hash',
        'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'glidingghost_color_toclinkbghover', array(
        'label'   => __('Link Background Hover Color', 'glidingghost'),
        'section' => 'tofc',
		'priority'=> 80,
		'setting' => 'glidingghost_color_toclinkbghover'
    ) ) );

	// Settings for section: FOOTER
	
	$wp_customize->add_setting( 'glidingghost_credits', array(
 	   'default' => '&copy; Copyright 2013. Tigers will eat your face if you remove this.',
 	   'type' => 'option',
 	   'transport' => 'postMessage'
	) );
	$wp_customize->add_control( 'glidingghost_credits', array(
	    'label' => __('Footer Credits', 'glidingghost'),
	    'section' => 'footer'
	) );


    // Check box to show/hide top nav.
	$wp_customize->add_setting( 'glidingghost_topbar_menu', array(
        'default' => 0,
	) );

	$wp_customize->add_control( 'glidingghost_topbar_menu', array(
        'label' => __('Show Topbar (navigation/widget).'),
        'type' => 'checkbox',
        'section' => 'nav',
	) );


	// Settings for section: CUSTOM CSS	
	
	/////////////////////////////////////////////////////////////////////////////////////
    // Adds a textfield option to the Theme Customizer. Allows for the Custom CSS option.

	class Example_Customize_Textarea_Control extends WP_Customize_Control {
   	 public $type = 'textarea';
 
  	  public function render_content() {
    	    ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
        <?php
 	   }
	}

	$wp_customize->add_setting( 'glidingghost_custom_css', array(
	) );
 
	$wp_customize->add_control(new Example_Customize_Textarea_Control($wp_customize,'glidingghost_custom_css',array(
		'label' => __('Add any custom CSS you have here.', 'glidingghost'),
		'section' => 'custom-css',
		'type' => 'textarea',
		'settings' => 'glidingghost_custom_css',
		)
  	  )
	);

	// Settings for section: GOOGLE ANALYTICS		
	$wp_customize->add_setting( 'glidingghost_google_analytics', array(
        'default' => '',
	) );
 
	$wp_customize->add_control('glidingghost_google_analytics',array(
		'label' => __('Add your Google Analytics ID here. (Ex: UA-XXXXXXX-X)', 'glidingghost'),
		'section' => 'google-analytics',
		'type' => 'text',
		'settings' => 'glidingghost_google_analytics',
		)
 	);

    // The action below calls the contained live-editing javascript ONLY in the Theme Customizer.
	if ( $wp_customize->is_preview() && ! is_admin() ) {
		function glidingghost_customize_preview() {
 		   ?>
		    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/theme-customizer.js"></script>
		   <?php
		}  // End function glidingghost_customize_preview()
  	  add_action( 'wp_footer', 'glidingghost_customize_preview', 21);
	}
	
}

    // The below function outputs color/other styles from the Theme Customizer into the Wordpress header.


if (!is_admin()) {
	
	//Output the styles in the header.
	add_action('wp_head', 'glidingghost_custom_css');
	
	//begin glidingghost_custom_css()
	function glidingghost_custom_css() {
		
		$custom_css ='';
		
		/**custom css field**/
		if(get_option('glidingghost_color_bkg') != 'FEFEFE') {
			$custom_css .= 'body { background-color:#'.get_option('glidingghost_color_bkg').';}';
		}
		if(get_option('glidingghost_color_highlight') != '618C7C') {
			$custom_css .= '#topbar a:hover, .post-content a:hover, #commentform a:hover {border-bottom: 1px dotted #'.get_option('glidingghost_color_highlight').'}'.'#topbar a:link, #topbar a:visited, .post-content a:link, .post-content a:visited {color: #'.get_option('glidingghost_color_highlight').'}'.'.site-title a, .button-square, .newer-posts, .older-posts,.highlight-button, #submit, #cancel-comment-reply-link, #nprogress .bar {background: #'.get_option('glidingghost_color_highlight').'}'.'#nprogress .peg {box-shadow: 0 0 10px #'.get_option('glidingghost_color_highlight').', 0 0 5px #'.get_option('glidingghost_color_highlight').'}'.'#nprogress .spinner-icon {border-top-color: #'.get_option('glidingghost_color_highlight').';border-left-color: #'.get_option('glidingghost_color_highlight').'}';
		}
		if(get_option('glidingghost_color_highlighttext') != 'FFFFFF') {
			$custom_css .= '.pagination a,.site-title a, #submit, #cancel-comment-reply-link, .highlight-button, .site-title a:link, a.js-ajax-link {color: #'.get_option('glidingghost_color_highlighttext').';}';
		}
		if(get_option('glidingghost_color_highlighthover') != '121212') {
			$custom_css .= '.site-title a:hover, .button-square:hover, .boxbutton:hover {background-color: #'.get_option('glidingghost_color_highlighthover').';}';
		}
		if(get_option('glidingghost_color_highlighthovertext') != 'FFFFFF') {
			$custom_css .= '.pagination a:hover,.site-title a:hover, #submit:hover, #cancel-comment-reply-link:hover {color: #'.get_option('glidingghost_color_highlighthovertext').';'.'border-color: #'.get_option('glidingghost_color_highlighthovertext').';}';
		}
		if(get_option('glidingghost_color_listlinktext') != '424242') {
			$custom_css .= '.post-stub a { color:#'.get_option('glidingghost_color_listlinktext').';}'.'.tofc .post-stub { border-bottom: 1px dotted #'.get_option('glidingghost_color_listlinktext').';}';
		}
		if(get_option('glidingghost_color_listlinktexthover') != '618C7C') {
			$custom_css .= '.post-stub a:hover { color:#'.get_option('glidingghost_color_listlinktexthover').';}';
		}
		if(get_option('glidingghost_color_listlinkbghover') != 'FCF5F5') {
			$custom_css .= '.post-stub a:hover { background-color: #'.get_option('glidingghost_color_listlinkbghover').';}';
		}
		if(get_option('glidingghost_color_fonts') != '424242') {
			$custom_css .= 'body { color:#'.get_option('glidingghost_color_fonts').';}'.'.post-header {border-bottom:6px solid #'.get_option('glidingghost_color_fonts').'}'.'.post-date:after {border-bottom:1px dotted #'.get_option('glidingghost_color_fonts').'}'.'#topbar .open {border-bottom:1px solid #'.get_option('glidingghost_color_fonts').'}'.'.post-navigation {border-top:1px solid #'.get_option('glidingghost_color_fonts').'}';
		}
		if(get_option('glidingghost_color_byline') != 'AEADAD') {
			$custom_css .= '.post-date { color:#'.get_option('glidingghost_color_byline').';}';
		}
		if(get_option('glidingghost_color_tocbody') != '618C7C') {
			$custom_css .= 'body.tofc,.tofc .homelink { background-color:#'.get_option('glidingghost_color_tocbody').';}';
		}
		if(get_option('glidingghost_color_tocfontcolor') != 'ffffff') {
			$custom_css .= 'body.tofc, .tofc .homelink, a:link.highlight-button, a:link.gray-button, a:link.blue-button, a:link.red-button, a:link.green-button { color:#'.get_option('glidingghost_color_tocfontcolor').';}'.'body.tofc .post-header { border-bottom: 6px solid #'.get_option('glidingghost_color_tocfontcolor').';}';
		}
		if(get_option('glidingghost_color_toclink') != 'fefefe') {
			$custom_css .= '.tofc .post-stub a { color:#'.get_option('glidingghost_color_toclink').';}'.'.tofc .post-stub { border-bottom: 1px dotted #'.get_option('glidingghost_color_toclink').';}';
		}
		if(get_option('glidingghost_color_toclinktexthover') != '618C7C') {
			$custom_css .= '.tofc .post-stub a:hover { color:#'.get_option('glidingghost_color_toclinktexthover').';}';
		}
		if(get_option('glidingghost_color_toclinkbghover') != 'FCF5F5') {
			$custom_css .= '.tofc .post-stub a:hover { background-color: #'.get_option('glidingghost_color_toclinkbghover').';}';
		}
		if(get_theme_mod('glidingghost_custom_css') !='0') {
			$custom_css .= get_theme_mod('glidingghost_custom_css');
		}
		
		/** Displays all Custom CSS in header **/
		$css_output = "<!-- Custom CSS Styles -->\n<style type=\"text/css\">\n" . $custom_css . "\n</style>";
		if(!empty($custom_css)) { echo $css_output;}
		
	} //end glidingghost_custom_css()

} //end Custom CSS Header function.


add_action('wp_footer', 'ga');
 
function ga() {
 
    $account = get_theme_mod('glidingghost_google_analytics');
     
    $code = "<script type=\"text/javascript\"> 
      
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '$account']);
      _gaq.push(['_trackPageview']);
      
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
      
    </script>"; 
     
    if (get_theme_mod('glidingghost_google_analytics') != '') echo $code;
 
}


    // The action below wraps up the Theme Customizer options.
	add_action( 'customize_register', 'glidingghost_theme_customizer', 11 );

?>