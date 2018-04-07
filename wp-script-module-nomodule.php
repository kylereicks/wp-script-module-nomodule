<?php
/**
 * Plugin Name: Script module and nomodule.
 * Plugin URI: https://github.com/kylereicks/script-module-nomodule
 * Description: Add type="module" and nomodule parameters to script tags when the values are set via wp_script_add_data.
 * Version: 1.0.0
 * Author: Kyle Reicks
 * Author URI: https://github.com/kylereicks/
 *
 * @package module-nomodule
 */

namespace WordPress\Script\ModuleNoModule;

add_filter( 'script_loader_tag', __NAMESPACE__ . '\add_module_nomodule', 10, 3 );

/**
 * Add type="module" and nomodule parameters to a script tag.
 *
 * Add type="module" and nomodule parameters to script tags when the values are set via wp_script_add_data.
 *
 * wp_script_add_data( 'script-handle', 'type', 'module' );
 * wp_script_add_data( 'script-handle', 'nomodule', true );
 *
 * @since 1.0.0
 *
 * @global \WP_Scripts $wp_scripts The global WP_Scripts object, containing registered scripts.
 *
 * @param string $tag The filtered HTML tag.
 * @param string $handle The handle for the registered script/style.
 * @param string $src The resource URL.
 * @return string The filtered HTML tag.
 */
function add_module_nomodule( $tag, $handle, $src ) {
	global $wp_scripts;

	if ( ! empty( $wp_scripts->registered[ $handle ]->extra['type'] ) ) {
		if ( preg_match( '/\stype="[^"]*"/', $tag, $match ) ) {
			$tag = str_replace( $match[0], ' type="' . esc_attr( $wp_scripts->registered[ $handle ]->extra['type'] ) . '"', $tag );
		} else {
			$tag = str_replace( '<script ', '<script type="' . esc_attr( $wp_scripts->registered[ $handle ]->extra['type'] ) . '" ', $tag );
		}
	}

	if ( ! empty( $wp_scripts->registered[ $handle ]->extra['nomodule'] ) ) {
		if ( preg_match( '/snomodule([=\s]([\'\"])((?!\2).+?[^\\\])\2)?/', $tag, $match ) ) {
			$tag = str_replace( $match[0], ' nomodule', $tag );
		} else {
			$tag = str_replace( '<script ', '<script nomodule ', $tag );
		}
	}

	return $tag;
}
