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

$taxonomy = 'easy_qa_topic';
$tax_terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
?>

<!-- Begin Easy QA Topics List -->

<div class="easy-qa easy-qa-topics">

	<ul>
		<?php foreach ($tax_terms as $tax_term) : ?>
			<li>
				<a href="<?php echo esc_attr( get_term_link( $tax_term, $taxonomy ) ) ?>" title="<?php printf( 'View all %s Questions', $tax_term->name ); ?>">
					<?php echo $tax_term->name; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

</div>

<!-- End Easy QA Topics List -->