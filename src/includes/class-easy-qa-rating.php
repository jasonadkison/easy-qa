<?php

/**
 * Rating specific functionality.
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/includes
 * @author jason@emptyset.co
 */
class Easy_QA_Rating {

	/**
	 * Creates a new rating storing the rating in the post's meta.
	 *
	 * @param array $data the list containing the post_ID and rating values
	 * @return array response list of status codes and messages.
	 * @access public
	 */
	public function create_rating($data = array()) {
		$status = 200;
		$errors = array();
		$message = 'Rating successfully posted.';
		$required_fields = array( 'post_ID', 'rating' );

		// check required fields exist
		$missing_fields = array_diff( $required_fields, array_keys( $data ) );
		if ( count( $missing_fields ) > 0 ) {
			$status = 422;
			foreach ( $missing_fields as $name ) {
				array_push( $errors, array(
					'field' => $name,
					'error' => 'Missing field for ' . $name . '.'
				) );
			}
			$message = 'One or more missing fields prevented rating from being saved.';
		}

		// check required fields are numeric
		if ( $status == 200 ) {
			foreach ($required_fields as $name) {
				if ( !is_numeric( $data[$name] ) ) {
					array_push( $errors, array(
						'field' => $name,
						'error' => ucfirst( $name ) . ' must be a valid number.'
					) );
				}
			}

			if ( count( $errors ) > 0 ) {
				$status = 422;
				$message = 'One or more fields contained an invalid value.';
			}
		}

		if ( $status == 200 ) {

			// copy $data for saving
			$rating = $data;

			$currentRatings = get_post_meta($rating['post_ID'], 'easy_qa_ratings', true);

			if ( is_array( $currentRatings ) ) {
				$currentRatings[] = $rating['rating'];
			} else {
				$currentRatings = array($rating['rating']);
			}

			// create the post
			if ( !update_post_meta( $rating['post_ID'], 'easy_qa_ratings', $currentRatings ) ) {
				$status = 422;
				$message = 'Rating failed to save.';
			}
		}

		return array(
			'status' => $status,
			'data' => $data,
			'errors' => $errors,
			'message' => $message
		);
	}

}