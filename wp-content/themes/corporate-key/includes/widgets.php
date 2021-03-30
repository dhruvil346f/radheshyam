<?php
/**
 * Custom Theme widgets.
 *
 * @package Corporate_Key
 */

if ( ! function_exists( 'corporate_key_register_widgets' ) ) :

	/**
	 * Register widgets.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_register_widgets() {

		// Social widget.
		register_widget( 'Corporate_Key_Social_Widget' );

		// Featured Page widget.
		register_widget( 'Corporate_Key_Featured_Page_Widget' );

		// Call To Action widget.
		register_widget( 'Corporate_Key_Call_To_Action_Widget' );

		// Latest News widget.
		register_widget( 'Corporate_Key_Latest_News_Widget' );

		// Services widget.
		register_widget( 'Corporate_Key_Services_Widget' );

	}

endif;

add_action( 'widgets_init', 'corporate_key_register_widgets' );

if ( ! class_exists( 'Corporate_Key_Social_Widget' ) ) :

	/**
	 * Social widget Class.
	 *
	 * @since 1.0.0
	 */
	class Corporate_Key_Social_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'corporate_key_widget_social',
				'description'                 => esc_html__( 'Social Icons Widget', 'corporate-key' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'corporate-key-social', esc_html__( 'CK: Social', 'corporate-key' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			echo $args['before_widget'];

			// Render widget title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			if ( has_nav_menu( 'social' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'social',
					'container'      => false,
					'depth'          => 1,
					'link_before'    => '<span class="screen-reader-text">',
					'link_after'     => '</span>',
				) );
			}

			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title'] = sanitize_text_field( $new_instance['title'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title' => '',
			) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<?php if ( true !== has_nav_menu( 'social' ) ) : ?>
				<p><?php echo esc_html__( 'Social menu is not set. Please create menu and assign it to Social Menu.', 'corporate-key' ); ?></p>
			<?php endif; ?>
			<?php
		}
	}

endif;

