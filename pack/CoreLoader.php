<?php
/**
 * CoreLoader class.
 */

use NoahBuscher\Macaw\Macaw as Route;

class CoreLoader {

	// Loader constructor.
	public function __construct() {
		$this->init();
		$this->routes();
	}

	// Loader init method.
	private function init() {
		$const = [
			'CORE_APP'    => 'app',
			'CORE_DIR'    => 'pack',
			'CORE_ROUTES' => 'routes',
			'CORE_VIEWS'  => 'views',
		];

		foreach ( $const as $key => $value ) {
			if ( !defined($key) ) {
				define($key, ABSPATH . $value . DIRECTORY_SEPARATOR);
			}
		}

		date_default_timezone_set('UTC');

		require CORE_DIR . 'CoreConfig.php';
		new CoreConfig();

		require CORE_DIR . 'helpers.php';

		require CORE_APP . 'BotController.php';
		require CORE_APP . 'InbentaAPI.php';
		require CORE_APP . 'StarWarsAPI.php';
	}

	// Loader routes method.
	private function routes() {
		session_start();

		require CORE_ROUTES . 'web.php';
		Route::dispatch();
	}

}
