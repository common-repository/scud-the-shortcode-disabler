<?php
/*
Plugin Name: SCuD - The ShortCode Disabler 
Plugin URI: http://thecodecave.com/plugins/scud-the-shortcode-disabler/
Description: Allows you to disable shortcodes on a per post/page basis
Version: 1.0.1
Author: Brian Layman
Author URI: http://eHermitsInc.com/
License: GPL2
Requires: 2.5

Copyright 2014  Brian Layman  (email : plugins@thecodecave.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('SCUD_ARMING_KEY', '_tcc-scud-armed');

class shortcodeDisabler {
	protected $stored_shortcodes = array();

	function reg_meta_box() {
		// This security check is also verified upon save.
		if ( current_user_can('edit_theme_options') ) {
			add_meta_box( 'scud_sectionid', __( 'SCuD - The ShortCode Disabler', 'shortcode_disabler' ), array( $this, 'display_custom_box' ), 'page', 'advanced', 'high' );
			add_meta_box( 'scud_sectionid', __( 'SCuD - The ShortCode Disabler', 'shortcode_disabler' ), array( $this, 'display_custom_box' ), 'post', 'advanced', 'high' );
		}
	}
	
	function add_checked( $testvalue, $value ) {
		if ($testvalue == $value) 
			return ' checked="checked" ';
	}
	
	function display_custom_box( $post ) {
		$scudArmed = get_post_meta($post->ID, SCUD_ARMING_KEY, true);
	
		echo '<input type="hidden" id="scud_nonce" name="scud_nonce" value="' . wp_create_nonce(plugin_basename(__FILE__) ) . '" />';
		echo '<input type="checkbox" value="1" id="scud-armed" name="scud-armed" class="scud-checkbox" ' .
			$this->add_checked( $scudArmed, true) . '/> <label for="scud-armed">' . __('Disable ShortCodes on this post/page?', 'shortcode_disabler' ) . 
			'</label><br/><br/>';
	}

	function save_postdata( $post_id ) {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

		if ( current_user_can('edit_theme_options') ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			return $post_id;
		}

		// Return with no errors if the NONCE fails. This both blocks hack attacks AND prevents a save from removing
		// the data if plugin modification adds security checks on meta box display that aren't duplicated here.
		if (!wp_verify_nonce($_POST['scud_nonce'], plugin_basename(__FILE__))) return $post_id;
		
		$isArmed = ( $_POST['scud-armed'] == true );
		
		if ( $isArmed ) {
			update_post_meta($post_id, SCUD_ARMING_KEY, $isArmed);
		} else {
			delete_post_meta($post_id, SCUD_ARMING_KEY);
		}
		return $post_id;
	}
	
	function launch( $content ) {
		global $post;
        global $shortcode_tags;
		$scudArmed = get_post_meta($post->ID, SCUD_ARMING_KEY, true);
		if ( $scudArmed ) {
			$this->stored_shortcodes = $shortcode_tags;
			$shortcode_tags = array();			
		}
		// otherwise returns the database content
		return $content;
	}

	function recover( $content ) {
		global $post;
        global $shortcode_tags;
		$scudArmed = get_post_meta($post->ID, SCUD_ARMING_KEY, true);
		if ( $scudArmed ) {
			$shortcode_tags = $this->stored_shortcodes;
			$this->stored_shortcodes = array();
		}
		// otherwise returns the database content
		return $content;
	}
	
}

$scud = new shortcodeDisabler();

// Add the edit field to the Page/Post Edit screens
add_action('admin_menu', array( $scud, 'reg_meta_box' ) ); 

// Process the custom values submitted when saving pages.
add_action('save_post', array( $scud, 'save_postdata' ) );

add_filter( 'the_content', array( $scud, 'launch' ), -999999 );
add_filter( 'the_content', array( $scud, 'recover' ), 999999 );