if ( ! class_exists( 'Corporate_Key_Featured_Page_Widget' ) ) :

	/**
	 * Featured page widget class.
	 *
	 * @since 1.0.0
	 */
	class Corporate_Key_Featured_Page_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'corporate_key_widget_featured_page',
				'description'                 => esc_html__( 'Displays single featured Page', 'corporate-key' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'corporate-key-featured-page', esc_html__( 'CK: Featured Page', 'corporate-key' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title                    = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$featured_page            = ! empty( $instance['featured_page'] ) ? $instance['featured_page'] : 0;
			$content_type             = ! empty( $instance['content_type'] ) ? $instance['content_type'] : 'full';
			$excerpt_length           = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 80;
			$featured_image           = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'medium';
			$featured_image_alignment = ! empty( $instance['featured_image_alignment'] ) ? $instance['featured_image_alignment'] : 'left';

			echo $args['before_widget'];

			if ( absint( $featured_page ) > 0 ) {

				$qargs = array(
					'p'                   => absint( $featured_page ),
					'post_type'           => 'page',
					'no_found_rows'       => true,
					'ignore_sticky_posts' => true,
					);

				$the_query = new WP_Query( $qargs );

				if ( $the_query->have_posts() ) {

					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						// Display featured image.
						if ( 'disable' !== $featured_image && has_post_thumbnail() ) {
							the_post_thumbnail( esc_attr( $featured_image ), array( 'class' => 'align' . esc_attr( $featured_image_alignment ) ) );
						}

						echo '<div class="featured-page-widget">';

						// Render widget title.
						if ( ! empty( $title ) ) {
							echo $args['before_title'] . $title . $args['after_title'];
						}

						if ( 'short' === $content_type ) {
							if ( absint( $excerpt_length ) > 0 ) {
								$excerpt = corporate_key_get_the_excerpt( absint( $excerpt_length ) );
								echo wp_kses_post( wpautop( $excerpt ) );
								echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html__( 'Read more', 'corporate-key' ) . '</a>';
							}
						} else {
							the_content();
						}

						echo '</div><!-- .featured-page-widget -->';

					} // End while.

					// Reset.
					wp_reset_postdata();

				} // End if.
			}

			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			$instance['title']                    = sanitize_text_field( $new_instance['title'] );
			$instance['featured_page']            = absint( $new_instance['featured_page'] );
			$instance['content_type']             = sanitize_key( $new_instance['content_type'] );
			$instance['excerpt_length']           = absint( $new_instance['excerpt_length'] );
			$instance['featured_image']           = sanitize_text_field( $new_instance['featured_image'] );
			$instance['featured_image_alignment'] = sanitize_key( $new_instance['featured_image_alignment'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title'                    => '',
				'featured_page'            => '',
				'content_type'             => 'full',
				'excerpt_length'           => 80,
				'featured_image'           => 'medium',
				'featured_image_alignment' => 'left',
			) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'featured_page' ) ); ?>"><?php esc_html_e( 'Select Page:', 'corporate-key' ); ?></label>
				<?php
				wp_dropdown_pages( array(
					'id'               => $this->get_field_id( 'featured_page' ),
					'name'             => $this->get_field_name( 'featured_page' ),
					'selected'         => $instance['featured_page'],
					'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'corporate-key' ),
					)
				);
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'content_type' ) ); ?>"><?php esc_html_e( 'Show Content:', 'corporate-key' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'content_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content_type' ) ); ?>">
					<option value="short"<?php selected( $instance['content_type'], 'short' ) ?>><?php esc_html_e( 'Short', 'corporate-key' ) ?></option>
					<option value="full"<?php selected( $instance['content_type'], 'full' ) ?>><?php esc_html_e( 'Full', 'corporate-key' ) ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length:', 'corporate-key' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" min="1" max="200" />&nbsp;<small><?php esc_html_e( 'in words', 'corporate-key' ); ?></small>
				<br/><span><small><?php esc_html_e( 'Applies when Short is selected in Show Content.', 'corporate-key') ?></small></span>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'featured_image' ) ); ?>"><?php esc_html_e( 'Featured Image:', 'corporate-key' ); ?></label>
				<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'featured_image' ),
					'name'     => $this->get_field_name( 'featured_image' ),
					'selected' => $instance['featured_image'],
					);
				corporate_key_render_select_dropdown( $dropdown_args, 'corporate_key_get_image_sizes_options' );
				?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'featured_image_alignment' ); ?>"><?php esc_html_e( 'Image Alignment:', 'corporate-key' ); ?></label>
				<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'featured_image_alignment' ),
					'name'     => $this->get_field_name( 'featured_image_alignment' ),
					'selected' => $instance['featured_image_alignment'],
					);
				corporate_key_render_select_dropdown( $dropdown_args, 'corporate_key_get_image_alignment_options' );
				?>
			</p>
			<?php
		}
	}

endif;

