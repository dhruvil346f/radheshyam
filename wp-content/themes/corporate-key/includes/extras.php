<?php
/**
 * Functions hooked to core hooks.
 *
 * @package Corporate_Key
 */

if ( ! function_exists( 'corporate_key_customize_search_form' ) ) :

	/**
	 * Customize search form.
	 *
	 * @since 1.0.0
	 *
	 * @return string The search form HTML output.
	 */
	function corporate_key_customize_search_form() {

		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
			<label>
			<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'corporate-key' ) . '</span>
			<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search&hellip;', 'placeholder', 'corporate-key' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label', 'corporate-key' ) . '" />
			</label>
			<input type="submit" class="search-submit" value="&#xf002;" /></form>';

		return $form;

	}

endif;

add_filter( 'get_search_form', 'corporate_key_customize_search_form', 15 );

if ( ! function_exists( 'corporate_key_implement_excerpt_length' ) ) :

	/**
	 * Implement excerpt length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length The number of words.
	 * @return int Excerpt length.
	 */
	function corporate_key_implement_excerpt_length( $length ) {

		if ( is_admin() ) {
			return $length;
		}

		$excerpt_length = corporate_key_get_option( 'excerpt_length' );
		$excerpt_length = apply_filters( 'corporate_key_filter_excerpt_length', $excerpt_length );

		if ( absint( $excerpt_length ) > 0 ) {
			$length = absint( $excerpt_length );
		}

		return $length;

	}

endif;

add_filter( 'excerpt_length', 'corporate_key_implement_excerpt_length', 999 );

if ( ! function_exists( 'corporate_key_implement_read_more' ) ) :

	/**
	 * Implement read more in excerpt.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The excerpt.
	 */
	function corporate_key_implement_read_more( $more ) {
		$output = $more;
		$read_more_text = corporate_key_get_option( 'read_more_text' );

		if ( ! empty( $read_more_text ) ) {
			$output = ' <a href="'. esc_url( get_permalink() ) . '" class="read-more">' . esc_html( $read_more_text ) . '</a>';
		}

		return $output;
	}

endif;

add_filter( 'excerpt_more', 'corporate_key_implement_read_more' );

if ( ! function_exists( 'corporate_key_content_more_link' ) ) :

	/**
	 * Implement read more in content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more_link Read More link element.
	 * @param string $more_link_text Read More text.
	 * @return string Link.
	 */
	function corporate_key_content_more_link( $more_link, $more_link_text ) {
		$read_more_text = corporate_key_get_option( 'read_more_text' );

		if ( ! empty( $read_more_text ) ) {
			$more_link = str_replace( $more_link_text, esc_html( $read_more_text ), $more_link );
		}

		return $more_link;
	}

endif;

add_filter( 'the_content_more_link', 'corporate_key_content_more_link', 10, 2 );

if ( ! function_exists( 'corporate_key_custom_body_class' ) ) :

	/**
	 * Custom body class.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $input One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function corporate_key_custom_body_class( $input ) {

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$input[] = 'group-blog';
		}

		// Global layout.
		global $post;
		$global_layout = corporate_key_get_option( 'global_layout' );
		$global_layout = apply_filters( 'corporate_key_filter_theme_global_layout', $global_layout );

		// Check if single template.
		if ( $post && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'corporate_key_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}

		$input[] = 'global-layout-' . esc_attr( $global_layout );

		// Common class for three columns.
		switch ( $global_layout ) {
			case 'three-columns':
				$input[] = 'three-columns-enabled';
			break;

			default:
			break;
		}

		// Header overlap.
		$header_overlap_status = false;

		if ( ( is_front_page() && ! is_home() ) && 'home-page' === corporate_key_get_option( 'featured_slider_status' ) ) {
				$header_overlap_status = true;
		}

		$header_overlap_class = ( true === $header_overlap_status ) ? 'header-overlap-enabled' : 'header-overlap-disabled';
		$input[] = $header_overlap_class;

		return $input;
	}

endif;

add_filter( 'body_class', 'corporate_key_custom_body_class' );

if ( ! function_exists( 'corporate_key_custom_post_class' ) ) :

	/**
	 * Custom post class.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $input One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function corporate_key_custom_post_class( $input ) {

		if ( is_archive() || is_home() ) {

			// Archive layout.
			$archive_layout = corporate_key_get_option( 'archive_layout' );
			$input[] = 'layout-'. esc_attr( $archive_layout );
		}

		return $input;
	}

endif;

add_filter( 'post_class', 'corporate_key_custom_post_class' );


if ( ! function_exists( 'corporate_key_featured_image_instruction' ) ) :

	/**
	 * Message to show in the Featured Image Meta box.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Admin post thumbnail HTML markup.
	 * @param int    $post_id Post ID.
	 * @return string HTML.
	 */
	function corporate_key_featured_image_instruction( $content, $post_id ) {

		if ( in_array( get_post_type( $post_id ), array( 'post', 'page' ), true ) ) {
			$content .= '<strong>' . __( 'Recommended Image Sizes', 'corporate-key' ) . ':</strong><br/>';
			$content .= __( 'Slider Image', 'corporate-key' ) . ' : 1920px X 770px';
		}

		return $content;

	}

endif;

add_filter( 'admin_post_thumbnail_html', 'corporate_key_featured_image_instruction', 10, 2 );

if ( ! function_exists( 'corporate_key_custom_content_width' ) ) :

	/**
	 * Custom content width.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_custom_content_width() {

		global $post, $wp_query, $content_width;

		$global_layout = corporate_key_get_option( 'global_layout' );
		$global_layout = apply_filters( 'corporate_key_filter_theme_global_layout', $global_layout );

		// Check if single template.
		if ( $post  && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'corporate_key_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}
		switch ( $global_layout ) {

			case 'no-sidebar':
				$content_width = 1220;
				break;

			case 'three-columns':
				$content_width = 570;
				break;

			case 'left-sidebar':
			case 'right-sidebar':
				$content_width = 895;
				break;

			default:
				break;
		}

	}
endif;

add_filter( 'template_redirect', 'corporate_key_custom_content_width' );
