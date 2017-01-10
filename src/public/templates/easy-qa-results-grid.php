<?php
/**
 * Question Results & Grid Template
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/templates
 * @author jason@emptyset.co
 */

global $wp_query;

$term = $wp_query->query_vars['term'];
$keyword = get_query_var( 's' );
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$args = array(
  'post_type' => 'easy_qa_question',
  'post_status' => array( 'publish' ),
  'posts_per_page' => get_site_option( 'posts_per_page' ),
  'paged' => $paged
);

if ( $term ) {
  $args['tax_query'] = array(
    array(
      'taxonomy' => 'easy_qa_topic',
      'field' => 'slug',
      'terms' => $term,
    )
  );
}

if ( strlen( $keyword ) ) {
  $args['s'] = $keyword;
}

if ( $keyword ) {
  $title = sprintf( '%s %s',
    translate( 'Search Results', 'easy-qa' ),
    $paged > 1 ? 'Page ' . $paged : ''
  );
} else {
  $title = single_cat_title( '', false );
}

query_posts( $args );
?>

<?php if ( have_posts() ) : ?>

  <?php if ( ( is_archive() || $keyword ) && $title ): ?>
    <header id="easy-qa easy-qa-header" class="entry-header">

      <?php if ($title) : ?>
        <h1 class="entry-title">
          <?php echo $title; ?>
        </h1>
      <?php endif; ?>

      <?php if ( $term || $keyword ) : ?>
        <p class="col-xs-12">
          <strong>
            <?php if ( $keyword ) : ?>
              <?php printf('%d %s contain the phrase "<em>%s</em>".',
                $wp_query->found_posts,
                ( $wp_query->found_posts == 1 ? translate( 'Question', 'easy-qa' ) : translate( 'Questions', 'easy-qa' ) ),
                $keyword
              ); ?>
            <?php endif; ?>
            <?php if ( $term ) : ?>
              <?php printf('%d %s found under "<em>%s</em>".',
                $wp_query->found_posts,
                ( $wp_query->found_posts == 1 ? translate( 'Question', 'easy-qa' ) : translate( 'Questions', 'easy-qa' ) ),
                single_cat_title( '', false )
              ); ?>
            <?php endif; ?>
          </strong>
        </p>
      <?php endif; ?>

    </header>
  <?php endif; ?>

  <section class="easy-qa-results">

    <?php while ( have_posts() ) : ?>
      <?php
        the_post();
        $name = get_easy_qa_question_field( get_the_ID(), 'name' );
        $location = get_easy_qa_question_field( get_the_ID(), array( 'city', 'state' ), ', ' );
      ?>

      <div class="panel panel-default">

        <div class="panel-heading">
          <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
            <?php the_title(); ?>
          </a>
        </div>

        <div class="panel-footer">
          <span class="author-name">
            <span class="glyphicon glyphicon-user"></span>
            <?php echo $name ? $name : "Anonymous"; ?>
          </span>
          <span class="author-location">
            <span class="glyphicon glyphicon-map-marker"></span>
            <?php echo $location ? $location : "Unknown Location"; ?>
          </span>
        </div>

      </div>

    <?php endwhile; ?>

  </section>

  <div class="row">
    <div class="col-xs-12">
      <?php echo easy_qa_paginate_links(); ?>
    </div>
  </div>

<?php else : ?>

  <section class="no-results not-found">
    <div class="page-content">
      <?php if ( is_search() ): ?>
        <p>
          Sorry, but nothing matched your search terms. Please try again with some different
          keywords.
        </p>
      <?php else : ?>
        <p>There were no questions found.</p>
      <?php endif; ?>
    </div>
  </section>

<?php endif; ?>


<?php wp_reset_query(); ?>
