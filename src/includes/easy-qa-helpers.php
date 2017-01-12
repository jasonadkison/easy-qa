<?php

// Reads file into buffer and returns it
if ( !function_exists( 'easy_qa_include_file ') ) {
  function easy_qa_include_file( $filename ) {
    if ( is_file( $filename ) ) {
        ob_start();
        include $filename;
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    return false;
  }
}

// Fetches a question's custom meta value by field name
if ( !function_exists( 'get_easy_qa_question_field' ) ) {
  function get_easy_qa_question_field( $post_ID = null, $field, $delimiter = null ) {
    if ( is_array( $field ) ) {

      $values = array();

      for( $i = 0; $i < count( $field ); $i++ ) {
        $values[] = get_easy_qa_question_field( $post_ID, $field[$i] );
      }

      return implode( $delimiter, array_filter( $values ) );
    }

    if ( substr( $field, 0, 9 ) !== 'question[' ) {
      $field = sprintf('question[%s]', $field);
    }

    $value = get_post_meta( $post_ID, $field, true );

    return $value ? $value : '';
  }
}

if ( !function_exists( 'get_easy_qa_template_path' ) ) {
  function get_easy_qa_template_path( $template ) {
    $template = strtolower( substr( $template, -4 ) ) !== '.php' ? $template . '.php' : $template;

    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ( $file = locate_template( array( 'plugin_templates/easy-qa-' . $template ) ) ) {
      return $file;
    }

    return plugin_dir_path( dirname( __FILE__ ) ) . 'public/templates/easy-qa-' . $template;
  }
}

if ( !function_exists( 'get_easy_qa_partial_path' ) ) {
  function get_easy_qa_partial_path( $partial ) {
    $partial = strtolower( substr( $partial, -4 ) ) !== '.php' ? $partial . '.php' : $partial;

    // Check if a custom template exists in the theme folder, if not, load the plugin template file
    if ( $file = locate_template( array( 'plugin_partials/easy-qa-' . $partial ) ) ) {
      return $file;
    }

    return plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/easy-qa-' . $partial;
  }
}

if ( !function_exists( 'get_easy_qa_partial' ) ) {
  function get_easy_qa_partial( $partial ) {
    return easy_qa_include_file( get_easy_qa_partial_path( $partial ) );
  }
}

if ( !function_exists( 'get_easy_qa_template' ) ) {
  function get_easy_qa_template( $template ) {
    return easy_qa_include_file( get_easy_qa_template_path( $template ) );
  }
}
