<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package hq
 */
?>

	</div><!-- #content -->
	<div class="row">
	<div class="large-12 columns">
		<div class="panel">
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'hq_credits' ); ?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'hq' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'hq' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s', 'hq' ), 'hq' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- panel -->
	</div>
</div><!-- row -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>