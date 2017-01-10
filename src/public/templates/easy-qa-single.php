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

        <div class="post-inner-content">

          <div class="entry-content">

            <blockquote>
              <?php the_title(); ?>
            </blockquote>

            <?php if ( $name || $location ) : ?>
              <span>
                <!-- author name -->
                <?php if ( $name ) : ?>
                  <?php echo $name; ?>
                <?php endif; ?>

                <!-- author location -->
                <?php if ( $location ) : ?>
                from <cite title="Location"><?php echo $location; ?></cite>
                <?php endif; ?>
              </span>
            <?php endif; ?>

          </div>

          <div class="entry-content">
            <?php the_content(); ?>

            <?php edit_post_link( __( 'Edit', 'easy-qa' ), '<i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span>' ); ?>
          </div>

          <div class="entry-content">
            <?php echo do_shortcode( '[easy_qa partials="ratings"]' ); ?>
            <?php echo do_shortcode( '[easy_qa partials="sharethis"]'); ?>
          </div>

        </div>


        <?php
          // If comments are open or we have at least one comment, load up the comment template
          if ( comments_open() || '0' != get_comments_number() ) :
            comments_template();
          endif;
        ?>

      </article>

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
