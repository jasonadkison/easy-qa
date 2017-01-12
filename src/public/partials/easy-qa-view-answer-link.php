<?php
/**
 * View Answer Link Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */
?>

<a class="btn btn-default" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
  <span class="glyphicon glyphicon-ok-sign"></span> View Answer
</a>
