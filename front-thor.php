<?php
/**
 * Plugin Name: Front Thor
 * Plugin URI:  https://github.com/wearerequired/frontthor/
 * Description: Redirect your front end to a custom destination.
 * Version:     1.0.0
 * Author:      required
 * Author URI:  https://required.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: frontthor
 *
 * @package FrontThor
 */

namespace FrontThor;

/**
 * Registers option for the redirect URL.
 *
 * @since 1.0.0
 */
function init_setting() {
	register_setting(
		'reading',
		'frontthor_url',
		[
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
			'show_in_rest'      => [
				'schema' => [
					'format' => 'uri',
				],
			],
			'type'              => 'string',
			'description'       => __( 'Redirect URL for front end.' ),
		]
	);
}
add_action( 'init', __NAMESPACE__ . '\init_setting' );

/**
 * Registers settings field for the redirect URL.
 *
 * @since 1.0.0
 */
function init_settings_field() {
	add_settings_field(
		'frontthor_url',
		__( 'Redirect URL for front end' ),
		function() {
			echo '<input name="frontthor_url" type="text" id="frontthor_url"
				value="' . esc_attr( get_option( 'frontthor_url' ) ) . '" class="regular-text code" />';
		},
		'reading',
		'default',
		[
			'label_for' => 'frontthor_url',
		]
	);
}
add_action( 'admin_init', __NAMESPACE__ . '\init_settings_field' );

/**
 * Redirects frontend to a custom URL, if set.
 *
 * @since 1.0.0
 */
function maybe_redirect() {
	$url = get_option( 'frontthor_url' );
	if ( $url ) {
		wp_redirect( $url );
		exit;
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\maybe_redirect' );
