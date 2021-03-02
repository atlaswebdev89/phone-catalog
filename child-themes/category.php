<?php
get_header(); ?>
	<div id="container" class="<?php echo fluida_get_layout_class(); ?>">
		<main id="main" role="main" class="main">
			<?php cryout_before_content_hook(); ?>
				<div>
                                   <?php $category = get_queried_object();?>
                                        <article <?php post_class( 'hentry' ); ?> >
                                                <?php if ( false == get_post_format() ) { cryout_featured_hook(); } ?>
                                                <div class="article-inner list-phone">
                                                        <header class="entry-header">
                                                                <?php get_list_phone_in_category($category); ?>
                                                        </header><!-- .entry-header -->
                                                </div>
                                        </article>
				</div><!--content-masonry-->
				<?php cryout_after_content_hook(); ?>
		</main><!-- #main -->
            <?php fluida_get_sidebar(); ?>
	</div><!-- #container -->
<?php get_footer(); 


