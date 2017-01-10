<?php
/**
 * Ratings Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */

global $post;

$ratings = get_post_meta( $post->ID, 'easy_qa_ratings', true );
$average = is_array( $ratings ) ? ( round( array_sum( $ratings ) / count( $ratings ) ) ) : 0;
?>

<!-- Begin Easy QA Ratings -->

<div class="easy-qa-ratings">

  <div class="row">

    <div class="col-md-6">
      <span class="rating-label"><?php _e( 'Average Rating', 'easy-qa' ); ?></span>
      <div class="clearfix">
        <form>
          <input type="hidden" class="rating-stars" value="<?php echo $average; ?>">
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <span class="rating-label"><?php _e( 'Your Rating', 'easy-qa' ); ?></span>
      <div class="clearfix">
        <form>
          <input type="hidden" class="rating-stars" name="easy_qa_rating[rating]" value="">
          <input type="hidden" name="easy_qa_rating[post_ID]" value="<?php echo $post->ID; ?>">
          <input type="hidden" name="action" value="post_easy_qa_rating">
        </form>
      </div>
    </div>

  </div>

</div>

<!-- End Easy QA Ratings -->
