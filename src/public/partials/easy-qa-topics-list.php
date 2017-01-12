<?php
/**
 * Topics List Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */

$terms = get_terms( 'easy_qa_topic', array( 'hide_empty' => false ) );
?>

<!-- Begin Easy QA Topics List -->

<div class="easy-qa-topics">

  <div class="list-group">
    <?php foreach ($terms as $term) : ?>
      <a class="list-group-item" href="<?php echo get_term_link($term); ?>" title="<?php printf( 'View all %s Questions', $term->name ); ?>">
        <span class="badge"><?php echo $term->count; ?></span>
        <?php echo $term->name; ?>
      </a>
    <?php endforeach; ?>
  </div>

</div>

<!-- End Easy QA Topics List -->
