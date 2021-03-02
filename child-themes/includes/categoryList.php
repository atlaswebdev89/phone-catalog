<?php
function get_list_phone_in_category($category) {

if($category->term_id) {
        $current_category = get_category ($category->term_id);
        $parent_id = $category->term_id;
}
    
echo '<h2>'.$category->name.'</h2>';
//Получаем посты текущей рубрики
$myposts = get_posts(       
                        [     
                            'numberposts' => -1,
                            'category_name' =>$category->slug,
                        ]
                    );   
# получаем дочерние рубрики
$sub_cats = get_categories( array(
	'child_of' => $category->term_id,
	'hide_empty' => 0
) );

if( $sub_cats ){
        //Выводим посты, которое пренадлежат только текущей категории а не дочерним
        if($myposts) {
                foreach($myposts as $post){
                    $categories = get_the_category($post->ID);
                    foreach ($categories as $item_cat) {
                        foreach ($sub_cats as $item_subcat){
                                if($item_cat->term_id == $item_subcat->term_id){
                                    $arr[]=$post->ID;
                                }
                        }
                    }
                }
                foreach ($myposts as $value) {
                    if(!in_array($value->ID, $arr)) {
                        echo '<li><i class="icon-tags"></i>  <a href="'. get_the_permalink($value->ID) .'">'. get_the_title($value->ID) .'</a></li>';
                    }
                }
        }
    
    foreach( $sub_cats as $cat ){
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
