<?php
/**
 * Functions hooked to custom hook.
 *
 * @package Corporate_Key
 */

if ( ! function_exists( 'corporate_key_skip_to_content' ) ) :

	/**
	 * Add skip to content.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_skip_to_content() {
		?><a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'corporate-key' ); ?></a><?php
	}
endif;

add_action( 'corporate_key_action_before', 'corporate_key_skip_to_content', 15 );

if ( ! function_exists( 'corporate_key_site_branding' ) ) :

	/**
	 * Site branding.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_site_branding() {
		?>
		<div class="site-branding">

			<?php corporate_key_the_custom_logo(); ?>

			<?php $show_title = corporate_key_get_option( 'show_title' ); ?>
			<?php $show_tagline = corporate_key_get_option( 'show_tagline' ); ?>

			<?php if ( true === $show_title || true === $show_tagline ) : ?>
				<div id="site-identity">
					<?php if ( true === $show_title ) : ?>
						<?php if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( true === $show_tagline ) : ?>
						<p class="site-description"><?php bloginfo( 'description' ); ?></p>
					<?php endif; ?>
				</div><!-- #site-identity -->
			<?php endif; ?>

		</div><!-- .site-branding -->
		<div class="right-head">
			<?php
			$show_search_in_header = corporate_key_get_option( 'show_search_in_header' );
			if ( true === $show_search_in_header ) : ?>
				<div class="header-search-box">
					<a href="#" class="search-icon"><i class="fa fa-search"></i></a>
					<div class="search-box-wrap">
						<?php get_search_form(); ?>
					</div>
				</div> <!-- .header-search-box -->
			<?php endif; ?>
		</div><!-- .right-head -->
		<div id="main-nav" class="clear-fix">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div class="wrap-menu-content">
					<?php
					wp_nav_menu(
						array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'corporate_key_primary_navigation_fallback',
						)
					);
					?>
				</div><!-- .wrap-menu-content -->
			</nav><!-- #site-navigation -->
		</div><!-- #main-nav -->
	<?php
	}

endif;

add_action( 'corporate_key_action_header', 'corporate_key_site_branding' );

if ( ! function_exists( 'corporate_key_mobile_navigation' ) ) :

	/**
	 * Mobile navigation.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_mobile_navigation() {
		?>
		<a id="mobile-trigger" href="#mob-menu"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
		<div id="mob-menu">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => '',
				'fallback_cb'    => 'corporate_key_primary_navigation_fallback',
			) );
			?>
		</div>
		<?php
	}

endif;

add_action( 'corporate_key_action_before', 'corporate_key_mobile_navigation', 20 );

if ( ! function_exists( 'corporate_key_footer_copyright' ) ) :

	/**
	 * Footer copyright.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_footer_copyright() {

		// Check if footer is disabled.
		$footer_status = apply_filters( 'corporate_key_filter_footer_status', true );
		if ( true !== $footer_status ) {
			return;
		}

		// Copyright content.
		$copyright_text = corporate_key_get_option( 'copyright_text' );
		$copyright_text = apply_filters( 'corporate_key_filter_copyright_text', $copyright_text );
		?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<?php
			$footer_menu_content = wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => 'div',
				'container_id'   => 'footer-navigation',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
			?>
		<?php endif; ?>
		<?php if ( ! empty( $copyright_text ) ) : ?>
			<div id="copyright" class="copyright">
				<?php echo wp_kses_post( $copyright_text ); ?>
			</div>
		<?php endif; ?>
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'corporate-key' ) ); ?>"><?php printf( esc_html__( 'Powered by %s', 'corporate-key' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( '%1$s by %2$s', 'corporate-key' ), 'Corporate Key', '<a target="_blank" rel="nofollow" href="https://axlethemes.com">Axle Themes</a>' ); ?>
		</div>
		<?php
	}

endif;

add_action( 'corporate_key_action_footer', 'corporate_key_footer_copyright', 10 );

if ( ! function_exists( 'corporate_key_add_sidebar' ) ) :

	/**
	 * Add sidebar.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_sidebar() {

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

		// Include primary sidebar.
		if ( 'no-sidebar' !== $global_layout ) {
			get_sidebar();
		}

		// Include secondary sidebar.
		switch ( $global_layout ) {
			case 'three-columns':
				get_sidebar( 'secondary' );
				break;

			default:
				break;
		}

	}

endif;

add_action( 'corporate_key_action_sidebar', 'corporate_key_add_sidebar' );

if ( ! function_exists( 'corporate_key_custom_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_custom_posts_navigation() {

		$pagination_type = corporate_key_get_option( 'pagination_type' );

		switch ( $pagination_type ) {
			case 'default':
				the_posts_navigation();
			break;

			case 'numeric':
				the_posts_pagination();
			break;

			default:
			break;
		}

	}
endif;

add_action( 'corporate_key_action_posts_navigation', 'corporate_key_custom_posts_navigation' );

if ( ! function_exists( 'corporate_key_add_image_in_single_display' ) ) :

	/**
	 * Add image in single template.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_image_in_single_display() {

		if ( has_post_thumbnail() ) {
			$args = array(
				'class' => 'corporate-key-post-thumb aligncenter',
			);
			the_post_thumbnail( 'large', $args );
		}

	}

endif;

add_action( 'corporate_key_single_image', 'corporate_key_add_image_in_single_display' );

if ( ! function_exists( 'corporate_key_add_custom_header' ) ) :

	/**
	 * Add custom header.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_custom_header() {

		if ( ( is_front_page() && ! is_home() ) || is_page_template( 'templates/front.php' ) || is_page_template('elementor_header_footer') ) {
			return;
		}
		?>
		<div id="custom-header" style="background-image: url('<?php header_image(); ?>');">
			<div class="container">
				<div class="custom-header-content">
					<?php do_action( 'corporate_key_action_custom_header_title' ); ?>
				</div><!-- .custom-header-content -->
				<?php do_action( 'corporate_key_action_breadcrumb' ); ?>
			</div><!-- .container -->
		</div><!-- #custom-header -->
		<?php
	}

endif;

add_action( 'corporate_key_action_before_content', 'corporate_key_add_custom_header', 6 );

if ( ! function_exists( 'corporate_key_add_title_in_custom_header' ) ) :

	/**
	 * Add title in custom header.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_title_in_custom_header() {

		echo '<h1 class="custom-header-title">';

		if ( is_home() ) {
			echo esc_html( corporate_key_get_option( 'blog_page_title' ) );
		} elseif ( is_singular() ) {
			echo single_post_title( '', false );
		} elseif ( is_archive() ) {
			the_archive_title();
		} elseif ( is_search() ) {
			printf( esc_html__( 'Search Results for: %s', 'corporate-key' ),  get_search_query() );
		} elseif ( is_404() ) {
			esc_html_e( '404 Error', 'corporate-key' );
		}

		echo '</h1>';

	}

endif;

add_action( 'corporate_key_action_custom_header_title', 'corporate_key_add_title_in_custom_header' );

if ( ! function_exists( 'corporate_key_add_breadcrumb' ) ) :

	/**
	 * Add breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_breadcrumb() {

		// Bail if home page.
		if ( is_front_page() || is_home() ) {
			return;
		}

		echo '<div id="breadcrumb">';
		corporate_key_breadcrumb();
		echo '</div><!-- #breadcrumb -->';
		return;

	}

endif;

add_action( 'corporate_key_action_breadcrumb', 'corporate_key_add_breadcrumb' );

if ( ! function_exists( 'corporate_key_footer_goto_top' ) ) :

	/**
	 * Go to top.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_footer_goto_top() {
		echo '<a href="#page" class="scrollup" id="btn-scrollup"><i class="fa fa-angle-up"></i></a>';
	}

endif;

add_action( 'corporate_key_action_after', 'corporate_key_footer_goto_top', 20 );

if ( ! function_exists( 'corporate_key_add_front_page_widget_area' ) ) :

	/**
	 * Add front page widget area.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_front_page_widget_area() {

		if ( is_page_template( 'templates/front.php' ) ) {
			if ( is_active_sidebar( 'sidebar-front-page-widget-area' ) ) {
				echo '<div id="sidebar-front-page-widget-area" class="widget-area">';
				dynamic_sidebar( 'sidebar-front-page-widget-area' );
				echo '</div><!-- #sidebar-front-page-widget-area -->';
			}
			else {
				if ( current_user_can( 'edit_theme_options' ) ) {
					echo '<div id="sidebar-front-page-widget-area" class="widget-area">';
					corporate_key_message_front_page_widget_area();
					echo '</div><!-- #sidebar-front-page-widget-area -->';
				}
			}
		}

	}
endif;

add_action( 'corporate_key_action_before_content', 'corporate_key_add_front_page_widget_area', 7 );

if ( ! function_exists( 'corporate_key_add_footer_widgets' ) ) :

	/**
	 * Add footer widgets.
	 *
	 * @since 1.0.0
	 */
	function corporate_key_add_footer_widgets() {

		get_template_part( 'template-parts/footer-widgets' );

	}
endif;

add_action( 'corporate_key_action_before_footer', 'corporate_key_add_footer_widgets', 5 );
