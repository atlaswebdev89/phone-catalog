<?php
function get_list_phone_in_category($parent_id = 5) {

if($parent_id) {
    $current_category = get_category ($parent_id);
}
    
echo '<h2>'.$current_category->name.'</h2>';
$myposts = get_posts( array(
                                            'numberposts' => -1,
                                            'category_name' =>$current_category->slug,
                                        ) );   






# получаем дочерние рубрики
$sub_cats = get_categories( array(
	'parent' => $parent_id,
	'hide_empty' => 0
) );
if( $sub_cats ){
     if($myposts) {
            foreach($myposts as $post){
                $categories = get_the_category($post->ID);
                    foreach ($categories as $key => $value) {
                            if($value->term_id == $current_category->term_id) {
                                    echo '<li><i class="icon-tags"></i>  <a href="'. get_the_permalink($post->ID) .'">'. get_the_title($post->ID) .'</a></li>';
                                }
                       }
                   }
            }
            
	foreach( $sub_cats as $cat ){
		// Данные в объекте $cat

		// $cat->term_id
		// $cat->name (Рубрика 1)
		// $cat->slug (rubrika-1)
		// $cat->term_group (0)
		// $cat->term_taxonomy_id (4)
		// $cat->taxonomy (category)
		// $cat->description ()
		// $cat->parent (0)
		// $cat->count (14)
		// $cat->object_id (2743)
		// $cat->cat_ID (4)
		// $cat->category_count (14)
		// $cat->category_description ()
		// $cat->cat_name (Рубрика 1)
		// $cat->category_nicename (rubrika-1)
		// $cat->category_parent (0)
                if($cat->parent == $parent_id) {
                    echo '<h3><a href ="'.get_category_link($cat->term_id).'">'. $cat->name .'</a></h3>';
                                   # получаем записи из рубрики
                                    $myposts = get_posts( array(
                                            'numberposts' => -1,
                                            'category_name' =>$cat->slug,
                                        ) );
                                    # выводим записи
                                global $post;
                                    if($myposts) {
                                        echo '<ul>';
                                            foreach($myposts as $post){
                                                setup_postdata($post);
                                                    echo '<li><i class="icon-tags"></i>  <a href="'. get_permalink() .'">'. get_the_title() .'</a></li>';
                                                }
                                        echo '</ul>';
                                    }else {
                                        echo '<p>Телефонных номеров в указанном разделе нет</p>';
                                    }
                }
	}
	wp_reset_postdata(); // сбрасываем глобальную переменную пост
    }else {
        # получаем записи из рубрики
                                    $myposts = get_posts( array(
                                            'numberposts' => -1,
                                            'category' =>$parent_id,
                                        ) );
                                    # выводим записи
                                global $post;
                                    if($myposts) {
                                        echo '<ul>';
                                            foreach($myposts as $post){
                                                setup_postdata($post);
                                                    echo '<li><i class="icon-tags"></i>  <a href="'. get_permalink() .'">'. get_the_title() .'</a></li>';
                                                }
                                        echo '</ul>';
                                    }else {
                                        echo '<p>Телефонных номеров в указанном разделе нет</p>';
                                    }
                            wp_reset_postdata();
    }
}
