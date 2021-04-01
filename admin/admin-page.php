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

	public function sanitize( $input ) {
        $new_input = array();

        if( isset( $input['enabled'] ) )
            $new_input['enabled'] = boolval( $input['enabled'] );

        if( isset( $input['wp_admin'] ) )
            $new_input['wp_admin'] = boolval( $input['wp_admin'] );

        if( isset( $input['apikey'] ) )
            $new_input['apikey'] = sanitize_text_field( $input['apikey'] );

        return $new_input;
    }

	public function page_init() {
		register_setting( self::PLUGIN_SLUG, self::OPTION_NAME, array( $this, 'sanitize' ) );

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

		add_settings_field(
            'enabled', // ID
            'Enabled', // Title
            array( $this, 'enabled_callback' ), // Callback
            self::PLUGIN_SLUG, // Page
            'wt_settings_section' // Section
        );

		add_settings_field(
            'wp_admin', // ID
            'WP Admin', // Title
            array( $this, 'wp_admin_callback' ), // Callback
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

	public function enabled_callback() {
		printf(
			'<input name="%s[enabled]" type="checkbox" id="enabled" value="%s" %s> Enable Watcher collect data and send to remote <abbr title="Internexus Watcher Server">server</abbr>',
			self::OPTION_NAME,
			$this->options['enabled'] == '' ? 'true' : 'false',
			$this->options['enabled'] == 'true' ? 'checked' : ''
		);
	}

	public function wp_admin_callback() {
		printf(
			'<input name="%s[wp_admin]" type="checkbox" id="wp_admin" value="%s" %s> Collect data from <i>/wp-admin</i>',
			self::OPTION_NAME,
			$this->options['wp_admin'] == '' ? 'true' : 'false',
			$this->options['wp_admin'] == 'true' ? 'checked' : ''
		);
	}
}

if ( is_admin() ) {
    new Settings_Page();
}
