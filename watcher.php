<?php

use Internexus\Watcher\Config;
use Internexus\Watcher\Watcher;

require 'vendor/autoload.php';

class Watcher_Plugin {

	protected $watcher;

	protected $options;

	public function __construct() {
		$this->options = $this->get_options();

		if ( ! empty( $this->options['apikey'] ) && $this->options['enabled'] ) {
			$this->setup();
		}
	}

	private function get_options() {
		$defaults = array(
			'apikey' => null,
			'enabled' => false,
			'wp_admin' => false
		);

		return array_merge( $defaults, get_option( '_wt-settings' ) );
	}

	private function setup() {
		$config = new Config( $this->options['apikey'] );
		$this->watcher = new Watcher( $config );

		add_action( 'plugins_loaded', array( $this, 'start_transaction' ), 1 );
		add_action( 'template_redirect', array( $this, 'redirect' ) );
	}

	public function start_transaction()  {
		if ( ! $this->options['wp_admin'] && is_admin() ) {
			return;
		}

		$name = $_SERVER['REQUEST_METHOD'] . ' /' . trim( $_SERVER['REQUEST_URI'], '/' );

		$this->watcher->transaction( $name )->setResult( 200 );
	}

	public function redirect() {
		if ( is_404() ) {
			$this->watcher->current()->setResult( 404 );
		}
	}

	public function error_handler ( $code, $message, $file, $line, $context ) {
		//
	}

	public function exception_handler ( $e ) {
		$this->watcher->reportException( $e );
	}
}

$_watcher = new Watcher_Plugin();

set_error_handler( array( $_watcher, 'error_handler' ) );
set_exception_handler( array( $_watcher, 'exception_handler' ) );
