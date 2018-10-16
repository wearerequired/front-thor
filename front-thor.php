<?php
/**
 * Plugin Name: Front Thor
 * Plugin URI:  https://github.com/wearerequired/front-thor/
 * Description: Redirect your front end to a custom destination.
 * Version:     1.0.3
 * Author:      required
 * Author URI:  https://required.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: front-thor
 *
 * Copyright (c) 2017 required (email: info@required.ch)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package FrontThor
 */

namespace Required\FrontThor;

/**
 * Download language packs from Traduttore.
 *
 * @since 1.0.3
 */
function init_traduttore() {
	\Required\Traduttore_Registry\add_project(
		'plugin',
		'front-thor',
		'https://translate.required.com/api/translations/required/front-thor/'
	);
}
add_action( 'init', __NAMESPACE__ . '\init_traduttore' );

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
			'description'       => __( 'Redirect URL for front end.', 'front-thor' ),
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
		__( 'Redirect URL for front end', 'front-thor' ),
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