if ( ! class_exists( 'Corporate_Key_Call_To_Action_Widget' ) ) :

	/**
	 * Call To Action widget Class.
	 *
	 * @since 1.0.0
	 */
	class Corporate_Key_Call_To_Action_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'corporate_key_widget_call_to_action',
				'description'                 => esc_html__( 'Call To Action Widget', 'corporate-key' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'corporate-key-call-to-action', esc_html__( 'CK: Call To Action', 'corporate-key' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title                 = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$text                  = ! empty( $instance['text'] ) ? $instance['text'] : '';
			$primary_button_text   = ! empty( $instance['primary_button_text'] ) ? esc_html( $instance['primary_button_text'] ) : '';
			$primary_button_url    = ! empty( $instance['primary_button_url'] ) ? esc_url( $instance['primary_button_url'] ) : '';
			$secondary_button_text = ! empty( $instance['secondary_button_text'] ) ? esc_html( $instance['secondary_button_text'] ) : '';
			$secondary_button_url  = ! empty( $instance['secondary_button_url'] ) ? esc_url( $instance['secondary_button_url'] ) : '';
			$background_image      = ! empty( $instance['background_image'] ) ? esc_url( $instance['background_image'] ) : '';

			// Add background image.
			if ( ! empty( $background_image ) ) {
				$background_style = '';
				$background_style .= ' style="background-image:url(' . esc_url( $background_image ) . ');" ';
				$args['before_widget'] = implode( $background_style . ' class="', explode( 'class="', $args['before_widget'], 2 ) );
			}

			echo $args['before_widget'];
			echo '<div class="cta-content">';

			// Render widget title.
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>

			<?php if ( ! empty( $text ) ) : ?>
				<?php echo wp_kses_post( wpautop( $text ) ); ?>
			<?php endif; ?>
			<?php echo '</div>'; ?>
			<?php if ( ( ! empty( $primary_button_text ) && ! empty( $primary_button_url ) ) || ( ! empty( $secondary_button_text ) && ! empty( $secondary_button_url ) ) ) : ?>
				<div class="call-to-action-buttons">
					<?php if ( ! empty( $primary_button_url ) && ! empty( $primary_button_text ) ) : ?>
						<a href="<?php echo esc_url( $primary_button_url ); ?>" class="button cta-button cta-button-primary"><?php echo esc_attr( $primary_button_text ); ?></a>
					<?php endif; ?>
					<?php if ( ! empty( $secondary_button_url ) && ! empty( $secondary_button_text ) ) : ?>
						<a href="<?php echo esc_url( $secondary_button_url ); ?>" class="button cta-button cta-button-secondary"><?php echo esc_attr( $secondary_button_text ); ?></a>
					<?php endif; ?>
				</div><!-- .call-to-action-buttons -->
			<?php endif; ?>
			<?php
			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user via
		 *                            {@see WP_Widget::form()}.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']                 = sanitize_text_field( $new_instance['title'] );
			$instance['text']                  = sanitize_text_field( $new_instance['text'] );
			$instance['primary_button_text']   = sanitize_text_field( $new_instance['primary_button_text'] );
			$instance['primary_button_url']    = esc_url_raw( $new_instance['primary_button_url'] );
			$instance['secondary_button_text'] = sanitize_text_field( $new_instance['secondary_button_text'] );
			$instance['secondary_button_url']  = esc_url_raw( $new_instance['secondary_button_url'] );
			$instance['background_image']      = esc_url_raw( $new_instance['background_image'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title'                 => '',
				'text'                  => '',
				'primary_button_text'   => esc_html__( 'Read more', 'corporate-key' ),
				'primary_button_url'    => home_url( '/' ),
				'secondary_button_text' => esc_html__( 'Learn more', 'corporate-key' ),
				'secondary_button_url'  => home_url( '/' ),
				'background_image'      => '',
			) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'primary_button_text' ) ); ?>"><?php esc_html_e( 'Primary Button Text:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'primary_button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'primary_button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['primary_button_text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'primary_button_url' ) ); ?>"><?php esc_html_e( 'Primary Button URL:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'primary_button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'primary_button_url' ) ); ?>" type="text" value="<?php echo esc_url( $instance['primary_button_url'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'secondary_button_text' ) ); ?>"><?php esc_html_e( 'Secondary Button Text:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'secondary_button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'secondary_button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['secondary_button_text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'secondary_button_url' ) ); ?>"><?php esc_html_e( 'Secondary Button URL:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'secondary_button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'secondary_button_url' ) ); ?>" type="text" value="<?php echo esc_url( $instance['secondary_button_url'] ); ?>" />
			</p>
			<div>
				<label for="<?php echo esc_attr( $this->get_field_id( 'background_image' ) ); ?>"><?php esc_html_e( 'Background Image:', 'corporate-key' ); ?></label>
				<?php
				$background_image = $instance['background_image'];
				$image_status = false;

				if ( ! empty( $background_image ) ) {
					$image_status = true;
				}

				$remove_button_style = 'display:none;';

				if ( true === $image_status ) {
					$remove_button_style = 'display:inline-block;';
				}
				?>
				<input type="hidden" class="img widefat" name="<?php echo esc_attr( $this->get_field_name( 'background_image' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'background_image' ) ); ?>" value="<?php echo esc_url( $background_image ); ?>" />
				<input type="button" class="select-img button button-primary" value="<?php esc_html_e( 'Upload', 'corporate-key' ); ?>" data-uploader_title="<?php esc_attr_e( 'Select Image', 'corporate-key' ); ?>" data-uploader_button_text="<?php esc_attr_e( 'Choose Image', 'corporate-key' ); ?>" />
				<input type="button" value="<?php echo esc_attr_x( 'X', 'remove button', 'corporate-key' ); ?>" class="button button-secondary btn-image-remove" style="<?php echo esc_attr( $remove_button_style ); ?>" />
				<div class="image-preview-wrap">
					<?php if ( ! empty( $background_image ) ) : ?>
						<img src="<?php echo esc_url( $background_image ); ?>" alt="" />
					<?php endif; ?>
				</div><!-- .image-preview-wrap -->
			</div>
			<?php
		}
	}

