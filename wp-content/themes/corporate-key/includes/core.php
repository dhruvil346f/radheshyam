<?php
/**
 * Core functions.
 *
 * @package Corporate_Key
 */

if ( ! function_exists( 'corporate_key_get_option' ) ) :

	/**
	 * Get theme option.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function corporate_key_get_option( $key ) {

		$corporate_key_default_options = corporate_key_get_default_theme_options();

		if ( empty( $key ) ) {
			return;
		}

		$default = ( isset( $corporate_key_default_options[ $key ] ) ) ? $corporate_key_default_options[ $key ] : '';
		$theme_options = get_theme_mod( 'theme_options', $corporate_key_default_options );
		$theme_options = array_merge( $corporate_key_default_options, $theme_options );
		$value = '';

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;

	}

endif;

if ( ! function_exists( 'corporate_key_get_options' ) ) :

	/**
	 * Get all theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Theme options.
	 */
	function corporate_key_get_options() {

		$value = array();
		$value = get_theme_mod( 'theme_options' );
		return $value;

	}

endif;

if ( ! function_exists( 'corporate_key_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
	function corporate_key_get_default_theme_options() {

		$defaults = array();

		// Header.
		$defaults['show_title']            = true;
		$defaults['show_tagline']          = true;
		$defaults['show_search_in_header'] = true;

		// Layout.
		$defaults['global_layout']  = 'right-sidebar';
		$defaults['archive_layout'] = 'grid';

		// Pagination.
		$defaults['pagination_type'] = 'default';

		// Footer.
		$defaults['copyright_text'] = esc_html__( 'Copyright &copy; All rights reserved.', 'corporate-key' );

		// Blog.
		$defaults['blog_page_title'] = esc_html__( 'Blog', 'corporate-key' );
		$defaults['excerpt_length']  = 40;
		$defaults['read_more_text']  = esc_html__( 'Read More', 'corporate-key' );

		// Slider Options.
		$defaults['featured_slider_status']              = 'disabled';
		$defaults['featured_slider_transition_effect']   = 'fadeout';
		$defaults['featured_slider_transition_delay']    = 3;
		$defaults['featured_slider_transition_duration'] = 1;
		$defaults['featured_slider_enable_caption']      = true;
		$defaults['featured_slider_enable_arrow']        = true;
		$defaults['featured_slider_enable_pager']        = true;
		$defaults['featured_slider_enable_autoplay']     = true;
		$defaults['featured_slider_type']                = 'featured-page';
		$defaults['featured_slider_number']              = 3;
		$defaults['featured_slider_read_more_text']      = esc_html__( 'Read More', 'corporate-key' );

		return apply_filters( 'corporate_key_filter_default_theme_options', $defaults );
	}

endif;
