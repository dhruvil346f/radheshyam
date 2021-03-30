<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Corporate_Key
 */

?><?php
	/**
	 * Hook - corporate_key_action_doctype.
	 *
	 * @hooked corporate_key_doctype - 10
	 */
	do_action( 'corporate_key_action_doctype' );
?>
<head>
	<?php
	/**
	 * Hook - corporate_key_action_head.
	 *
	 * @hooked corporate_key_head - 10
	 */
	do_action( 'corporate_key_action_head' );
	?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Hook - corporate_key_action_before.
	 *
	 * @hooked corporate_key_page_start - 10
	 * @hooked corporate_key_skip_to_content - 15
	 */
	do_action( 'corporate_key_action_before' );
	?>

	<?php
	  /**
	   * Hook - corporate_key_action_before_header.
	   *
	   * @hooked corporate_key_header_start - 10
	   */
	  do_action( 'corporate_key_action_before_header' );
	?>
		<?php
		/**
		 * Hook - corporate_key_action_header.
		 *
		 * @hooked corporate_key_site_branding - 10
		 */
		do_action( 'corporate_key_action_header' );
		?>
	<?php
	  /**
	   * Hook - corporate_key_action_after_header.
	   *
	   * @hooked corporate_key_header_end - 10
	   * @hooked corporate_key_add_primary_navigation - 20
	   */
	  do_action( 'corporate_key_action_after_header' );
	?>

	<?php
	/**
	 * Hook - corporate_key_action_before_content.
	 *
	 * @hooked corporate_key_add_custom_header - 6
	 * @hooked corporate_key_content_start - 10
	 */
	do_action( 'corporate_key_action_before_content' );
	?>
	<?php
	  /**
	   * Hook - corporate_key_action_content.
	   */
	  do_action( 'corporate_key_action_content' );
