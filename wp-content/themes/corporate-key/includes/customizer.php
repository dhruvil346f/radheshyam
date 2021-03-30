<?php
/**
 * Theme Customizer.
 *
 * @package Corporate_Key
 */

/**
 * Add Customizer options.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporate_key_customize_register( $wp_customize ) {

	// Load custom controls.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/control.php';

	// Custom controls and sections.
	$wp_customize->register_control_type( 'Corporate_Key_Heading_Control' );
	$wp_customize->register_control_type( 'Corporate_Key_Message_Control' );
	$wp_customize->register_section_type( 'Corporate_Key_Upsell_Section' );

	// Upsell section.
	$wp_customize->add_section(
		new Corporate_Key_Upsell_Section( $wp_customize, 'custom_theme_upsell',
			array(
				'title'    => esc_html__( 'Corporate Key Pro', 'corporate-key' ),
				'pro_text' => esc_html__( 'Buy Pro', 'corporate-key' ),
				'pro_url'  => 'https://axlethemes.com/wordpress-themes/corporate-key-pro/',
				'priority' => 1,
			)
		)
	);

	// Load helpers.
	require_once trailingslashit( get_template_directory() ) . 'includes/helpers.php';

	// Load customize sanitize.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/sanitize.php';

	// Load customize callback.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/callback.php';

	// Load customize option.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/option.php';

	// Load slider customize option.
	require_once trailingslashit( get_template_directory() ) . 'includes/customizer/slider.php';

	// Modify default customizer options.
	$wp_customize->get_control( 'background_color' )->description = __( 'Note: Background Color is applicable only if no image is set as Background Image.', 'corporate-key' );

}
add_action( 'customize_register', 'corporate_key_customize_register' );

/**
 * Customizer control assets.
 *
 * @since 1.0.0
 */
function corporate_key_customizer_control_scripts() {

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'corporate-key-customize-controls', get_template_directory_uri() . '/js/customize-controls' . $min . '.js', array( 'jquery', 'customize-controls' ), '2.0.2' );

	wp_enqueue_style( 'corporate-key-customize-controls', get_template_directory_uri() . '/css/customize-controls' . $min . '.css', array(), '2.0.2' );

}

add_action( 'customize_controls_enqueue_scripts', 'corporate_key_customizer_control_scripts', 0 );

/**
 * Register Customizer partials.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporate_key_customizer_partials( WP_Customize_Manager $wp_customize ) {

	// Bail if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->get_setting( 'blogname' )->transport                      = 'refresh';
		$wp_customize->get_setting( 'blogdescription' )->transport               = 'refresh';
		$wp_customize->get_setting( 'theme_options[copyright_text]' )->transport = 'refresh';
		return;
	}

	$wp_customize->get_setting( 'blogname' )->transport                      = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport               = 'postMessage';
	$wp_customize->get_setting( 'theme_options[copyright_text]' )->transport = 'postMessage';

	// Register partial for blogname.
	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector'            => '.site-title a',
		'container_inclusive' => false,
		'render_callback'     => 'corporate_key_customize_partial_blogname',
	) );

	// Register partial for blogdescription.
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector'            => '.site-description',
		'container_inclusive' => false,
		'render_callback'     => 'corporate_key_customize_partial_blogdescription',
	) );

	// Register partial for copyright.
	$wp_customize->selective_refresh->add_partial( 'copyright', array(
		'settings'            => 'theme_options[copyright_text]',
		'selector'            => '#copyright',
		'container_inclusive' => false,
		'render_callback'     => 'corporate_key_customize_partial_copyright',
	) );

}

add_action( 'customize_register', 'corporate_key_customizer_partials', 99 );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function corporate_key_customize_partial_blogname() {

	bloginfo( 'name' );

}

/**
 * Render the site title for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function corporate_key_customize_partial_blogdescription() {

	bloginfo( 'description' );

}

/**
 * Render the copyright for the selective refresh partial.
 *
 * @since 1.0.0
 *
 * @return void
 */
function corporate_key_customize_partial_copyright() {

	$copyright_text = corporate_key_get_option( 'copyright_text' );
	$copyright_text = apply_filters( 'corporate_key_filter_copyright_text', $copyright_text );
	echo wp_kses_post( $copyright_text );

}
