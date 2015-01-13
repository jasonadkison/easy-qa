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

global $post;
global $query;
$template = 'results-grid';

if ( function_exists( 'get_easy_qa_template' ) ) {
	echo get_easy_qa_template( $template );
}