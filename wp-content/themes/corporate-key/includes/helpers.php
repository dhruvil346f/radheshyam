<?php
/**
 * Helper functions.
 *
 * @package Corporate_Key
 */

if ( ! function_exists( 'corporate_key_get_global_layout_options' ) ) :

	/**
	 * Returns global layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_global_layout_options() {
		$choices = array(
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'corporate-key' ),
			'right-sidebar' => esc_html__( 'Right Sidebar', 'corporate-key' ),
			'three-columns' => esc_html__( 'Three Columns', 'corporate-key' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'corporate-key' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'corporate_key_get_archive_layout_options' ) ) :

	/**
	 * Returns archive layout options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_archive_layout_options() {
		$choices = array(
			'full'    => esc_html__( 'Full Post', 'corporate-key' ),
			'excerpt' => esc_html__( 'Post Excerpt', 'corporate-key' ),
			'grid'    => esc_html__( 'Excerpt Grid', 'corporate-key' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'corporate_key_get_image_sizes_options' ) ) :

	/**
	 * Returns image sizes options.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $add_disable    True for adding No Image option.
	 * @param array $allowed        Allowed image size options.
	 * @param bool  $show_dimension True for showing dimension.
	 * @return array Image size options.
	 */
	function corporate_key_get_image_sizes_options( $add_disable = true, $allowed = array(), $show_dimension = true ) {

		global $_wp_additional_image_sizes;

		$choices = array();

		if ( true === $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'corporate-key' );
		}

		$choices['thumbnail'] = esc_html__( 'Thumbnail', 'corporate-key' );
		$choices['medium']    = esc_html__( 'Medium', 'corporate-key' );
		$choices['large']     = esc_html__( 'Large', 'corporate-key' );
		$choices['full']      = esc_html__( 'Full (original)', 'corporate-key' );

		if ( true === $show_dimension ) {
			foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
				$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
			}
		}

		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
			foreach ( $_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key;
				if ( true === $show_dimension ) {
					$choices[ $key ] .= ' (' . $size['width'] . 'x' . $size['height'] . ')';
				}
			}
		}

		if ( ! empty( $allowed ) ) {
			foreach ( $choices as $key => $value ) {
				if ( ! in_array( $key, $allowed, true ) ) {
					unset( $choices[ $key ] );
				}
			}
		}

		return $choices;

	}

endif;

if ( ! function_exists( 'corporate_key_get_image_alignment_options' ) ) :

	/**
	 * Returns image options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_image_alignment_options() {
		$choices = array(
			'none'   => esc_html_x( 'None', 'alignment', 'corporate-key' ),
			'left'   => esc_html_x( 'Left', 'alignment', 'corporate-key' ),
			'center' => esc_html_x( 'Center', 'alignment', 'corporate-key' ),
			'right'  => esc_html_x( 'Right', 'alignment', 'corporate-key' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'corporate_key_get_featured_slider_transition_effects' ) ) :

	/**
	 * Returns the featured slider transition effects.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_featured_slider_transition_effects() {
		$choices = array(
			'fade'       => esc_html_x( 'fade', 'transition effect', 'corporate-key' ),
			'fadeout'    => esc_html_x( 'fadeout', 'transition effect', 'corporate-key' ),
			'none'       => esc_html_x( 'none', 'transition effect', 'corporate-key' ),
			'scrollHorz' => esc_html_x( 'scrollHorz', 'transition effect', 'corporate-key' ),
		);
		ksort( $choices );
		return $choices;
	}

endif;

if ( ! function_exists( 'corporate_key_get_featured_slider_content_options' ) ) :

	/**
	 * Returns the featured slider content options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_featured_slider_content_options() {
		$choices = array(
			'home-page' => esc_html__( 'Static Front Page', 'corporate-key' ),
			'disabled'  => esc_html__( 'Disabled', 'corporate-key' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'corporate_key_get_featured_slider_type' ) ) :

	/**
	 * Returns the featured slider type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_featured_slider_type() {
		$choices = array(
			'featured-page' => esc_html__( 'Featured Pages', 'corporate-key' ),
		);
		return $choices;
	}

endif;

if ( ! function_exists( 'corporate_key_get_pagination_type_options' ) ) :

	/**
	 * Returns pagination type options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options array.
	 */
	function corporate_key_get_pagination_type_options() {
		$choices = array(
			'default' => esc_html__( 'Default (Older/Newer)', 'corporate-key' ),
			'numeric' => esc_html__( 'Numeric', 'corporate-key' ),
		);
		return $choices;
	}

endif;
