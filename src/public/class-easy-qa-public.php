<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link http://emptyset.co
 * @since 1.0.0
 *
 * @package Easy_QA
 * @subpackage Easy_QA/public
 * @author jason@emptyset.co
 */
class Easy_QA_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = get_option( 'advanced_qa_options' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'font-awesome', sprintf( '%s://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', is_ssl() ? 'http' : 'http' ), null, $this->version );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-qa.css', array('font-awesome'), $this->version, 'all' );
	}

	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( 'recaptcha', sprintf( '%s://www.google.com/recaptcha/api.js?onload=easyQaRecaptchaOnloadCallback&render=explicit', is_ssl() ? 'https' : 'http' ), null, $this->version, false );
		wp_register_script( 'isotope', plugin_dir_url( __FILE__ ) . 'js/lib/isotope.pkgd.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'star-rating', plugin_dir_url( __FILE__ ) . 'js/lib/star-rating.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'easy-qa-ratings', plugin_dir_url( __FILE__ ) . 'js/easy-qa-ratings.js', array( 'jquery', 'star-rating'), $this->version, false );
		wp_register_script( 'easy-qa-search-form', plugin_dir_url( __FILE__ ) . 'js/easy-qa-search-form.js', array( 'jquery', 'recaptcha'), $this->version, false );

		wp_register_script( 'sharethis', sprintf('%s://w.sharethis.com/button/buttons.js', is_ssl() ? 'https' : 'http' ), array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-qa.js', array( 'jquery', 'easy-qa-ratings', 'isotope', 'easy-qa-search-form', 'sharethis' ), $this->version, true );

		// localize script with php data
		wp_localize_script( $this->plugin_name, 'easy_qa_php_vars', array(
			'easy_qa_ajaxurl' => admin_url( 'admin-ajax.php' ),
			'easy_qa_recaptcha_sitekey' => $this->options['recaptcha_sitekey'],
			'easy_qa_sharethis_publisher_key' => $this->options['sharethis_publisher_key'],
			'easy_qa_sharethis_providers_code' => $this->options['sharethis_providers_code']
		));
	}

	public function init_hooks() {
		$this->add_actions();
		$this->add_filters();
		$this->add_shortcodes();
	}

	public function add_actions() {
		add_action( 'wp_ajax_add_easy_qa_question', array( &$this, 'add_question' ) );
		add_action( 'wp_ajax_nopriv_add_easy_qa_question', array( &$this, 'add_question' ) );

		add_action( 'wp_ajax_post_easy_qa_rating', array( &$this, 'post_rating' ) );
		add_action( 'wp_ajax_nopriv_post_easy_qa_rating', array( &$this, 'post_rating' ) );
	}

	public function add_filters() {
		add_filter( 'template_include', array( &$this, 'load_custom_template' ) );
		add_filter( 'pre_get_posts', array( &$this, 'search_filter' ) );
	}

	public function add_shortcodes() {
		add_shortcode( 'easy_qa', array( &$this, 'easy_qa_callback' ) );
	}

	public function add_question() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-easy-qa-question.php';

		$qa_question = new Easy_QA_Question();
		$response = $qa_question->create_question($_POST['easy_qa_ask']);

		echo json_encode($response);
		die();
	}

	public function post_rating() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-easy-qa-rating.php';

		$qa_question = new Easy_QA_Rating();
		$response = $qa_question->create_rating($_POST['easy_qa_rating']);

		echo json_encode($response);
		die();
	}

	public function search_filter($query) {
		if (isset($_GET['post_type']) && $_GET['post_type'] == 'easy_qa_question') {
			$post_type = 'easy_qa_question';
		} else {
			$post_type = 'any';
		}
		if ($query->is_search) {
			$query->set('post_type', $post_type);
		};
		return $query;
	}

	public function load_custom_template( $template ) {

		if ( is_single() && get_post_type( get_the_ID() ) == 'easy_qa_question' ) {
			return get_easy_qa_template_path( 'single' );
		} elseif ( ( is_archive() || is_search() ) && (get_query_var( 'post_type' ) == 'easy_qa_question' ) ) {
			return get_easy_qa_template_path( 'archive-qa-question' );
		}

		return $template;
	}

	public function easy_qa_callback( $atts ) {
		$atts = shortcode_atts( array(
			'partials' => ''
		), $atts, 'easy_qa' );

		$atts['partials'] = array_filter( array_map( 'trim', explode( '|', $atts['partials'] ) ) );

		return $this->render($atts);
	}

	private function render($atts) {
		$content = '';

		if ( is_array( $atts ) && isset( $atts['partials'] ) && is_array( $atts['partials'] ) ) {

			$partials = $atts['partials'];

			if ( count($partials) > 0 ) {
				foreach( $partials as $partial ) {
					$content .= easy_qa_include_file( get_easy_qa_partial_path( $partial ) );
				}

				return $content;
			}

		}

		return $content;
	}

}