endif;

if ( ! class_exists( 'Corporate_Key_Latest_News_Widget' ) ) :

	/**
	 * Latest news widget class.
	 *
	 * @since 1.0.0
	 */
	class Corporate_Key_Latest_News_Widget extends WP_Widget {

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			$opts = array(
				'classname'                   => 'corporate_key_widget_latest_news',
				'description'                 => esc_html__( 'Latest News Widget. Displays latest posts in grid.', 'corporate-key' ),
				'customize_selective_refresh' => true,
				);

			parent::__construct( 'corporate-key-latest-news', esc_html__( 'CK: Latest News', 'corporate-key' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title          = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$subtitle       = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
			$post_category  = ! empty( $instance['post_category'] ) ? $instance['post_category'] : 0;
			$post_column    = ! empty( $instance['post_column'] ) ? $instance['post_column'] : 3;
			$featured_image = ! empty( $instance['featured_image'] ) ? $instance['featured_image'] : 'corporate-key-thumb';
			$post_number    = ! empty( $instance['post_number'] ) ? $instance['post_number'] : 3;
			$excerpt_length = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 0;
			$more_text      = ! empty( $instance['more_text'] ) ? $instance['more_text'] : '';
			$view_more_link = ! empty( $instance['view_more_link'] ) ? $instance['view_more_link'] : '';
			$view_more_text = ! empty( $instance['view_more_text'] ) ? $instance['view_more_text'] : '';

			echo $args['before_widget'];

			// Display widget title.
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// Display widget subtitle.
			if ( $subtitle ) {
				echo '<h3 class="subtitle">' . esc_html( $subtitle ) . '</h3>';
			}

			$qargs = array(
				'posts_per_page'      => esc_attr( $post_number ),
				'no_found_rows'       => true,
				'ignore_sticky_posts' => true,
			);

			if ( absint( $post_category ) > 0 ) {
				$qargs['cat'] = absint( $post_category );
			}

			$the_query = new WP_Query( $qargs );
			?>
			<?php if ( $the_query->have_posts() ) : ?>

				<div class="latest-news-widget latest-news-col-<?php echo esc_attr( $post_column ); ?>">

					<div class="inner-wrapper">

						<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

							<div class="latest-news-item">
								<div class="latest-news-wrapper">
									<?php if ( 'disable' !== $featured_image && has_post_thumbnail() ) : ?>
										<div class="latest-news-thumb">
											<a href="<?php the_permalink(); ?>">
												<?php
												$img_attributes = array( 'class' => 'aligncenter' );
												the_post_thumbnail( esc_attr( $featured_image ), $img_attributes );
												?>
											</a>
											<div class="latest-news-meta">
												<span class="posted-on">
													<span class="metadata-time-day"><?php the_time( esc_html_x( 'd', 'date format day', 'corporate-key' ) ); ?></span>
													<span class="metadata-time-mon"><?php the_time( esc_html_x( 'M', 'date format month', 'corporate-key' ) ); ?></span>
												</span>
												</div><!-- .latest-news-meta -->
										</div><!-- .latest-news-thumb -->
									<?php endif; ?>
									<div class="latest-news-text-wrap">
										<h3 class="latest-news-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>

										<?php if ( absint( $excerpt_length ) > 0 ) : ?>
											<div class="latest-news-summary">
												<?php
												$excerpt = corporate_key_get_the_excerpt( absint( $excerpt_length ) );
												echo wp_kses_post( wpautop( $excerpt ) );
												?>
											</div><!-- .latest-news-summary -->
										<?php endif; ?>
										<?php if ( ! empty( $more_text ) ) : ?>
											<div class="latest-news-read-more">
												<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html( $more_text ); ?></a>
											</div>
										<?php endif; ?>
									</div><!-- .latest-news-text-wrap -->
								</div><!-- .latest-news-wrapper -->
							</div><!-- .latest-news-item -->

						<?php endwhile; ?>

					</div><!-- .inner-wrapper -->
					<?php if ( ! empty( $view_more_link ) && ! empty( $view_more_text ) ) : ?>
						<a href="<?php echo esc_url( $view_more_link ); ?>" class="button"><?php echo esc_html( $view_more_text ); ?></a>
					<?php endif; ?>
				</div><!-- .latest-news-widget -->

				<?php wp_reset_postdata(); ?>

			<?php endif; ?>
			<?php
			echo $args['after_widget'];

		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['subtitle']       = sanitize_text_field( $new_instance['subtitle'] );
			$instance['post_category']  = absint( $new_instance['post_category'] );
			$instance['post_number']    = absint( $new_instance['post_number'] );
			$instance['post_column']    = absint( $new_instance['post_column'] );
			$instance['excerpt_length'] = absint( $new_instance['excerpt_length'] );
			$instance['featured_image'] = sanitize_text_field( $new_instance['featured_image'] );
			$instance['more_text']      = sanitize_text_field( $new_instance['more_text'] );
			$instance['view_more_link'] = esc_url_raw( $new_instance['view_more_link'] );
			$instance['view_more_text'] = sanitize_text_field( $new_instance['view_more_text'] );

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$instance = wp_parse_args( (array) $instance, array(
				'title'          => '',
				'subtitle'       => '',
				'post_category'  => '',
				'post_column'    => 3,
				'featured_image' => 'corporate-key-thumb',
				'post_number'    => 3,
				'excerpt_length' => 40,
				'more_text'      => esc_html__( 'Read more', 'corporate-key' ),
				'view_more_link' => '',
				'view_more_text' => esc_html__( 'View more', 'corporate-key' ),
			) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['subtitle'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo  esc_attr( $this->get_field_id( 'post_category' ) ); ?>"><?php esc_html_e( 'Select Category:', 'corporate-key' ); ?></label>
				<?php
				$cat_args = array(
					'orderby'         => 'name',
					'hide_empty'      => true,
					'taxonomy'        => 'category',
					'name'            => $this->get_field_name( 'post_category' ),
					'id'              => $this->get_field_id( 'post_category' ),
					'selected'        => $instance['post_category'],
					'show_option_all' => esc_html__( 'All Categories','corporate-key' ),
				);
				wp_dropdown_categories( $cat_args );
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>"><?php esc_html_e( 'Number of Posts:', 'corporate-key' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'post_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_number' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['post_number'] ); ?>" min="1" max="20" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'post_column' ) ); ?>"><?php esc_html_e( 'Number of Columns:', 'corporate-key' ); ?></label>
				<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'post_column' ),
					'name'     => $this->get_field_name( 'post_column' ),
					'selected' => $instance['post_column'],
				);
				corporate_key_render_select_dropdown( $dropdown_args, 'corporate_key_get_numbers_dropdown_options', array( 'min' => 3, 'max' => 4 ) );
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'featured_image' ) ); ?>"><?php esc_html_e( 'Select Image Size:', 'corporate-key' ); ?></label>
				<?php
				$dropdown_args = array(
					'id'       => $this->get_field_id( 'featured_image' ),
					'name'     => $this->get_field_name( 'featured_image' ),
					'selected' => $instance['featured_image'],
				);
				corporate_key_render_select_dropdown( $dropdown_args, 'corporate_key_get_image_sizes_options', array( 'add_disable' => false ) );
				?>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length:', 'corporate-key' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" min="0" max="200" />&nbsp;<small><?php esc_html_e( 'in words', 'corporate-key' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"><?php esc_html_e( 'Read More Text:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['more_text'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_more_link' ) ); ?>"><?php esc_html_e( 'View More Link:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_more_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_more_link' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['view_more_link'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'view_more_text' ) ); ?>"><?php esc_html_e( 'View More Text:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['view_more_text'] ); ?>" />
			</p>
			<?php
		}

	}

