<?php
/**
 * Implementation of slider feature.
 *
 * @package Corporate_Key
 */

if ( ! function_exists( 'corporate_key_add_featured_slider' ) ) :

	/**
	 * Add featured slider.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_featured_slider() {

		$flag_apply_slider = apply_filters( 'corporate_key_filter_slider_status', true );

		if ( true !== $flag_apply_slider ) {
			return;
		}

		$slider_details = array();
		$slider_details = apply_filters( 'corporate_key_filter_slider_details', $slider_details );

		if ( empty( $slider_details ) ) {
			return;
		}

		corporate_key_render_featured_slider( $slider_details );

	}

endif;

add_action( 'corporate_key_action_before_content', 'corporate_key_add_featured_slider', 5 );

if ( ! function_exists( 'corporate_key_check_slider_status' ) ) :

	/**
	 * Check status of slider.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $input Slider status.
	 */
	function corporate_key_check_slider_status( $input ) {

		$input = false;

		// Slider status.
		$featured_slider_status = corporate_key_get_option( 'featured_slider_status' );

		switch ( $featured_slider_status ) {
			case 'home-page':
				if ( is_front_page() && ! is_home() ) {
					$input = true;
				}
				break;

			case 'disabled':
				$input = false;
				break;

			default:
				break;
		}

		return $input;

	}

endif;

add_filter( 'corporate_key_filter_slider_status', 'corporate_key_check_slider_status' );

if ( ! function_exists( 'corporate_key_get_slider_details' ) ) :

	/**
	 * Slider details.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Slider details.
	 */
	function corporate_key_get_slider_details( $input ) {

		$featured_slider_type           = corporate_key_get_option( 'featured_slider_type' );
		$featured_slider_number         = corporate_key_get_option( 'featured_slider_number' );
		$featured_slider_read_more_text = corporate_key_get_option( 'featured_slider_read_more_text' );

		switch ( $featured_slider_type ) {

			case 'featured-page':

				$ids = array();

				for ( $i = 1; $i <= $featured_slider_number ; $i++ ) {
					$id = corporate_key_get_option( 'featured_slider_page_' . $i );
					if ( ! empty( $id ) ) {
						$ids[] = absint( $id );
					}
				}

				// Bail if no valid ids.
				if ( empty( $ids ) ) {
					return $input;
				}

				$qargs = array(
					'posts_per_page' => esc_attr( $featured_slider_number ),
					'no_found_rows'  => true,
					'orderby'        => 'post__in',
					'post_type'      => 'page',
					'post__in'       => $ids,
					'meta_query'     => array(
						array( 'key' => '_thumbnail_id' ),
					),
				);

				// Fetch posts.
				$all_posts = get_posts( $qargs );
				$slides = array();

				if ( ! empty( $all_posts ) ) {

					$cnt = 0;
					foreach ( $all_posts as $key => $post ) {

						if ( has_post_thumbnail( $post->ID ) ) {
							$image_array = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
							$slides[ $cnt ]['images']  = $image_array;
							$slides[ $cnt ]['title']   = $post->post_title;
							$slides[ $cnt ]['url']     = get_permalink( $post->ID );
							$slides[ $cnt ]['excerpt'] = corporate_key_get_the_excerpt( apply_filters( 'corporate_key_filter_slider_caption_length', 30 ), $post );

							if ( ! empty( $featured_slider_read_more_text ) ) {
								$slides[ $cnt ]['primary_button_text'] = $featured_slider_read_more_text;
								$slides[ $cnt ]['primary_button_url']  = $slides[ $cnt ]['url'];
							}

							$cnt++;
						}
					}
				}

				if ( ! empty( $slides ) ) {
					$input = $slides;
				}

			break;

			default:
			break;
		}
		return $input;

	}
endif;

add_filter( 'corporate_key_filter_slider_details', 'corporate_key_get_slider_details' );

