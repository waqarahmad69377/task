<?php

/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_VERSION', '2.7.1');

function project_scripts()
{
	wp_register_script('project-jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js', '', HELLO_ELEMENTOR_VERSION, false);
	wp_register_script('project-js', get_template_directory_uri() . '/assets/js/projects.js', array('project-jquery'), HELLO_ELEMENTOR_VERSION, false);
	wp_localize_script('project-js', 'ajax_projects', array(
		'ajax_url' => admin_url('admin-ajax.php')
	));
	wp_enqueue_script('project-js');
	wp_enqueue_script('project-jquery');
}
add_action('wp_enqueue_scripts', 'project_scripts');



if (!isset($content_width)) {
	$content_width = 800; // Pixels.
}

if (!function_exists('hello_elementor_setup')) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup()
	{
		if (is_admin()) {
			hello_maybe_update_theme_version_in_db();
		}

		if (apply_filters('hello_elementor_register_menus', true)) {
			register_nav_menus(['menu-1' => esc_html__('Header', 'hello-elementor')]);
			register_nav_menus(['menu-2' => esc_html__('Footer', 'hello-elementor')]);
		}

		if (apply_filters('hello_elementor_post_type_support', true)) {
			add_post_type_support('page', 'excerpt');
		}

		if (apply_filters('hello_elementor_add_theme_support', true)) {
			add_theme_support('post-thumbnails');
			add_theme_support('automatic-feed-links');
			add_theme_support('title-tag');
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);

			/*
			 * Editor Style.
			 */
			add_editor_style('classic-editor.css');

			/*
			 * Gutenberg wide images.
			 */
			add_theme_support('align-wide');

			/*
			 * WooCommerce.
			 */
			if (apply_filters('hello_elementor_add_woocommerce_support', true)) {
				// WooCommerce in general.
				add_theme_support('woocommerce');
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support('wc-product-gallery-zoom');
				// lightbox.
				add_theme_support('wc-product-gallery-lightbox');
				// swipe.
				add_theme_support('wc-product-gallery-slider');
			}
		}
	}
}
add_action('after_setup_theme', 'hello_elementor_setup');

