<?php 
define('URL_THEMES', get_stylesheet_directory_uri());
define('SPRAVOCHNIK', 'spravochnik');
define('TITLE_SECTION_DEFAULT', 'Справочник');
define('ID_IMAGE_NOT_FOUND', 45);

//Получение данных корневой рубрики Справочник
function get_spravochnik() {
     $data = get_category_by_slug(SPRAVOCHNIK);
     if ($data) return $data;
}

//Получение дочерних категорий первого уровня от переданной корневой категории
function get_category_by_parentID($parentID=0, $numberCounts  =0) {
            $ID = $parentID;
                    $param = [
                        'taxonomy' => 'category', //Тип таксономии (категории)
                        'hide_empty' => false, //Показывать категории не имеющие записей
                        'parent' => $ID, // id родительской категории
                        'number' => $numberCounts,
                        'order' => 'ASC',
                        'orderby'=> 'slug',
                    ];
            $category = get_terms($param);
        return $category;
}

//Получение картинки по id
function get_src_image_not_found ($id_image = ID_IMAGE_NOT_FOUND, $size = 'medium') {
    if($id_image) {
        $image_src = wp_get_attachment_image_url($id_image, $size);
        if($image_src) {
            return $image_src;
        }else {
            return URL_THEMES."/assets/img/image-cat-not-found.jpg";
        }
    }
}

//Вывод картинок таксономий использую плагин WP Multiple Taxonomy Images
//get_tax_image_urls - функция плагина WP Multiple Taxonomy Images
function get_url_tax ($id = 0, $size='full') {
    if (function_exists('get_tax_image_urls')) {
        if($id) {
                $img_urls = get_tax_image_urls($id ,$size); 
                    if ($img_urls) {
                         return $img_urls[0];
                    }else {
                        return get_src_image_not_found();
                    }  
                }else {
                    $tax_obj = get_queried_object();
                    $term_id = $tax_obj->term_id;
                    $img_urls = get_tax_image_urls($term_id ,$size); 
                    if ($img_urls) {
                        return $img_urls[0];
                    }else {
                        return get_src_image_not_found();
                    }  
            }
        }else {
            return get_src_image_not_found();
    }
}

function atlas_custom_lpboxes($sid = 1) {
                $options = cryout_get_option(
				array(
					 'fluida_lpboxmaintitle' . $sid,
					 'fluida_lpboxmaindesc' . $sid,
					 'fluida_lpboxcat' . $sid,
					 'fluida_lpboxrow' . $sid,
					 'fluida_lpboxcount' . $sid,
					 'fluida_lpboxlayout' . $sid,
					 'fluida_lpboxmargins' . $sid,
					 'fluida_lpboxanimation' . $sid,
					 'fluida_lpboxreadmore' . $sid,
					 'fluida_lpboxlength' . $sid,
				 )
			 );
 	$box_counter = 1;
	$animated_class = "";
	if ( $options['fluida_lpboxanimation' . $sid] == 1 ) $animated_class = 'lp-boxes-animated';
	if ( $options['fluida_lpboxanimation' . $sid] == 2 ) $animated_class = 'lp-boxes-static';
	if ( $options['fluida_lpboxanimation' . $sid] == 3 ) $animated_class = 'lp-boxes-animated lp-boxes-animated2';
	if ( $options['fluida_lpboxanimation' . $sid] == 4 ) $animated_class = 'lp-boxes-static lp-boxes-static2';
        
        $numberCounts=($options['fluida_lpboxcount' . $sid]>=0)?$options['fluida_lpboxcount' . $sid]:0;
        
        $cat = get_spravochnik();
        $header_section = (($cat->name))?$cat->name:TITLE_SECTION_DEFAULT;
        $categoryes = get_category_by_parentID ($cat->term_id, $numberCounts);
        
        if ( $categoryes ): ?>
		<section id="lp-boxes-<?php echo absint( $sid ) ?>" class="lp-boxes lp-boxes-<?php echo absint( $sid ) ?> <?php  echo esc_attr( $animated_class ) ?> lp-boxes-rows-<?php echo absint( $options['fluida_lpboxrow' . $sid] ); ?>">
				<header class="lp-section-header">
					<?php if ( ! empty( $header_section) ) { ?> <h2 class="lp-section-title"> <?php echo $header_section; ?></h2><?php } ?>
				</header>
			
			<div class="<?php if ( $options['fluida_lpboxlayout' . $sid] == 2 ) { echo 'lp-boxes-inside'; } else { echo 'lp-boxes-outside'; }?>
					<?php if ( $options['fluida_lpboxmargins' . $sid] == 2 ) { echo 'lp-boxes-margins'; }?>
					<?php if ( $options['fluida_lpboxmargins' . $sid] == 1 ) { echo 'lp-boxes-padding'; }?>">
                                                    
                        <?php foreach ($categoryes as $key=>$caterogy):
                                    $box = array();
                                    $box['colno'] = $box_counter++;
                                    $box['counter'] = $options['fluida_lpboxcount' . $sid];
                                    $box['title'] = $caterogy->name;
                                    $box['link'] =get_category_link( $caterogy->term_id );
                                    $box['readmore'] = do_shortcode( wp_kses_post( $options['fluida_lpboxreadmore' . $sid] ) );
                                    $box['image'] = apply_filters('fluida_preview_img_src', get_url_tax($caterogy->term_id, 'medium'));
                                fluida_lpbox_output( $box );
                            endforeach;?>
			</div>
		</section><!-- .lp-boxes -->
	<?php endif;

};

function fluida_lpbox_output( $data ) {
	$randomness = array ( 6, 8, 1, 5, 2, 7, 3, 4 );
	extract($data); ?>
			<div class="lp-box box<?php echo absint( $colno ); ?> ">
                            <div class="bg-overlay"></div>
					<div class="lp-box-image lpbox-rnd<?php echo $randomness[$colno%8]; ?>">
						<?php if( ! empty( $image ) ) { ?><img alt="<?php echo esc_attr( $title ); ?>" src="<?php echo esc_url( $image ); ?>" /> <?php } ?>
						<a class="lp-box-link" <?php if ( !empty( $link ) ) { ?> href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( $title ); ?>" <?php } ?>> <i class="blicon-plus2"></i> </a>
						<div class="lp-box-overlay"></div>
					</div>
					<div class="lp-box-content">
						<?php if ( ! empty( $title ) ) { ?><h5 class="lp-box-title">
							<?php if ( !empty( $readmore ) && !empty( $link ) ) { ?> <a href="<?php echo esc_url( $link ); ?>" ><?php } ?>
								<?php echo do_shortcode( $title ); ?>
							<?php if ( !empty( $readmore ) && !empty( $link ) ) { ?> </a> <?php } ?>
						</h5><?php } ?>
						<div class="lp-box-text">
							<?php if ( ! empty( $content ) ) { ?>
								<div class="lp-box-text-inside"> <?php echo do_shortcode( $content ); ?> </div>
							<?php } ?>
							<?php if( ! empty( $readmore ) ) { ?>
								<a class="lp-box-readmore" href="<?php if( ! empty( $link ) ) { echo esc_url( $link ); } ?>" > <?php echo do_shortcode( wp_kses_post( $readmore ) ); ?> <em class="screen-reader-text">"<?php echo esc_attr( $title ) ?>"</em> <i class="icon-angle-right"></i></a>
							<?php } ?>
						</div>
					</div>
			</div><!-- lp-box -->
	<?php
} // fluida_lpbox_output()