if ( ! function_exists( 'corporate_key_render_featured_slider' ) ) :

	/**
	 * Render featured slider.
	 *
	 * @since 1.0.0
	 *
	 * @param array $slider_details Details of slider content.
	 */
	function corporate_key_render_featured_slider( $slider_details = array() ) {

		if ( empty( $slider_details ) ) {
			return;
		}

		$featured_slider_transition_effect   = corporate_key_get_option( 'featured_slider_transition_effect' );
		$featured_slider_enable_caption      = corporate_key_get_option( 'featured_slider_enable_caption' );
		$featured_slider_enable_arrow        = corporate_key_get_option( 'featured_slider_enable_arrow' );
		$featured_slider_enable_pager        = corporate_key_get_option( 'featured_slider_enable_pager' );
		$featured_slider_enable_autoplay     = corporate_key_get_option( 'featured_slider_enable_autoplay' );
		$featured_slider_transition_duration = corporate_key_get_option( 'featured_slider_transition_duration' );
		$featured_slider_transition_delay    = corporate_key_get_option( 'featured_slider_transition_delay' );

		// Cycle data.
		$slide_data = array(
			'fx'             => esc_attr( $featured_slider_transition_effect ),
			'speed'          => absint( $featured_slider_transition_duration ) * 1000,
			'pause-on-hover' => 'true',
			'loader'         => 'true',
			'log'            => 'false',
			'swipe'          => 'true',
			'auto-height'    => 'container',
			'slides'         => 'article',
		);

		if ( $featured_slider_enable_pager ) {
			$slide_data['pager-template'] = '<span class="pager-box"></span>';
		}

		if ( $featured_slider_enable_autoplay ) {
			$slide_data['timeout'] = absint( $featured_slider_transition_delay ) * 1000;
		} else {
			$slide_data['timeout'] = 0;
		}

		$slide_attributes_text = '';

		foreach ( $slide_data as $key => $item ) {
			$slide_attributes_text .= ' ';
			$slide_attributes_text .= ' data-cycle-' . esc_attr( $key );
			$slide_attributes_text .= '="' . esc_attr( $item ) . '"';
		}
	?>
	<div id="featured-slider">

		<div class="cycle-slideshow" id="main-slider" <?php echo $slide_attributes_text; ?>>

			<?php if ( $featured_slider_enable_arrow ) : ?>
				<div class="cycle-prev"><i aria-hidden="true" class="fa fa-angle-left"></i></div>
				<div class="cycle-next"><i aria-hidden="true" class="fa fa-angle-right"></i></div>
			<?php endif; ?>

			<?php $cnt = 1; ?>

			<?php foreach ( $slider_details as $key => $slide ) : ?>

				<?php
				$url = '';

				if ( ! empty( $slide['url'] ) ) {
					$url = $slide['url'];
				}

				$class_text = ( 1 === $cnt ) ? 'first' : '';

				// Buttons markup.
				$buttons_markup = '';
				$primary_button_text   = ! empty( $slide['primary_button_text'] ) ? $slide['primary_button_text'] : '';
				$primary_button_url    = ! empty( $slide['primary_button_url'] ) ? $slide['primary_button_url'] : '';
				$secondary_button_text = ! empty( $slide['secondary_button_text'] ) ? $slide['secondary_button_text'] : '';
				$secondary_button_url  = ! empty( $slide['secondary_button_url'] ) ? $slide['secondary_button_url'] : '';

				if ( ! empty( $primary_button_text ) || ! empty( $secondary_button_text ) ) {
					$buttons_markup .= '<div class="slider-buttons">';

					if ( ! empty( $primary_button_text ) ) {
						$buttons_markup .= '<a href="' . esc_url( $primary_button_url ) . '" class="custom-button slider-button button-primary">' . esc_html( $primary_button_text ) . '</a>';
					}

					if ( ! empty( $secondary_button_text ) ) {
						$buttons_markup .= '<a href="' . esc_url( $secondary_button_url ) . '" class="custom-button slider-button button-secondary">' . esc_html( $secondary_button_text ) . '</a>';
					}

					$buttons_markup .= '</div>';
				}
				?>
				<article class="<?php echo esc_attr( $class_text ); ?>"
					data-cycle-title="<?php echo esc_attr( $slide['title'] ); ?>"
					data-cycle-url="<?php echo esc_url( $url ); ?>"
					data-cycle-excerpt="<?php echo esc_attr( $slide['excerpt'] ); ?>"
					data-cycle-buttons="<?php echo esc_attr( $buttons_markup ); ?>" >
						<?php if ( ! empty( $slide['url'] ) ) : ?>
						<a href="<?php echo esc_url( $slide['url'] ); ?>">
						<?php endif; ?>
						<img src="<?php echo esc_url( $slide['images'][0] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" />
						<?php if ( ! empty( $slide['url'] ) ) : ?>
						</a>
						<?php endif; ?>

						<?php if ( $featured_slider_enable_caption ) : ?>
							<div class="cycle-caption">
								<div class="caption-wrap">
									<h3><a href="<?php echo esc_url( $slide['url'] ); ?>"><?php echo esc_attr( $slide['title'] ); ?></a></h3>
									<p><?php echo esc_attr( $slide['excerpt'] ); ?></p>
									<?php echo wp_kses_post( $buttons_markup ); ?>
								</div><!-- .cycle-wrap -->
							</div><!-- .cycle-caption -->
						<?php endif; ?>

				</article>

				<?php $cnt++; ?>

			<?php endforeach; ?>

			<?php if ( $featured_slider_enable_pager ) : ?>
				<div class="cycle-pager"></div>
			<?php endif ?>

		</div> <!-- #main-slider -->

	</div><!-- #featured-slider -->

	<?php

	}

endif;
