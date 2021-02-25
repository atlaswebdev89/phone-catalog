	<?php 
/**
 * boxes builder
 */

function fluida_lpboxes( $sid = 1 ) {
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

	if ( ( $options['fluida_lpboxcount' . $sid] <= 0 ) || ( $options['fluida_lpboxcat' . $sid] == '-1' ) ) return;

 	$box_counter = 1;
	$animated_class = "";
	if ( $options['fluida_lpboxanimation' . $sid] == 1 ) $animated_class = 'lp-boxes-animated';
	if ( $options['fluida_lpboxanimation' . $sid] == 2 ) $animated_class = 'lp-boxes-static';
	if ( $options['fluida_lpboxanimation' . $sid] == 3 ) $animated_class = 'lp-boxes-animated lp-boxes-animated2';
	if ( $options['fluida_lpboxanimation' . $sid] == 4 ) $animated_class = 'lp-boxes-static lp-boxes-static2';

	$custom_query = new WP_query();
    if ( ! empty( $options['fluida_lpboxcat' . $sid] ) ) $cat = $options['fluida_lpboxcat' . $sid]; else $cat = '';

	$args = apply_filters( 'fluida_boxes_query_args', array(
		'showposts' => $options['fluida_lpboxcount' . $sid],
		'cat' => cryout_localize_cat( $cat ),
		'ignore_sticky_posts' => 1,
		'lang' => cryout_localize_code()
	), $options['fluida_lpboxcat' . $sid], $sid );

    $custom_query->query( $args );

    if ( $custom_query->have_posts() ) : ?>
		<section id="lp-boxes-<?php echo absint( $sid ) ?>" class="lp-boxes lp-boxes-<?php echo absint( $sid ) ?> <?php  echo esc_attr( $animated_class ) ?> lp-boxes-rows-<?php echo absint( $options['fluida_lpboxrow' . $sid] ); ?>">
			<?php if( $options['fluida_lpboxmaintitle' . $sid] || $options['fluida_lpboxmaindesc' . $sid] ) { ?>
				<header class="lp-section-header">
					<?php if ( ! empty( $options['fluida_lpboxmaintitle' . $sid] ) ) { ?> <h2 class="lp-section-title"> <?php echo do_shortcode( wp_kses_post( $options['fluida_lpboxmaintitle' . $sid] ) ) ?></h2><?php } ?>
					<?php if ( ! empty( $options['fluida_lpboxmaindesc' . $sid] ) ) { ?><div class="lp-section-desc"> <?php echo do_shortcode( wp_kses_post( $options['fluida_lpboxmaindesc' . $sid] ) ) ?></div><?php } ?>
				</header>
			<?php } ?>
			<div class="<?php if ( $options['fluida_lpboxlayout' . $sid] == 2 ) { echo 'lp-boxes-inside'; } else { echo 'lp-boxes-outside'; }?>
						<?php if ( $options['fluida_lpboxmargins' . $sid] == 2 ) { echo 'lp-boxes-margins'; }?>
						<?php if ( $options['fluida_lpboxmargins' . $sid] == 1 ) { echo 'lp-boxes-padding'; }?>">
    		<?php while ( $custom_query->have_posts() ) :
	            $custom_query->the_post();
				
				/*
				if ( cryout_has_manual_excerpt( $custom_query->post ) ) {
					$excerpt = get_the_excerpt();
				} elseif ( has_excerpt() ) {
					$excerpt = fluida_custom_excerpt( get_the_excerpt(), $options['fluida_lpboxlength' . $sid] );
				} else {
					$excerpt = fluida_custom_excerpt( get_the_content(), $options['fluida_lpboxlength' . $sid] );
				};
				*/

	            $box = array();
	            $box['colno'] = $box_counter++;
	            $box['counter'] = $options['fluida_lpboxcount' . $sid];
	            $box['title'] = apply_filters('fluida_box_title', get_the_title(), get_the_ID() );
	            $box['content'] = $excerpt;
	            list( $box['image'], ) = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'fluida-lpbox-' . $sid );
	            $box['link'] = apply_filters( 'fluida_box_url', get_permalink(), get_the_ID() );
				$box['readmore'] = do_shortcode( wp_kses_post( $options['fluida_lpboxreadmore' . $sid] ) );
	            $box['target'] = apply_filters( 'fluida_box_target', '', get_the_ID() );
				$box['image'] = apply_filters('fluida_preview_img_src', $box['image']);

			fluida_lpbox_output( $box );
        		endwhile; ?>
			</div>
		</section><!-- .lp-boxes -->
	<?php endif;
	wp_reset_postdata();
} //  fluida_lpboxes()
/**
 * boxes output
 */

function fluida_lpbox_output( $data ) {
	$randomness = array ( 6, 8, 1, 5, 2, 7, 3, 4 );
	extract($data); ?>
			<div class="lp-box box<?php echo absint( $colno ); ?> ">
				<div class="bg-overlay"></div>
					<div class="lp-box-image lpbox-rnd<?php echo $randomness[$colno%8]; ?>">
						<?php if( ! empty( $image ) ) { ?><img alt="<?php echo esc_attr( $title ); ?>" src="<?php echo esc_url( $image ); ?>" /> <?php } ?>
						<a class="lp-box-link" <?php if ( !empty( $link ) ) { ?> href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( $title ); ?>" <?php echo esc_attr( $target ); ?><?php } ?>> <i class="blicon-plus2"></i> </a>
						<div class="lp-box-overlay"></div>
					</div>
					<div class="lp-box-content">
						<?php if ( ! empty( $title ) ) { ?><h5 class="lp-box-title">
							<?php if ( !empty( $readmore ) && !empty( $link ) ) { ?> <a href="<?php echo esc_url( $link ); ?>" <?php echo esc_attr( $target ); ?>><?php } ?>
								<?php echo do_shortcode( $title ); ?>
							<?php if ( !empty( $readmore ) && !empty( $link ) ) { ?> </a> <?php } ?>
						</h5><?php } ?> 

							
								<div class="entry-meta">
										<h6>
											<?php $categories = get_the_category( get_the_ID());
												 	foreach ($categories as $key => $value) {
																echo $value->name.' ';
													} ?>
										</h6>
								</div>
							

					

						<div class="lp-box-text">
							<?php if ( ! empty( $content ) ) { ?>
								<div class="lp-box-text-inside"> <?php echo do_shortcode( $content ); ?> </div>
							<?php } ?>
							<?php if( ! empty( $readmore ) ) { ?>
								<a class="lp-box-readmore" href="<?php if( ! empty( $link ) ) { echo esc_url( $link ); } ?>" <?php echo esc_attr( $target ); ?>> <?php echo do_shortcode( wp_kses_post( $readmore ) ); ?> <em class="screen-reader-text">"<?php echo esc_attr( $title ) ?>"</em> <i class="icon-angle-right"></i></a>
							<?php } ?>
						</div>
					</div>
			</div><!-- lp-box -->
	<?php
} // fluida_lpbox_output()

function custom_views_category() {
	echo "GOOD";
};