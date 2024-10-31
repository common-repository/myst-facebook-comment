<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class MYST_Facebook_Comment_Settings {

	/**
	 * The single instance of MYST_Facebook_Comment_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'myst_fbc_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		$page = add_options_page( __( 'Facebook Comment', 'myst-facebook-comment' ) , __( 'Facebook Comment', 'myst-facebook-comment' ) , 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
	}

	/**
	 * Load settings JS & CSS
	 * @return void
	 */
	public function settings_assets () {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
		wp_enqueue_style( 'farbtastic' );
    	wp_enqueue_script( 'farbtastic' );

    	// We're including the WP media scripts here because they're needed for the image upload field
    	// If you're not including an image upload then you can leave this function call out
    	wp_enqueue_media();

    	wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array( 'farbtastic', 'jquery' ), '1.0.0' );
    	wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'myst-facebook-comment' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['main'] = array(
			'title'					=> __( 'Main Setting', 'myst-facebook-comment' ),
			'description'			=> __( '', 'myst-facebook-comment' ),
			'fields'				=> array(
				array(
					'id' 			=> 'app_id',
					'label'			=> __( 'APP ID' , 'myst-facebook-comment' ),
					'description'	=> __( '<span style="color:red">** Required **</span> Your Facebook App ID', 'myst-facebook-comment' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( '0000000000000000', 'myst-facebook-comment' )
				),
				array(
					'id' 			=> 'include_sdk',
					'label'			=> __( 'Include Facebook Javascript SDK', 'myst-facebook-comment' ),
					'description'	=> __( 'Enable this if you have not include any other Facebook integration.', 'myst-facebook-comment' ),
					'type'			=> 'checkbox',
					'default'		=> true
				),
				array(
					'id' 			=> 'colour_scheme',
					'label'			=> __( 'Colour Scheme', 'myst-facebook-comment' ),
					'description'	=> __( '', 'myst-facebook-comment' ),
					'type'			=> 'radio',
					'options'		=> array( 'light' => 'Light', 'dark' => 'Dark' ),
					'default'		=> 'light'
				),
				array(
					'id' 			=> 'comment_responsive',
					'label'			=> __( 'Responsive', 'myst-facebook-comment' ),
					'description'	=> __( 'Disable this if you does not wished to have mobile-optimized version.', 'myst-facebook-comment' ),
					'type'			=> 'checkbox',
					'default'		=> true
				),
				array(
					'id' 			=> 'comment_width',
					'label'			=> __( 'Width' , 'myst-facebook-comment' ),
					'description'	=> __( 'Comment box width, keep 100% for responsive', 'myst-facebook-comment' ),
					'type'			=> 'text',
					'default'		=> '100%',
					'placeholder'	=> __( '550', 'myst-facebook-comment' )
				),
				array(
					'id' 			=> 'num_posts',
					'label'			=> __( 'Number of comments' , 'myst-facebook-comment' ),
					'description'	=> __( 'Number of comments to show by default. The minimum value is 1.', 'myst-facebook-comment' ),
					'type'			=> 'number',
					'default'		=> '5',
					'placeholder'	=> __( '10', 'myst-facebook-comment' )
				),
				array(
					'id' 			=> 'adjust_language',
					'label'			=> __( 'Adjust Language', 'myst-facebook-comment' ),
					'description'	=> __( 'Language for the comment box', 'myst-facebook-comment' ),
					'type'			=> 'select',
					'options'		=> array( 
						'af_ZA' => 'Afrikaans',
						'ar_AR' => 'Arabic',
						'az_AZ' => 'Azerbaijani',
						'be_BY' => 'Belarusian',
						'bg_BG' => 'Bulgarian',
						'bn_IN' => 'Bengali',
						'bs_BA' => 'Bosnian',
						'ca_ES' => 'Catalan',
						'cs_CZ' => 'Czech',
						'cy_GB' => 'Welsh',
						'da_DK' => 'Danish',
						'de_DE' => 'German',
						'el_GR' => 'Greek',
						'en_GB' => 'English (UK)',
						'en_PI' => 'English (Pirate)',
						'en_UD' => 'English (Upside Down)',
						'en_US' => 'English (US)',
						'eo_EO' => 'Esperanto',
						'es_ES' => 'Spanish (Spain)',
						'es_LA' => 'Spanish',
						'et_EE' => 'Estonian',
						'eu_ES' => 'Basque',
						'fa_IR' => 'Persian',
						'fb_LT' => 'Leet Speak',
						'fi_FI' => 'Finnish',
						'fo_FO' => 'Faroese',
						'fr_CA' => 'French (Canada)',
						'fr_FR' => 'French (France)',
						'fy_NL' => 'Frisian',
						'ga_IE' => 'Irish',
						'gl_ES' => 'Galician',
						'he_IL' => 'Hebrew',
						'hi_IN' => 'Hindi',
						'hr_HR' => 'Croatian',
						'hu_HU' => 'Hungarian',
						'hy_AM' => 'Armenian',
						'id_ID' => 'Indonesian',
						'is_IS' => 'Icelandic',
						'it_IT' => 'Italian',
						'ja_JP' => 'Japanese',
						'ka_GE' => 'Georgian',
						'km_KH' => 'Khmer',
						'ko_KR' => 'Korean',
						'ku_TR' => 'Kurdish',
						'la_VA' => 'Latin',
						'lt_LT' => 'Lithuanian',
						'lv_LV' => 'Latvian',
						'mk_MK' => 'Macedonian',
						'ml_IN' => 'Malayalam',
						'ms_MY' => 'Malay',
						'nb_NO' => 'Norwegian (bokmal)',
						'ne_NP' => 'Nepali',
						'nl_NL' => 'Dutch',
						'nn_NO' => 'Norwegian (nynorsk)',
						'pa_IN' => 'Punjabi',
						'pl_PL' => 'Polish',
						'ps_AF' => 'Pashto',
						'pt_BR' => 'Portuguese (Brazil)',
						'pt_PT' => 'Portuguese (Portugal)',
						'ro_RO' => 'Romanian',
						'ru_RU' => 'Russian',
						'sk_SK' => 'Slovak',
						'sl_SI' => 'Slovenian',
						'sq_AL' => 'Albanian',
						'sr_RS' => 'Serbian',
						'sv_SE' => 'Swedish',
						'sw_KE' => 'Swahili',
						'ta_IN' => 'Tamil',
						'te_IN' => 'Telugu',
						'th_TH' => 'Thai',
						'tl_PH' => 'Filipino',
						'tr_TR' => 'Turkish',
						'uk_UA' => 'Ukrainian',
						'vi_VN' => 'Vietnamese',
						'zh_CN' => 'Simplified Chinese (China)',
						'zh_HK' => 'Traditional Chinese (Hong Kong)',
						'zh_TW' => 'Traditional Chinese (Taiwan)'
					),
					'default'		=> 'en_US'
				)
			)
		);

		$settings['advanced'] = array(
			'title'					=> __( 'Advanced', 'myst-facebook-comment' ),
			'description'			=> __( '', 'myst-facebook-comment' ),
			'fields'				=> array(
				array(
					'id' 			=> 'add_moderator',
					'label'			=> __( 'Add Moderator' , 'myst-facebook-comment' ),
					'description'	=> __( 'By default, all admins of your app will also be able to moderate comments. Input Facebook User ID on this field with comma-seperated to add more moderator for this site facebook comment box', 'myst-facebook-comment' ),
					'type'			=> 'text',
					'default'		=> ''
				)
			)
		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) continue;

				// Add section to page
				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}
					
					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				if ( ! $current_section ) break;
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
			$html .= '<h1><i class="fa fa-facebook-square" aria-hidden="true"></i> ' . __( 'Facebook Comment Setting' , 'myst-facebook-comment' ) . '</h1>' . "\n";
			$html .= '<div class="myst_container-fluid">' . "\n";
				$html .= '<div class="myst_row">' . "\n";
					$html .= '<div class="myst_col-7">' . "\n";
						
						$html .= '<h2 style="color:#888888;">' . __( 'Replace Wordpress Comment Template into Facebook Comment Plugin' , 'myst-facebook-comment' ) . '</h2>' . "\n";
						
						$tab = '';
						if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
							$tab .= $_GET['tab'];
						}
			
						// Show page tabs
						if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {
			
							$html .= '<h2 class="nav-tab-wrapper">' . "\n";
			
							$c = 0;
							foreach ( $this->settings as $section => $data ) {
			
								// Set tab class
								$class = 'nav-tab';
								if ( ! isset( $_GET['tab'] ) ) {
									if ( 0 == $c ) {
										$class .= ' nav-tab-active';
									}
								} else {
									if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
										$class .= ' nav-tab-active';
									}
								}
			
								// Set tab link
								$tab_link = add_query_arg( array( 'tab' => $section ) );
								if ( isset( $_GET['settings-updated'] ) ) {
									$tab_link = remove_query_arg( 'settings-updated', $tab_link );
								}
			
								// Output tab
								$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";
			
								++$c;
							}
			
							$html .= '</h2>' . "\n";
							
							
						}
			
						$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";
			
							// Get settings fields
							ob_start();
							settings_fields( $this->parent->_token . '_settings' );
							do_settings_sections( $this->parent->_token . '_settings' );
							$html .= ob_get_clean();
			
							$html .= '<p class="submit">' . "\n";
								$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
								$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'myst-facebook-comment' ) ) . '" />' . "\n";
							$html .= '</p>' . "\n";
						$html .= '</form>' . "\n";
					$html .= '</div>' . "\n";
					$html .= '<div class="myst_col-4">' . "\n";
						
						$html .= '<h3><i class="fa fa-comments-o" aria-hidden="true"></i> ' . __( 'Frequently Asked Questions' , 'myst-facebook-comment' ) . '</h3>' . "\n";
						$html .= '<p><a href="https://developers.facebook.com/docs/apps/register" target="_blank">' . __( 'How do I create an Facebook App ID?' , 'myst-facebook-comment' ) . '</a></p>' . "\n";
						$html .= '<p><strong>Why is the comment box disappeared?</strong><br />' . "\n";
						$html .= 'Please double check if your APP ID is inserted correctly (16-digits). And, please check if Facebook Javascript SDK has been declared twice.</p>' . "\n";
						$html .= '<hr />' . "\n";
						$html .= '<h2>' . __( 'Created by' , 'myst-facebook-comment' ) . '</h3>' . "\n";
						$html .= '<div class="myst_about-author">' . "\n";
							$html .= '<a href="http://myst.my/" target="_blank"><img src="'.plugin_dir_url( __FILE__ ).'images/logo-myst-plugin.png" width="350" height="100" alt="Myst Studios" /></a>' . "\n";
							$html .= '<h3><a href="http://myst.my/" target="_blank">' . __( 'MYST Studios ( Plugin work )' , 'myst-facebook-comment' ) . '</a></h3>' . "\n";
							$html .= '<p><a href="http://myst.my/contact-us" target="_blank"><button class="button-secondary"><i class="fa fa-bug" aria-hidden="true"></i> Report Bug</button></a></p>' . "\n";
							
						$html .= '</div>' . "\n";
						$html .= '<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5CPDWQ48MB5XA" target="_blank"><button class="button-primary"><i class="fa fa-coffee" aria-hidden="true"></i> Buy us a coffee!</button></a></p>' . "\n";
						$html .= '<h4 style="text-align: left;">Version : '. $this->parent->_version. '</h4>' . "\n";
					$html .= '</div>' . "\n";
				$html .= '</div>' . "\n"; //END .row
				
				
			$html .= '</div>' . "\n"; // END .container-fluid
		$html .= '</div>' . "\n";

		echo $html;
	}

	


	/**
	 * Main MYST_Facebook_Comment_Settings Instance
	 *
	 * Ensures only one instance of MYST_Facebook_Comment_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see MYST_Facebook_Comment()
	 * @return Main MYST_Facebook_Comment_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()
	
}

