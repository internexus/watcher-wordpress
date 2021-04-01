<?php

class Settings_Page {

	const PLUGIN_SLUG = 'watcher-plugin';

	const OPTION_NAME = '_wt-settings';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	public function setup_menu() {
		add_menu_page( 'Watcher', 'Watcher', 'manage_options', self::PLUGIN_SLUG, array( $this, 'admin_page' ), 'dashicons-dashboard' );
	}

	public function admin_page() {
		$this->options = get_option( self::OPTION_NAME );

		include 'template.php';
	}

	public function page_init() {
		register_setting( self::PLUGIN_SLUG, self::OPTION_NAME );

		add_settings_section(
            'wt_settings_section', // ID
            'Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            self::PLUGIN_SLUG // Page
        );

		add_settings_field(
            'apikey', // ID
            'API Key', // Title
            array( $this, 'apikey_callback' ), // Callback
            self::PLUGIN_SLUG, // Page
            'wt_settings_section' // Section
        );
	}

	public function print_section_info()
    {
        print 'Enter your settings below:';
    }

	public function apikey_callback() {
		printf(
			'<input name="%s[apikey]" type="text" id="apikey" class="regular-text" value="%s"',
			self::OPTION_NAME,
			$this->options['apikey'] ?? null
		);
	}
}

if ( is_admin() ) {
    new Settings_Page();
}
