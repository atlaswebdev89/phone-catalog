<?php 

require_once( get_stylesheet_directory(). "/includes/front-page.php" );	// Landing Page outputs
require_once( get_stylesheet_directory(). "/includes/categoryList.php" );// Для страниц рубрик вывод категорий и записей

/**
 * Delete meta data from headers
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links',2);
remove_action('wp_head', 'feed_links_extra',3);
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'wp_shortlink_wp_head',10,0);
remove_action('wp_head', 'wp_oembed_add_discovery_links' );
remove_action('wp_head', 'wp_oembed_add_host_js');
// Удаляем информацию о REST API из заголовков HTTP и секции head
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );
//dns-prefetch
remove_action( 'wp_head', 'wp_resource_hints', 2 );
//Emoji из WordPress
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
//Нужен он для редактора Gutenberg
add_action( 'wp_enqueue_scripts', 'wpassist_remove_block_library_css' );
function wpassist_remove_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
}

//Подключение стилей родетельской темы FLUID
add_action( 'wp_enqueue_scripts', 'true_enqueue_styles' );
function true_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	//wp_enqueue_style( 'child-style',  get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
}

//Проверка авторизации для посещения любой страницы сайта
add_action('get_header', 'onlyregistered_function');
function onlyregistered_function() {
 	if(!is_user_logged_in()) {
  		auth_redirect();
 	}
}

//Задаем миниатюру по умолчанию 
add_action('save_post', 'my_template_thumbnail');
function my_template_thumbnail($post_id) {
$id_thumbnail_default = '25';
$post_thumbnail = get_post_meta($post_id, $key = '_thumbnail_id', $single = true);
  if ( !wp_is_post_revision($post_id) ) :
    if ( empty($post_thumbnail) ) {
      update_post_meta($post_id, $meta_key = '_thumbnail_id', $meta_value = $id_thumbnail_default);
    }
  endif;
}