endif;

if ( ! class_exists( 'Corporate_Key_Services_Widget' ) ) :

	/**
	 * Services widget class.
	 *
	 * @since 1.0.0
	 */
	class Corporate_Key_Services_Widget extends WP_Widget {

		/**
		 * Block count.
		 *
		 * @since 1.0.0
		 *
		 * @var int Block count.
		 */
		protected $block_count;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			$this->block_count = 4;
			$opts = array(
				'classname'                   => 'corporate_key_widget_services',
				'description'                 => esc_html__( 'Show your services pages with icon and read more link.', 'corporate-key' ),
				'customize_selective_refresh' => true,
				);
			parent::__construct( 'corporate-key-services', esc_html__( 'CK: Services', 'corporate-key' ), $opts );
		}

		/**
		 * Echo the widget content.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args     Display arguments including before_title, after_title,
		 *                        before_widget, and after_widget.
		 * @param array $instance The settings for the particular instance of the widget.
		 */
		function widget( $args, $instance ) {

			$title          = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$subtitle       = ! empty( $instance['subtitle'] ) ? $instance['subtitle'] : '';
			$excerpt_length = ! empty( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 0;
			$more_text      = ! empty( $instance['more_text'] ) ? $instance['more_text'] : '';

			echo $args['before_widget'];

			// Render widget title.
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}

			// Display widget subtitle.
			if ( $subtitle ) {
				echo '<h3 class="subtitle">' . esc_html( $subtitle ) . '</h3>';
			}

			$services_array = array();
			for ( $i = 1; $i <= $this->block_count; $i++ ) {
				$page = 0;
				if ( ! empty( $instance[ 'block_page_' . $i ] ) && absint( $instance[ 'block_page_' . $i ] ) > 0 ) {
					$page = absint( $instance[ 'block_page_' . $i ] );
				}
				if ( $page > 0 ) {
					$sitem = array();
					$sitem['page'] = $page;
					$sitem['icon'] = '';

					if ( ! empty( $instance[ 'block_icon_' . $i ] ) ) {
						$sitem['icon'] = $instance[ 'block_icon_' . $i ];
					}

					$services_array[] = $sitem;
				}
			}

			// Render content.
			if ( ! empty( $services_array ) ) {
				$extra_args = array(
					'excerpt_length' => $excerpt_length,
					'more_text'      => $more_text,
				);
				$this->render_widget_content( $services_array, $extra_args );
			}

			echo $args['after_widget'];

		}

		/**
		 * Render services.
		 *
		 * @since 1.0.0
		 *
		 * @param array $services Services details.
		 * @param array $args     Arguments.
		 */
		function render_widget_content( $services, $args = array() ) {

			global $post;

			$service_column = count( $services );

			$ids = wp_list_pluck( $services, 'page' );

			$qargs = array(
				'post_type'           => 'page',
				'no_found_rows'       => true,
				'post__in'            => $ids,
				'posts_per_page'      => count( $ids ),
				'orderby'             => 'post__in',
				'ignore_sticky_posts' => true,
			);

			$the_query = new WP_Query( $qargs );

			if ( ! $the_query->have_posts() ) {
				return;
			}
			?>
			<div class="service-block-list service-col-<?php echo esc_attr( $service_column ); ?>">
				<div class="inner-wrapper">

					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
						<?php
							$icon_item = wp_filter_object_list( $services, array( 'page' => get_the_ID() ), 'and', 'icon' );
							$icon = array_shift( $icon_item );
						?>

						<div class="service-block-item">
							<div class="service-block-inner">
								<?php if ( ! empty( $icon ) ) : ?>
									<a class="services-icon" href="<?php the_permalink(); ?>">
										<i class="<?php echo esc_attr( 'fa ' . $icon ); ?>"></i>
									</a>
								<?php endif; ?>
								<div class="service-block-inner-content">
									<h3 class="service-item-title">
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>
									<?php if ( absint( $args['excerpt_length'] ) > 0 ) : ?>
										<div class="service-block-item-excerpt">
											<?php
												$excerpt = corporate_key_get_the_excerpt( absint( $args['excerpt_length'] ) );
												echo wp_kses_post( wpautop( $excerpt ) );
											?>
										</div><!-- .service-block-item-excerpt -->
									<?php endif; ?>

									<?php if ( ! empty( $args['more_text'] ) ) : ?>
										<a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html( $args['more_text'] ); ?></a>
									<?php endif; ?>
								</div><!-- .service-block-inner-content -->
							</div><!-- .service-block-inner -->
						</div><!-- .service-block-item -->

						<?php wp_reset_postdata(); ?>

					<?php endwhile; ?>

				</div><!-- .inner-wrapper -->
			</div><!-- .service-block-list -->
			<?php
		}

		/**
		 * Update widget instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $new_instance New settings for this instance as input by the user.
		 * @param array $old_instance Old settings for this instance.
		 * @return array Settings to save or bool false to cancel saving.
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']          = sanitize_text_field( $new_instance['title'] );
			$instance['subtitle']       = sanitize_text_field( $new_instance['subtitle'] );
			$instance['excerpt_length'] = absint( $new_instance['excerpt_length'] );
			$instance['more_text']      = sanitize_text_field( $new_instance['more_text'] );

			for ( $i = 1; $i <= $this->block_count ; $i++ ) {
				$instance[ 'block_page_' . $i ] = absint( $new_instance[ 'block_page_' . $i ] );
				$instance[ 'block_icon_' . $i ] = sanitize_text_field( $new_instance[ 'block_icon_' . $i ] );
			}

			return $instance;
		}

		/**
		 * Output the settings update form.
		 *
		 * @since 1.0.0
		 *
		 * @param array $instance Current settings.
		 */
		function form( $instance ) {

			// Defaults.
			$widget_defaults = array(
				'title'          => '',
				'subtitle'       => '',
				'excerpt_length' => 20,
				'more_text'      => esc_html__( 'Read more', 'corporate-key' ),
			);

			for ( $i = 1; $i <= $this->block_count ; $i++ ) {
				$widget_defaults[ 'block_page_' . $i ] = '';
				$widget_defaults[ 'block_icon_' . $i ] = 'fa-cogs';
			}

			$instance = wp_parse_args( (array) $instance, $widget_defaults );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>"><?php esc_html_e( 'Subtitle:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subtitle' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subtitle' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['subtitle'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt Length:', 'corporate-key' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" min="0" max="200" />&nbsp;<small><?php esc_html_e( 'in words', 'corporate-key' ); ?></small>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>"><?php esc_html_e( 'Read More Text:', 'corporate-key' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'more_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'more_text' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['more_text'] ); ?>" />
			</p>
			<hr />
			<?php for ( $i = 1; $i <= $this->block_count ; $i++ ) { ?>
				<h4 class="block-heading"><?php printf( esc_html__( 'Block %d','corporate-key' ), $i ); ?></h4>
				<p>
					<label for="<?php echo $this->get_field_id( 'block_page_' . $i ); ?>"><?php esc_html_e( 'Page:', 'corporate-key' ); ?></label>
					<?php
					wp_dropdown_pages( array(
						'id'               => $this->get_field_id( 'block_page_' . $i ),
						'name'             => $this->get_field_name( 'block_page_' . $i ),
						'selected'         => $instance[ 'block_page_' . $i ],
						'show_option_none' => esc_html__( '&mdash; Select &mdash;', 'corporate-key' ),
						)
					);
					?>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'block_icon_' . $i ) ); ?>"><?php esc_html_e( 'Icon:', 'corporate-key' ); ?></label>
					<input id="<?php echo esc_attr( $this->get_field_id( 'block_icon_' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'block_icon_' . $i ) ); ?>" type="text" value="<?php echo esc_attr( $instance[ 'block_icon_' . $i ] ); ?>" placeholder="<?php esc_attr_e( 'eg: fa-cogs', 'corporate-key' ); ?>" />
				</p>
				<?php if ( 1 === $i ) : ?>
					<p>
						<a href="<?php echo esc_url( 'http://fontawesome.io/cheatsheet/' ); ?>"><?php esc_html_e( 'View Font Awesome Reference', 'corporate-key' ); ?></a>
					</p>
				<?php endif; ?>
			<?php } // End for loop. ?>
			<?php
		}
	}

endif;
