<?php
/*
* Plugin Name: SiteStats
* Plugin URI: https://wordpress.org/plugins/sitestats/
* Description: Provides useful and interesting statistics about your Wordpress website.
* Version: 1.0.1
* Author: Paolo Manganiello
* Author URI: http://paolomanganiello.com
* License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('SITESTATS_VERSION', '4.2.4');
define('SITESTATS_DIR', dirname(__FILE__));
define('SITESTATS_URL', plugins_url('',__FILE__));

/* Includes -------------------------------------------- */
include_once(SITESTATS_DIR . '/includes/home.php');
include_once(SITESTATS_DIR . '/includes/posts.php');
include_once(SITESTATS_DIR . '/includes/comments.php');
include_once(SITESTATS_DIR . '/includes/category.php');
include_once(SITESTATS_DIR . '/includes/tags.php');
include_once(SITESTATS_DIR . '/includes/users.php');

add_action('admin_menu', 'sitestats_add_admin_menu');
add_action('admin_init', 'sitestats_settings_init');
add_action('admin_enqueue_scripts', 'sitestats_plugin_styles' );

function sitestats_add_admin_menu() {
	add_menu_page(
		'SiteStats', 
		'SiteStats', 
		'manage_options', 
		'sitestats', 
		'sitestats_plugin_page',
		'dashicons-chart-bar'
	);
}

function sitestats_plugin_page(  ) { 
?>
	<div class="wrap" id="sitestats">

		<h2>SiteStats - All the Statistics You Ever Wanted!</h2>
		<?php settings_errors(); ?>

		<?php if( isset($_GET['tab']) ) {
			$active_tab = $_GET[ 'tab' ];
		} else {
			$active_tab = 'home';
		}
		?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=sitestats&amp;tab=home" class="nav-tab <?php echo $active_tab == 'home' ? 'nav-tab-active' : ''; ?>">Home</a>
			<a href="?page=sitestats&amp;tab=posts" class="nav-tab <?php echo $active_tab == 'posts' ? 'nav-tab-active' : ''; ?>">Posts</a>
			<a href="?page=sitestats&amp;tab=comments" class="nav-tab <?php echo $active_tab == 'comments' ? 'nav-tab-active' : ''; ?>">Comments</a>
			<a href="?page=sitestats&amp;tab=users" class="nav-tab <?php echo $active_tab == 'users' ? 'nav-tab-active' : ''; ?>">Users</a>
			<a href="?page=sitestats&amp;tab=category" class="nav-tab <?php echo $active_tab == 'category' ? 'nav-tab-active' : ''; ?>">Category</a>
			<a href="?page=sitestats&amp;tab=tags" class="nav-tab <?php echo $active_tab == 'tags' ? 'nav-tab-active' : ''; ?>">Tags</a>
		</h2>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
				<div id="post-body-content">
					<div id="postbox-container-2" class="postbox-container">				

						<?php
						if ($active_tab == 'home') {					
							do_settings_sections( 'sitestats_home' );					
						} elseif ($active_tab == 'comments') { 
							do_settings_sections( 'sitestats_comments' );
						} elseif ($active_tab == 'posts') { 
							do_settings_sections( 'sitestats_posts' );
						} elseif ($active_tab == 'category') { 
							do_settings_sections( 'sitestats_category' );
						} elseif ($active_tab == 'tags') { 
							do_settings_sections( 'sitestats_tags' );
						} elseif ($active_tab == 'users') { 
							do_settings_sections( 'sitestats_users' );
						}
						?>	
							
					</div>
					<div id="postbox-container-1" class="postbox-container">						
						<!-- Donate -->
						<div class="postbox">
							<h3 class="hndle">Support SiteStats</h3>
							<div class="inside">
								<p>If you enjoy this plugin, you can thank me by sending a small donation for the time I’ve spent writing and supporting this plugin. Thank you!</p>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" id="paypal_form">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="S44U5LF2FFR8Y">
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Donate with PayPal">
									<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
								</form>

							</div>
						</div>
						<!-- Suggest -->
						<div class="postbox">
							<h3 class="hndle">Suggest new features!</h3>
							<div class="inside">
								<p>Would you like to see more statistics? Send me your ideas to <a href="mailto:me@paolomanganiello.com">me@paolomanganiello.com</a>, I will be happy to implement them in the future releases of SiteStats!</p> 														
							</div>
						</div>
						<!-- Support -->
						<div class="postbox">
							<h3 class="hndle">Support</h3>
							<div class="inside">
								<ul>
									<p><a href="https://wordpress.org/plugins/sitestats/faq/">Help / FAQ</a>
									<p><a href="https://wordpress.org/support/plugin/sitestats">Support Forum</a></p>
									<p><a href="https://wordpress.org/support/view/plugin-reviews/sitestats">Review SiteStats on Wordpress.org</a></p>
								</ul>
							</div>
						</div>
						<!-- About -->
						<div class="postbox">
							<h3 class="hndle">About SiteStats</h3>
							<div class="inside">
								<ul>
									<p>Author : <a href="http://paolomanganiello.com">Paolo Manganiello</a></p>
									<p>Website : <a href="http://paolomanganiello.com">paolomanganiello.com</a></p>
									<p>Email : <a href="mailto:me@paolomanganiello.com">me@paolomanganiello.com</a></p>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>

<?php 
}

function sitestats_settings_init(  ) { 

	add_settings_section(
		'sitestats_home_section', 						// Slug
		'', 											// Section Title
		'sitestats_home_section_callback', 				// Name of the function that will display the section’s content.
		'sitestats_home'								// Page to display the section on
	);

	add_settings_section(
		'sitestats_posts_section',
		'', 
		'sitestats_posts_section_callback', 
		'sitestats_posts'
	);

	add_settings_section(
		'sitestats_comments_section',
		'', 
		'sitestats_comments_section_callback', 
		'sitestats_comments'
	);	

	add_settings_section(
		'sitestats_category_section',
		'', 
		'sitestats_category_section_callback', 
		'sitestats_category'
	);	

	add_settings_section(
		'sitestats_tags_section',
		'', 
		'sitestats_tags_section_callback', 
		'sitestats_tags'
	);	

	add_settings_section(
		'sitestats_users_section',
		'', 
		'sitestats_users_section_callback', 
		'sitestats_users'
	);
}

function sitestats_plugin_styles() {
	wp_register_style( 'sitestats_css', plugins_url( 'sitestats/css/sitestats.css' ) );
	wp_enqueue_style( 'sitestats_css' );
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}

