<?php
/**
 *
 * @link http://emptyset.co/wordpress/easy-qa
 * @since 1.0.0
 * @package Easy_QA
 * @subpackage Easy_QA/admin
 * @author jason@emptyset.co
 */
class Easy_QA_Admin {

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
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-qa-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-qa-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add options page
	 */
	public function add_options_page() {
		// This page will be under "Settings"
		add_options_page(
				'Easy Q&A Settings', // page title
				'Easy Q&A', // menu title
				'manage_options', // capability
				'easy-qa-admin', // menu slug
				array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'easy_qa_options' );
		?>
		<div class="wrap">
			<h2>Easy QA Settings</h2>
			<form method="post" action="options.php">
			<?php
					// This prints out all hidden setting fields
					settings_fields( 'easy_qa_option_group' );
					do_settings_sections( 'easy-qa-admin' );
					submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function admin_page_init() {
		register_setting(
				'easy_qa_option_group', // Option group
				'easy_qa_options', // Option name
				array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
				'easy_qa_globals', // ID
				'Global Settings', // Title
				array( $this, 'print_section_info' ), // Callback
				'easy-qa-admin' // Page
		);

		add_settings_field(
				'recaptcha_sitekey',
				'Recaptcha sitekey',
				array( &$this, 'recaptcha_sitekey_callback' ),
				'easy-qa-admin',
				'easy_qa_globals'
		);

		add_settings_field(
				'sharethis_publisher_key',
				'ShareThis publisher key',
				array( &$this, 'sharethis_publisher_key_callback' ),
				'easy-qa-admin',
				'easy_qa_globals'
		);

		add_settings_field(
				'sharethis_providers_code',
				'ShareThis providers code',
				array( &$this, 'sharethis_providers_code_callback' ),
				'easy-qa-admin',
				'easy_qa_globals'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
			$new_input = array();

			if( isset( $input['recaptcha_sitekey'] ) )
					$new_input['recaptcha_sitekey'] = sanitize_text_field( $input['recaptcha_sitekey'] );

			if( isset( $input['sharethis_publisher_key'] ) )
					$new_input['sharethis_publisher_key'] = sanitize_text_field( $input['sharethis_publisher_key'] );

			if( isset( $input['sharethis_providers_code'] ) )
					$new_input['sharethis_providers_code'] = $input['sharethis_providers_code'];

			return $new_input;
	}

	/** 
	 * Print the Section text
	 */
	public function print_section_info() {
			//print 'Enter your settings below:';
	}

	/**
	 * Print the Recaptcha Sitekey field.
	 */
	public function recaptcha_sitekey_callback() {
		printf(
			'<input type="text" id="recaptcha_sitekey" name="easy_qa_options[recaptcha_sitekey]" value="%s" />',
			isset( $this->options['recaptcha_sitekey'] ) ? esc_attr( $this->options['recaptcha_sitekey'] ) : ''
		);
	}

	/**
	 * Print the ShareThis publisher key field.
	 */
	public function sharethis_publisher_key_callback() {
		printf(
			'<input type="text" id="sharethis_publisher_key" name="easy_qa_options[sharethis_publisher_key]" value="%s" />',
			isset( $this->options['sharethis_publisher_key'] ) ? esc_attr( $this->options['sharethis_publisher_key'] ) : ''
		);
	}

	/**
	 * Print the ShareThis provider codes field.
	 */
	public function sharethis_providers_code_callback() {
		printf(
			'<textarea id="sharethis_providers_code" name="easy_qa_options[sharethis_providers_code]" rows="5">%s</textarea>',
			isset( $this->options['sharethis_providers_code'] ) ? $this->options['sharethis_providers_code'] : ''
		);
	}

}
