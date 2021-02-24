<?php 

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

