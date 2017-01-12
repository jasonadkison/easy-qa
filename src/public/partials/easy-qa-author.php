<?php
/**
 * Author Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */

$name = get_easy_qa_question_field(get_the_ID(), 'name');
$location = get_easy_qa_question_field(get_the_ID(), array( 'city', 'state' ), ', ');
?>

<div class="author-text">
  <span class="author-name">
    <span class="glyphicon glyphicon-user"></span>
    <?php echo $name ? $name : "Anonymous"; ?>
  </span>
  <span class="author-location">
    <span class="glyphicon glyphicon-map-marker"></span>
    <?php echo $location ? $location : "Unknown Location"; ?>
  </span>
</div>
