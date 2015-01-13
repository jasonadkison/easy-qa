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

if ( !function_exists( 'easy_qa_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of questions when applicable.
	 *
	 * @return void
	 */
	function easy_qa_paging_nav() {
		global $wp_query;
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}
		?>
		<nav class="navigation paging-navigation" role="navigation">
			<div class="nav-links">

				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"> <?php next_posts_link( __( '<i class="fa fa-chevron-left"></i> Older questions', 'easy-qa' ) ); ?></div>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( __( 'Newer questions <i class="fa fa-chevron-right"></i>', 'easy-qa' ) ); ?> </div>
				<?php endif; ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'easy_qa_post_nav' ) ) :
/**
 * Display navigation to next/previous question within the same topic when applicable.
 *
 * @return void
 */
function easy_qa_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( true, '', true, 'qa_topic' );
	$next = get_adjacent_post( true, '', false, 'qa_topic' );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'easy-qa' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<i class="fa fa-chevron-left"></i> %title', 'Previous post link', 'easy-qa' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <i class="fa fa-chevron-right"></i>', 'Next post link',     'easy-qa' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( !function_exists( 'easy_qa_paginate_links ') ) {
	function easy_qa_paginate_links() {
		global $wp_query, $wp_rewrite;

		$total        = ( isset( $wp_query->max_num_pages ) ) ? $wp_query->max_num_pages : 1;
		$current      = ( get_query_var( 'paged' ) ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );
		$s = $_GET['s'];

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
			$query_args = urlencode_deep( $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		$args = array(
			'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
			'format' => $format, // ?page=%#% : %#% is replaced by the page number
			'total' => $total,
			'current' => $current,
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __('&laquo; Previous'),
			'next_text' => __('Next &raquo;'),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'list',
			'add_args' => $query_args, // array of query args to add
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
		);


		// Who knows what else people pass in $args
		$total = (int) $args['total'];
		if ( $total < 2 ) {
			return;
		}
		$current  = (int) $args['current'];
		$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
		if ( $end_size < 1 ) {
			$end_size = 1;
		}
		$mid_size = (int) $args['mid_size'];
		if ( $mid_size < 0 ) {
			$mid_size = 2;
		}
		$add_args = is_array( $args['add_args'] ) ? $args['add_args'] : false;
		$r = '';
		$page_links = array();
		$dots = false;

		if ( $args['prev_next'] && $current && 1 < $current ) :
			$link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current - 1, $link );
			if ( $add_args )
				$link = add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];

			/**
			 * Filter the paginated links for the given archive pages.
			 *
			 * @since 3.0.0
			 *
			 * @param string $link The paginated link URL.
			 */
			$page_links[] = '<a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
		endif;
		for ( $n = 1; $n <= $total; $n++ ) :
			if ( $n == $current ) :
				$page_links[] = "<span class='page-numbers current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</span>";
				$dots = true;
			else :
				if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
					$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
					$link = str_replace( '%#%', $n, $link );
					if ( $add_args )
						$link = add_query_arg( $add_args, $link );
					$link .= $args['add_fragment'];

					/** This filter is documented in wp-includes/general-template.php */
					$page_links[] = "<a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
					$dots = true;
				elseif ( $dots && ! $args['show_all'] ) :
					$page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
					$dots = false;
				endif;
			endif;
		endfor;
		if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) ) :
			$link = str_replace( '%_%', $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current + 1, $link );
			if ( $add_args )
				$link = add_query_arg( $add_args, $link );
			$link .= $args['add_fragment'];

			/** This filter is documented in wp-includes/general-template.php */
			$page_links[] = '<a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
		endif;
		switch ( $args['type'] ) {
			case 'array' :
				return $page_links;

			case 'list' :
				$r .= "<nav class='clearfix'>";
				$r .= "<ul class='pagination'>\n\t<li>";
				$r .= join("</li>\n\t<li>", $page_links);
				$r .= "</li>\n</ul>\n";
				$r .= "</nav>";
				break;

			default :
				$r = join("\n", $page_links);
				break;
		}
		return $r;
	}

}