<?php
/**
 * Results Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */

$terms = get_terms( 'easy_qa_topic', array( 'hide_empty' => false ) );
?>

<section class="easy-qa-results">

  <div class="panel-group" id="topics-accordion" role="tablist" aria-multiselectable="true">
    <?php foreach ($terms as $idx => $term ): ?>
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading<?php echo $idx; ?>" data-toggle="collapse" data-parent="#topics-accordion" href="#qa-topic-<?php echo $term->slug ?>" aria-expanded="true" aria-controls="qa-topic-<?php echo $term->slug ?>">
          <h4 class="panel-title">
            <?php echo $term->name; ?>
            <span class="badge pull-right"><?php echo $term->count; ?></span>
          </h4>
        </div>
        <div id="qa-topic-<?php echo $term->slug ?>" class="panel-collapse collapse <?php echo $idx == 0 ? 'in' : ''; ?>" role="tabpanel" aria-labelledby="heading<?php echo $idx; ?>">
          <div class="list-group">
            <?php
            $query = new WP_Query( array(
              'post_type' => 'easy_qa_question',
              'post_status' => array( 'publish' ),
              'posts_per_page' => -1,
              'orderby' => 'menu_order',
              'order' => 'ASC',
              'tax_query' => array(
                array(
                  'taxonomy' => 'easy_qa_topic',
                  'field' => 'slug',
                  'terms' => $term->slug,
                )
              )
            ) );
            ?>
            <?php if ( $query->have_posts() ): ?>
              <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <a class="list-group-item" href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                  <?php the_title(); ?>
                </a>
              <?php endwhile; ?>
            <?php else: ?>
              <span class="list-group-item">No questions within this topic yet.</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($query->have_posts()) : ?>
    <?php while ($query->have_posts()) : $query->the_post(); ?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
            <?php the_title(); ?>
          </a>
        </div>
        <div class="panel-footer">
          <?php echo do_shortcode('[easy_qa partials="author"]'); ?>
        </div>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php endwhile; ?>
  <?php endif; ?>

</section>
