<?php
/*
Template Name: Table of Contents
*/
?>
<?php get_header(); ?>

<body class="tofc">

<a href="<?php echo home_url(); ?>" class="homelink"><span class="logo">X</span></a>

<div id="wrapper">
  <div id="postcontainer">
	<div id="post">


			<?php the_post(); $do_not_duplicate = get_the_ID(); ?>

  		<div class="container">
			<article class="post">
				<header class="post-header">
					<h1 class="post-title"><?php the_title(); ?></h1>
				</header>


		<div class="grid home-features">
		    <div class="grid-unit">
              <?php if (get_option('glidingghost_tofc_titleleft') ==''); ?><?php echo ('<h4>'.get_option('glidingghost_tofc_titleleft').'</h4>'); ?>
			  <?php wp_nav_menu( array( 'theme_location'  => 'tofc1', 'items_wrap' => '<ol id="%1$s" class="post-list %2$s">%3$s</ol>', 'walker' => new MV_Cleaner_Walker_Nav_Menu() ) ); ?>
            </div>
		    <div class="grid-unit">
                            <?php if (get_option('glidingghost_tofc_titleright') ==''); ?><?php echo ('<h4>'.get_option('glidingghost_tofc_titleright').'</h4>'); ?>
               <?php wp_nav_menu( array( 'theme_location'  => 'tofc2', 'items_wrap' => '<ol id="%1$s" class="post-list %2$s">%3$s</ol>', 'walker' => new MV_Cleaner_Walker_Nav_Menu() ) ); ?>
            </div>
		</div>
	
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
				<li class="post-stub"><a rel="<?php the_permalink(); ?>" id="<?php the_id(); ?>" title="<?php echo( basename( get_permalink() ) ); ?>"><h4 class="post-stub-title"><?php the_title(); ?></h4> <span class="post-stub-date"><?php the_time('F j, Y') ?></span></a></li>
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
                
				<li class="post-stub"><a rel="<?php the_permalink(); ?>" id="<?php the_id(); ?>" title="<?php echo( basename( get_permalink() ) ); ?>"><h4 class="post-stub-title"><?php the_title(); ?></h4> <span class="post-stub-date"><?php the_time('F Y') ?></span></a></li>
				<?php endwhile; wp_reset_postdata(); ?>
            </ol>
		</div>

	</div>
</div>


</div>

<?php get_footer() ?>