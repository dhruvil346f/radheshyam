<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Corporate_Key
 */

	/**
	 * Hook - corporate_key_action_after_content.
	 *
	 * @hooked corporate_key_content_end - 10
	 */
	do_action( 'corporate_key_action_after_content' );
?>

	<?php
	/**
	 * Hook - corporate_key_action_before_footer.
	 *
	 * @hooked corporate_key_add_footer_widgets - 5
	 * @hooked corporate_key_footer_start - 10
	 */
	do_action( 'corporate_key_action_before_footer' );
	?>
	RADHESHYAM PLASTIC.<br> Copyright © All rights reserved.
	<?php
	/**
	 * Hook - corporate_key_action_after_footer.
	 *
	 * @hooked corporate_key_footer_end - 10
	 */
	do_action( 'corporate_key_action_after_footer' );
	?>

<?php
	/**
	 * Hook - corporate_key_action_after.
	 *
	 * @hooked corporate_key_page_end - 10
	 * @hooked corporate_key_footer_goto_top - 20
	 */
	do_action( 'corporate_key_action_after' );
?>

<?php wp_footer(); ?>
</body>
</html>
