<?php
/**
 * Question specific functionality.
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/includes
 * @author jason@emptyset.co
 */
class Easy_QA_Question {

  public function register_taxonomy() {

    $labels = array(
      'name'              => _x( 'QA Topics', 'taxonomy general name', 'easy-qa' ),
      'singular_name'     => _x( 'QA Topic', 'taxonomy singular name', 'easy-qa' ),
      'search_items'      => __( 'Search QA Topics', 'easy-qa' ),
      'all_items'         => __( 'All QA Topics', 'easy-qa' ),
      'parent_item'       => __( 'Parent QA Topic', 'easy-qa' ),
      'parent_item_colon' => __( 'Parent QA Topic:', 'easy-qa' ),
      'edit_item'         => __( 'Edit QA Topic', 'easy-qa' ),
      'update_item'       => __( 'Update QA Topic', 'easy-qa' ),
      'add_new_item'      => __( 'Add New QA Topic', 'easy-qa' ),
      'new_item_name'     => __( 'New QA Topic Name', 'easy-qa' ),
      'menu_name'         => __( 'QA Topics', 'easy-qa' )
    );

    $args = array(
      'labels' => $labels,
      'show_admin_column' => true,
      'rewrite' => array( 'slug' => 'qa-topics', 'with_front' => true ),
      'hierarchical' => true
    );

    register_taxonomy( 'easy_qa_topic', 'easy_qa_question', $args );
  }

  /**
   * Registers the easy_qa_question custom post type.
   */
  public function register_custom_post_type() {

    $labels = array(
      'name'               => _x( 'Questions', 'post type general name', 'easy-qa' ),
      'singular_name'      => _x( 'Question', 'post type singular name', 'easy-qa' ),
      'menu_name'          => _x( 'Questions', 'admin menu', 'easy-qa' ),
      'name_admin_bar'     => _x( 'Question', 'add new on admin bar', 'easy-qa' ),
      'add_new'            => _x( 'Add New', 'book', 'easy-qa' ),
      'add_new_item'       => __( 'Add New Question', 'easy-qa' ),
      'new_item'           => __( 'New Question', 'easy-qa' ),
      'edit_item'          => __( 'Edit Question', 'easy-qa' ),
      'view_item'          => __( 'View Question', 'easy-qa' ),
      'all_items'          => __( 'All Questions', 'easy-qa' ),
      'search_items'       => __( 'Search Questions', 'easy-qa' ),
      'parent_item_colon'  => __( 'Parent Questions:', 'easy-qa' ),
      'not_found'          => __( 'No books found.', 'easy-qa' ),
      'not_found_in_trash' => __( 'No books found in Trash.', 'easy-qa' )
    );

    $args = array(
      'labels'              => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'menu_icon'          => 'dashicons-testimonial',
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'question' ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      'menu_position'      => null,
      'supports'           => array( 'title', 'thumbnail', 'editor', 'custom-fields', 'page-attributes', 'comments', 'revisions' ),
      'taxonomies'         => array( 'easy_qa_topic' )
    );

    register_post_type( 'easy_qa_question', $args );
  }

  public function register_filters() {
    add_filter( 'enter_title_here', array( &$this, 'change_title_text' ) );

    add_filter( 'clean_url', array( &$this, 'clean_script_urls' ) );
  }

  public function change_title_text( $title ){
    $screen = get_current_screen();

    if ( 'easy_qa_question_question' == $screen->post_type ) {
        $title = 'Enter question here...';
    }

    return $title;
  }

  public function clean_script_urls( $url ) {
    if ( !strpos( $url, 'recaptcha/api.js' ) ) {
      return $url;
    }

    return "$url' async defer='defer";
  }

  /**
   * Creates a new question with initial meta data as a draft post.
   *
   * @param array $data the list containing the question, name, email, city and state values
   * @return array the new post_ID, response list of status codes and error messages.
   * @access public
   */
  public function create_question($data = array()) {
    $status = 200;
    $errors = array();
    $message = 'Your Question has been asked! You will receive email notification when it gets answered.';
    $required_fields = array( 'question', 'name', 'email', 'city', 'state' );

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
      $message = 'One or more missing fields prevented question from being saved.';
    }

    // check required fields are not empty
    if ( $status == 200 ) {
      foreach ($required_fields as $name) {
        if ( !is_string( $data[$name] ) || strlen( trim( $data[$name] ) ) < 1 ) {
          array_push( $errors, array(
            'field' => $name,
            'error' => ucfirst( $name ) . ' is required.'
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
      $question = $data;

      // trim all the question values
      foreach ( $question as $i => $value ) {
        $question[$i] = trim( $value );
      }

      // trim the question text to 140 characters
      $question['question'] = substr( $question['question'], 0, 140 );

      // create the post
      $post_ID = wp_insert_post( array(
        'post_title' => $question['question'],
        'post_content' => "Insert answer here. Then select a topic and manage additional settings below.",
        'post_status' => 'draft',
        'post_type' => 'easy_qa_question'
      ), false );

      if ( $post_ID > 0 ) {

        $meta_saves = array();

        // save the meta data
        $meta_saves[] = add_post_meta($post_ID, 'question[question]', $question['question'], true);
        $meta_saves[] = add_post_meta($post_ID, 'question[name]', $question['name'], true);
        $meta_saves[] = add_post_meta($post_ID, 'question[email]', $question['email'], true);
        $meta_saves[] = add_post_meta($post_ID, 'question[city]', $question['city'], true);
        $meta_saves[] = add_post_meta($post_ID, 'question[state]', $question['state'], true);

      } else {
        $status = 422;
        $message = 'Question failed to save.';
      }
    }

    return array(
      'status' => $status,
      'data' => $data,
      'post_ID' => $post_ID && $post_ID > 0 ? $post_ID : null,
      'errors' => $errors,
      'message' => $message
    );
  }

}
