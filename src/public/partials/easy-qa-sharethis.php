<?php
/**
 * Sharethis Partial
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/public/partials
 * @author jason@emptyset.co
 */
$options = get_option( 'easy_qa_options' );
$providers_code = is_array( $options ) && isset( $options['sharethis_providers_code'] ) ? $options['sharethis_providers_code'] : '';
?>

<!-- Begin Easy QA Sharethis -->

<div class="easy-qa easy-qa-sharethis">
	<h3>Share this Question</h3>
	<?php if ( $providers_code ) : ?>
		<?php echo $providers_code; ?>
	<?php else : ?>
		<span class='st_sharethis_large' displayText='ShareThis'></span>
		<span class='st_facebook_large' displayText='Facebook'></span>
		<span class='st_twitter_large' displayText='Tweet'></span>
		<span class='st_googleplus_large' displayText='Google +'></span>
		<span class='st_linkedin_large' displayText='LinkedIn'></span>
		<span class='st_blogger_large' displayText='Blogger'></span>
		<span class='st_pinterest_large' displayText='Pinterest'></span>
		<span class='st_email_large' displayText='Email'></span>
	<?php endif; ?>
</div>

<!-- End Easy QA Sharethis -->