function hello_maybe_update_theme_version_in_db()
{
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option($theme_version_option_name);

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if (!$hello_theme_db_version || version_compare($hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<')) {
		update_option($theme_version_option_name, HELLO_ELEMENTOR_VERSION);
	}
}

if (!function_exists('hello_elementor_scripts_styles')) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles()
	{
		$min_suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		if (apply_filters('hello_elementor_enqueue_style', true)) {
			wp_enqueue_style(
				'hello-elementor',
				get_template_directory_uri() . '/style' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if (apply_filters('hello_elementor_enqueue_theme_style', true)) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				get_template_directory_uri() . '/theme' . $min_suffix . '.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action('wp_enqueue_scripts', 'hello_elementor_scripts_styles');

if (!function_exists('hello_elementor_register_elementor_locations')) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations($elementor_theme_manager)
	{
		if (apply_filters('hello_elementor_register_elementor_locations', true)) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action('elementor/theme/register_locations', 'hello_elementor_register_elementor_locations');

if (!function_exists('hello_elementor_content_width')) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width()
	{
		$GLOBALS['content_width'] = apply_filters('hello_elementor_content_width', 800);
	}
}
add_action('after_setup_theme', 'hello_elementor_content_width', 0);

if (is_admin()) {
	require get_template_directory() . '/includes/admin-functions.php';
}

/**
 * If Elementor is installed and active, we can load the Elementor-specific Settings & Features
 */

// Allow active/inactive via the Experiments
require get_template_directory() . '/includes/elementor-functions.php';

/**
 * Include customizer registration functions
 */
function hello_register_customizer_functions()
{
	if (is_customize_preview()) {
		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action('init', 'hello_register_customizer_functions');

if (!function_exists('hello_elementor_check_hide_title')) {
	/**
	 * Check hide title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title($val)
	{
		if (defined('ELEMENTOR_VERSION')) {
			$current_doc = Elementor\Plugin::instance()->documents->get(get_the_ID());
			if ($current_doc && 'yes' === $current_doc->get_settings('hide_title')) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter('hello_elementor_page_title', 'hello_elementor_check_hide_title');

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if (!function_exists('hello_elementor_body_open')) {
	function hello_elementor_body_open()
	{
		wp_body_open();
	}
}

function ip_base_login()
{
	$visitor = $_SERVER['REMOTE_ADDR'];
	$redirectTo = site_url('wp_admin');
	if (preg_match("/192.168.100.65/", $visitor)) {
		return wp_redirect($redirectTo);
	}
	exit;
}

function ajax_projects()
{

	$is_user_login = is_user_logged_in();

	$args = array(
		'post_type' => 'projects',
		'numberposts' => $is_user_login ? 6 : 3,
		'post_status' => 'publish',
		'order' => 'ASC',
		'orderby' => 'ID',
		'tax_query' => array(
			array(
				'taxonomy' => 'project_type',
				'field' => 'slug',
				'terms' => 'architecture'
			)
		)
	);
	$projects = get_posts($args);
	$results = array();
	foreach ($projects as $pro) {
		$temp = array();
		$temp['id'] = $pro->ID;
		$temp['title'] = $pro->post_title;
		$temp['link'] = get_post_permalink($pro->ID);
		array_push($results, $temp);
	}
	echo json_encode(array('status' => "true", 'data' => $results));
	wp_die();
}
add_action('wp_ajax_nopriv_ajax_projects', 'ajax_projects');
add_action('wp_ajax_ajax_projects', 'ajax_projects');


add_action('rest_api_init', function () {
	register_rest_route('cup_of_coffee/v1', '/link', array(
		'methods' => 'GET',
		'callback' => 'hs_give_me_coffee'
	));
});

function  hs_give_me_coffee()
{
	return json_encode(array("status" => "ok", "data" => array("coffee-link" => "https://bmc.link/waqarahmadB")));
}

function get_quotes()
{
	$get_response = wp_remote_get("https://api.kanye.rest/");
	echo json_encode(array($get_response));
	wp_die();
}
add_action('wp_ajax_nopriv_get_quotes', 'get_quotes');
add_action('wp_ajax_get_quotes', 'get_quotes');

function cptui_register_my_cpts()
{

	/**
	 * Post Type: Projects.
	 */

	$labels = [
		"name" => esc_html__("Projects", "hello-elementor"),
		"singular_name" => esc_html__("Project", "hello-elementor"),
		"menu_name" => esc_html__("Projects", "hello-elementor"),
		"all_items" => esc_html__("All Projects", "hello-elementor"),
		"add_new" => esc_html__("Add New", "hello-elementor"),
		"add_new_item" => esc_html__("Add New Project", "hello-elementor"),
		"edit_item" => esc_html__("Edit Project", "hello-elementor"),
		"new_item" => esc_html__("New Project", "hello-elementor"),
		"view_item" => esc_html__("View Project", "hello-elementor"),
		"view_items" => esc_html__("View Projects", "hello-elementor"),
		"search_items" => esc_html__("Search Projects", "hello-elementor"),
		"not_found" => esc_html__("No Projects Found", "hello-elementor"),
		"not_found_in_trash" => esc_html__("No Projects Found in Trash", "hello-elementor"),
		"parent" => esc_html__("Parent Project", "hello-elementor"),
		"featured_image" => esc_html__("Featured image for this project", "hello-elementor"),
		"set_featured_image" => esc_html__("Set Featured image for this project", "hello-elementor"),
		"remove_featured_image" => esc_html__("Remove Featured image for this project", "hello-elementor"),
		"use_featured_image" => esc_html__("Use as featured image for this project", "hello-elementor"),
		"archives" => esc_html__("Project Archives", "hello-elementor"),
		"insert_into_item" => esc_html__("Insert into project", "hello-elementor"),
		"uploaded_to_this_item" => esc_html__("Uploaded to this project", "hello-elementor"),
		"filter_items_list" => esc_html__("Filter projects list", "hello-elementor"),
		"items_list_navigation" => esc_html__("Project list navigation", "hello-elementor"),
		"items_list" => esc_html__("Projects list", "hello-elementor"),
		"attributes" => esc_html__("Project Attributes", "hello-elementor"),
		"name_admin_bar" => esc_html__("Project", "hello-elementor"),
		"item_published" => esc_html__("Project Published", "hello-elementor"),
		"item_published_privately" => esc_html__("Project Published Privately", "hello-elementor"),
		"item_reverted_to_draft" => esc_html__("Project reverted to draft", "hello-elementor"),
		"item_scheduled" => esc_html__("Project Scheduled", "hello-elementor"),
		"item_updated" => esc_html__("Project Updated", "hello-elementor"),
		"parent_item_colon" => esc_html__("Parent Project", "hello-elementor"),
	];

	$args = [
		"label" => esc_html__("Projects", "hello-elementor"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => true,
		"can_export" => true,
		"rewrite" => ["slug" => "projects", "with_front" => true],
		"query_var" => true,
		"menu_position" => 9,
		"menu_icon" => "dashicons-image-filter",
		"supports" => ["title", "editor", "thumbnail", "excerpt", "custom-fields", "comments", "revisions", "author", "page-attributes", "post-formats"],
		"show_in_graphql" => false,
	];

	register_post_type("projects", $args);
}

add_action('init', 'cptui_register_my_cpts');

function cptui_register_my_taxes()
{

	/**
	 * Taxonomy: Projects Type.
	 */

	$labels = [
		"name" => esc_html__("Projects Type", "hello-elementor"),
		"singular_name" => esc_html__("Project Type", "hello-elementor"),
		"menu_name" => esc_html__("Project Type", "hello-elementor"),
		"all_items" => esc_html__("All Project Type", "hello-elementor"),
		"edit_item" => esc_html__("Edit Project Type", "hello-elementor"),
		"view_item" => esc_html__("View Project Type", "hello-elementor"),
		"update_item" => esc_html__("Update Project Type", "hello-elementor"),
		"add_new_item" => esc_html__("Add Project Type", "hello-elementor"),
		"new_item_name" => esc_html__("New Project Type Name", "hello-elementor"),
		"parent_item" => esc_html__("Parent Project Type", "hello-elementor"),
		"parent_item_colon" => esc_html__("Parent Project Type:", "hello-elementor"),
		"search_items" => esc_html__("Search Project Type", "hello-elementor"),
		"popular_items" => esc_html__("Popular Projects Type", "hello-elementor"),
		"separate_items_with_commas" => esc_html__("Separate Project Type with commas", "hello-elementor"),
		"add_or_remove_items" => esc_html__("Add or Remove Projects Type", "hello-elementor"),
		"choose_from_most_used" => esc_html__("Choose from the most used Project Type", "hello-elementor"),
		"not_found" => esc_html__("No Project Type Found", "hello-elementor"),
		"no_terms" => esc_html__("No Project Type", "hello-elementor"),
		"items_list_navigation" => esc_html__("Projects Type List Navigation", "hello-elementor"),
		"items_list" => esc_html__("Projects Type List", "hello-elementor"),
		"back_to_items" => esc_html__("Back to project type", "hello-elementor"),
		"name_field_description" => esc_html__("Project Type Description", "hello-elementor"),
		"parent_field_description" => esc_html__("Project Type Parent Description", "hello-elementor"),
	];


	$args = [
		"label" => esc_html__("Projects Type", "hello-elementor"),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => ['slug' => 'project_type', 'with_front' => true,  'hierarchical' => true,],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"show_tagcloud" => true,
		"rest_base" => "project_type",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => true,
		"sort" => true,
		"show_in_graphql" => false,
	];
	register_taxonomy("project_type", ["projects"], $args);
}
add_action('init', 'cptui_register_my_taxes');
