    <footer class="footer">
        <div class="container">
            <div class="site-title-wrapper">
                <h1 class="site-title"><a class="js-ajax-link" href="<?php echo home_url(); ?>"><?php echo get_bloginfo('name'); ?></a></h1>

                <a class="button-square button-jump-top js-jump-top" href="#"><i class="icon-up-open"></i></a>
            </div>
			<?php if (get_option('glidingghost_credits') ==''); ?><?php echo ('<p class="footer-copyright">'.get_option('glidingghost_credits').'</p>'); ?>

        </div>
    </footer>

<?php wp_footer(); ?>
	
</body>
</html>
