<?php
/**
 * The Template for displaying all single posts.
 *
 * @package hq
 */

get_header(); ?>

	<div class="large-9 columns">
		<div class="panel">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'single' ); ?>

			<?php hq_content_nav( 'nav-below' ); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>

		</div><!-- panel -->
	</div><!-- column -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>