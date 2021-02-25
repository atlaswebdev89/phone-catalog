<?php 


require_once( get_stylesheet_directory(). "/includes/landing-page.php" );	// Landing Page outputs

//Подключение стилей родетельской темы FLUID
add_action( 'wp_enqueue_scripts', 'true_enqueue_styles' );
function true_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	//wp_enqueue_style( 'child-style',  get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
}

//Проверка авторизации для посещения любой страницы сайта
add_action('get_header', 'onlyregistered_function');
function onlyregistered_function() {
 	if(!is_user_logged_in() && !is_404()) {
  		auth_redirect();
 	}
}

//Задаем миниатюру по умолчанию 
add_action('save_post', 'my_template_thumbnail');
function my_template_thumbnail($post_id) {
$id_thumbnail_default = '89';
$post_thumbnail = get_post_meta($post_id, $key = '_thumbnail_id', $single = true);
  if ( !wp_is_post_revision($post_id) ) :
    if ( empty($post_thumbnail) ) {
      update_post_meta($post_id, $meta_key = '_thumbnail_id', $meta_value = '89');
    }
  endif;
}




