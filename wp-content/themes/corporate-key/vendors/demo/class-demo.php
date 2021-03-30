<?php
/**
 * Demo class
 *
 * @package Corporate_Key
 */

if ( ! class_exists( 'Corporate_Key_Demo' ) ) {

	/**
	 * Main class.
	 *
	 * @since 1.0.0
	 *
	 * @version 2.0.2
	 */
	class Corporate_Key_Demo {

		/**
		 * Singleton instance of Corporate_Key_Demo.
		 *
		 * @var Corporate_Key_Demo $instance Corporate_Key_Demo instance.
		 */
		private static $instance;

		/**
		 * Configuration.
		 *
		 * @var array $config Configuration.
		 */
		private $config;

		/**
		 * Main Corporate_Key_Demo instance.
		 *
		 * @since 1.0.0
		 *
		 * @param array $config Configuration array.
		 */
		public static function init( $config ) {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Corporate_Key_Demo ) ) {
				self::$instance = new Corporate_Key_Demo();
				if ( ! empty( $config ) && is_array( $config ) ) {
					self::$instance->config = $config;
					self::$instance->setup_actions();
				}
			}
		}

		/**
		 * Setup actions.
		 *
		 * @since 1.0.0
		 */
		public function setup_actions() {

			// Disable branding.
			add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

			// OCDI import files.
			add_filter( 'pt-ocdi/import_files', array( $this, 'ocdi_files' ), 99 );

			// OCDI after import.
			add_action( 'pt-ocdi/after_import', array( $this, 'ocdi_after_import' ) );

			// OCDI additional intro text.
			add_filter( 'pt-ocdi/plugin_intro_text', array( $this, 'additional_intro_text' ) );
		}

		/**
		 * OCDI files.
		 *
		 * @since 1.0.0
		 */
		public function ocdi_files() {

			$ocdi = isset( $this->config['ocdi'] ) ? $this->config['ocdi'] : array();
			return $ocdi;
		}

		/**
		 * Intro message.
		 *
		 * @since 1.0.0
		 *
		 * @param string $intro Intro.
		 * @return string Modified intro.
		 */
		public function additional_intro_text( $intro ) {

			$intro_content = isset( $this->config['intro_content'] ) ? $this->config['intro_content'] : '';

			if ( ! empty( $intro_content ) ) {
				$message = '<div class="ocdi__intro-text">';
				$message .= wp_kses_post( wpautop( $intro_content ) );
				$message .= '</div><!-- .ocdi__intro-text -->';
				$intro .= $message;
			}

			return $intro;
		}

		/**
		 * OCDI after import.
		 *
		 * @since 1.0.0
		 */
		public function ocdi_after_import() {

			// Set static front page.
			$static_page = isset( $this->config['static_page'] ) ? $this->config['static_page'] : '';
			$posts_page  = isset( $this->config['posts_page'] ) ? $this->config['posts_page'] : '';
			update_option( 'show_on_front', 'page' );

			$pages = array(
				'page_on_front'  => $static_page,
				'page_for_posts' => $posts_page,
			);

			foreach ( $pages as $option_key => $slug ) {
				$result = get_page_by_path( $slug );
				if ( $result ) {
					if ( is_array( $result ) ) {
						$object = array_shift( $result );
					} else {
						$object = $result;
					}

					update_option( $option_key, $object->ID );
				}
			}

			// Set menu locations.
			$menu_details = isset( $this->config['menu_locations'] ) ? $this->config['menu_locations'] : array();
			if ( ! empty( $menu_details ) ) {
				$nav_settings  = array();
				$current_menus = wp_get_nav_menus();

				if ( ! empty( $current_menus ) && ! is_wp_error( $current_menus ) ) {
					foreach ( $current_menus as $menu ) {
						foreach ( $menu_details as $location => $menu_slug ) {
							if ( $menu->slug === $menu_slug ) {
								$nav_settings[ $location ] = $menu->term_id;
							}
						}
					}
				}

				set_theme_mod( 'nav_menu_locations', $nav_settings );
			}
		}
	}

} // End if().
