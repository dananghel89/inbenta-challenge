<?php

/* Public path to site directory. */
if ( !defined( 'PUBPATH' ) ) {
	define('PUBPATH', __DIR__ . DIRECTORY_SEPARATOR);
}

/* Absolute path to site directory. */
if ( !defined( 'ABSPATH' ) ) {
	define('ABSPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
}

/* Load dependencies file. */
require ABSPATH . 'vendor/autoload.php';

/* Load core application. */
require ABSPATH . 'pack/CoreLoader.php';
new CoreLoader();
