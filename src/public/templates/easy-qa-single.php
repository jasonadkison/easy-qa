<?php
/**
 * Single Question Template
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/templates
 * @author jason@emptyset.co
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			<?php
			$name = get_easy_qa_question_field( get_the_ID(), 'name' );
			$location = get_easy_qa_question_field( get_the_ID(), array( 'city', 'state' ), ', ' );
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_post_thumbnail( 'sparkling-featured', array( 'class' => 'single-featured' )); ?>

				<div class="post-inner-content">

					<?php echo do_shortcode( '[easy_qa partials="search-form"]' ); ?>

					<header class="entry-header page-header">

						<blockquote>
							<p><?php the_title(); ?></p>

							<?php if ( $name || $location ) : ?>
								<footer>
									<!-- author name -->
									<?php if ( $name ) : ?>
										<?php echo $name; ?>
									<?php endif; ?>

									<!-- author location -->
									<?php if ( $location ) : ?>
									from <cite title="Location"><?php echo $location; ?></cite>
									<?php endif; ?>
								</footer>
							<?php endif; ?>
						</blockquote>

						<?php edit_post_link( __( 'Edit', 'easy-qa' ), '<i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span>' ); ?>

					</header>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

				</div><!-- /.post-inner-content -->


				<div class="post-inner-content secondary-content-box">
					<?php echo do_shortcode( '[easy_qa partials="sharethis"]'); ?>
					<?php echo do_shortcode( '[easy_qa partials="ratings"]' ); ?>
				</div><!-- /.secondary-content-box -->

			</article>


			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) :
					comments_template();
				endif;
			?>

			<?php
			if ( function_exists( 'easy_qa_paging_nav' ) ) {
				easy_qa_post_nav();
			}
			?>

		<?php endwhile; // end of the loop. ?>

	</main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>