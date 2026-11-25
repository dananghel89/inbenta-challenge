<?php
/**
 * CoreConfig class.
 */

use Dotenv\Dotenv as Vars;

class CoreConfig {

	// Config properties.
	static $app;
	static $inbenta;

	// Config constructor.
	public function __construct() {
		if ( file_exists(ABSPATH . '.env') ) {
			$dotenv = Vars::createImmutable(ABSPATH);
			$dotenv->load();
		}

		$this->app();
		$this->inbenta();
	}

	// Config app method.
	private function app() {
		self::$app = (object) [
			'ver'  => $this->env('APP_VER', '2.20'),
			'name' => $this->env('APP_NAME', 'Core'),
			'env'  => $this->env('APP_ENV', 'local'),
			'url'  => $this->env('APP_URL', 'http://localhost'),
		];
	}

	// Config app method.
	private function inbenta() {
		self::$inbenta = (object) [
			'app_key'    => $this->env('INBENTA_APP_KEY'),
			'app_secret' => $this->env('INBENTA_APP_SECRET'),
		];
	}

	// Config inbenta method.
	private function env($key, $default = null) {
		$value = getenv($key);

		if ( $value === false ) {
			return $default;
		}

		return $value;
	}

}
