<?php get_header(); ?>

<body <?php body_class(); ?>>

<?php if (get_theme_mod('glidingghost_topbar_menu')) { ?>
		<div id="topbar">			
			<div class="closed"><div class="right">+</div><div class="clear"></div></div>	
			<div class="open">				
					<div class="left">
                    <?php if ( has_nav_menu( 'topbar' ) ) : ?>
                        <?php wp_nav_menu( array('theme_location'  => 'topbar' ) ); ?> <?php elseif ( is_active_sidebar( 'top_bar' ) ) : ?><?php ( dynamic_sidebar('top_bar') ); ?><?php endif; ?>					
					</div>					
					<div class="right">&times;</div>					
					<div class="clear"></div>
			</div>
		</div><?php } ?>

    <header class="site-header">
        <div class="container">
            <div class="site-title-wrapper">
                <h1 class="site-title"><a class="js-ajax-link" href="<?php echo home_url(); ?>"><?php echo get_bloginfo('name'); ?></a></h1>
                <a class="button-square" href="<?php echo home_url(); ?>/<?php echo get_option('glidingghost_toc'); ?>"><i class="icon-menu"></i></a>
				<?php
				$my_query = new WP_Query( array( 'post_type' => 'portfolio', "nopaging"=>true ) );
				if ($my_query->have_posts()) : $my_query->the_post(); { 
		  echo '<a class="shift button-square workicon" id="goworkarchive"><i class="icon-briefcase"></i></a>
                <a class="shift button-square workicon" id="goworkpost"><i class="icon-briefcase"></i></a>'; } ?>				
				<?php endif; wp_reset_postdata(); ?>

                <a class="shift button-square bookicon" id="goarchive"><i class="icon-book"></i></a>
                <a class="shift button-square bookicon" id="gopost"><i class="icon-book"></i></a>
            </div>
        </div>
    </header>

<div id="wrapper">
  <div id="postcontainer">
	<div id="post">


			<?php the_post(); $do_not_duplicate = get_the_ID(); ?>

  		<div class="container">
			<article class="post">
				<header class="post-header">
					<h1 class="post-title"><?php the_title(); ?></h1>
					<p class="post-date">
						<?php echo (__('Published', 'glidingghost')); ?> <?php date_i18n(the_date()) ?></a>
					</p>
				</header>
				<div class="post-content">
						<?php the_content(); ?>
				</div>	

			<?php if ( !is_front_page() && !is_page() ) : ?>
 			    <div class="post-navigation">
			        <nav class="pagination short" role="pagination">        
 			       	<?php previous_post_link('%link', (__('&larr; Previous'))); ?>   
 			       	<?php next_post_link('%link', (__('Next &rarr;'))); ?>   
					</nav>
			        <nav class="pagination full" role="pagination"> 
 			       	<?php previous_post_link('%link', ('&larr; %title')); ?>   
  			      	<?php next_post_link('%link', ('%title &rarr;')); ?>   
					</nav>
   				</div>
			<?php endif; ?>

            <?php echo comments_template(array('id_submit'=>'commentbutton')); ?>

    		</article>

		</div>
	</div>
	<!-- END OF POST CONTAINER -->


	<div id="archive">
      
		<div id="post-index" class="container">
		    <ol class="post-list">

				<?php				
				$my_query = new WP_Query( array( "nopaging"=>false ) );
				while ($my_query->have_posts()) :
					$my_query->the_post();
				?>
				<li class="post-stub"><a rel="<?php the_permalink(); ?>" id="<?php the_id(); ?>" title="<?php echo( basename( get_permalink() ) ); ?>"><h4 class="post-stub-title"><?php the_title(); ?></h4> <span class="post-stub-date"><?php the_time('j F, Y') ?> <?php
$category = get_the_category(); echo '<span class="category"> ('. $category[0]->cat_name.')</span>'?></span> </a></li>
				<?php endwhile; wp_reset_postdata(); ?>
            </ol>
		</div>

	</div>


	<div id="workarchive">
      
		<div id="post-index" class="container">
		    <ol class="post-list">

				<?php
				$my_query = new WP_Query( array( 'post_type' => 'portfolio', "nopaging"=>true ) );
				while ($my_query->have_posts()) : $my_query->the_post(); ?>
                
				<li class="post-stub"><a rel="<?php the_permalink(); ?>" id="<?php the_id(); ?>" title="<?php echo( basename( get_permalink() ) ); ?>"><h4 class="post-stub-title"><?php the_title(); ?></h4> <span class="post-stub-date"><?php the_time('F Y') ?>  <?php
$portfolio_cat = get_the_terms( $post->ID, 'portfolio_category' ); if ( $portfolio_cat && ! is_wp_error( $portfolio_cat ) ) : $portfolio_cat = array_values($portfolio_cat); echo '<span class="category"> ('. $portfolio_cat[0]->name .') </span>' ?><?php endif; ?></span></a></li>
				<?php endwhile; wp_reset_postdata(); ?>
            </ol>
		</div>

	</div>
</div>

</div>

<?php get_footer() ?>