<?php
/**
 * Question Archives Template
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/templates
 * @author jason@emptyset.co
 */
get_header();
?>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo do_shortcode( '[easy_qa partials="search-form"]' ); ?>

			<?php if ( function_exists( 'get_easy_qa_template' ) ) : ?>
				<?php echo get_easy_qa_template( 'results-grid' ); ?>
			<?php endif; ?>
		</article>

	</main>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>