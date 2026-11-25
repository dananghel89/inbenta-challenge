<?php

// Useful PHP debugging function.
if ( !function_exists('dd') ) {
	function dd($code, $die = true) {
		if ( is_string($code) || is_int($code) ) {
			echo $code . '<br>';
		} else {
			echo '<pre>';
			print_r($code);
			echo '</pre>';
		}

		if ( $die ) {
			die();
		}
	}
}

// Redirects to another page.
if ( !function_exists('header_redirect') ) {
	function header_redirect($location, $status = 302) {
		header('Location:' . $location, true, $status);
		exit();
	}
}

// Check if the request is AJAX.
if ( !function_exists('is_ajax') ) {
	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}
}

// Test if the current browser runs on a mobile device.
if ( !function_exists('is_mobile') ) {
	function is_mobile() {
		if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
			$mobile = false;
		} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
				$mobile = true;
		} else {
			$mobile = false;
		}

		return $mobile;
	}
}

// Sanitize data and prevent SQL injections.
if ( !function_exists('string_input_cleaner') ) {
	function string_input_cleaner($data, $lowercase = false) {
		$data = trim($data);
		$data = stripslashes($data); 
		$data = filter_var($data, FILTER_SANITIZE_STRING);

		if ( $lowercase !== false ) {
			$data = mb_strtolower($data);
		}

		return $data;
	}
}

// Get URL from path.
if ( !function_exists('get_url') ) {
	function get_url($path = '') {
		$url = '//' . $_SERVER['HTTP_HOST'];

		if ( !empty($path) ) {
			$url .= '/' . ltrim($path, '/');
		}

		return $url;
	}
}

// Compress HTML removing unnecessary data.
if ( !function_exists('minify_html') ) {
	function minify_html($buffer) {
		$find = ["\n", "\t"];
		return str_replace($find, '', remove_html_comments($buffer));
	}
}

// Remove unwanted HTML comments.
if ( !function_exists('remove_html_comments') ) {
	function remove_html_comments($content = '') {
		return preg_replace('/<!--(.|\s)*?-->/', '', $content);
	}
}

// Require view from part name.
if ( !function_exists('require_view') ) {
	function require_view($name, $vars = []) {
		extract($vars);

		if ( CoreConfig::$app->env === 'production' ) {
			ob_start('minify_html');
			require get_part($name);
			ob_end_flush();

		} else {
			require get_part($name);
		}
	}
}

// Get part from name.
if ( !function_exists('get_part') ) {
	function get_part($name) {
		return CORE_VIEWS . $name . '.php';
	}
}

// Get title for views.
if ( !function_exists('get_title') ) {
	function get_title($title, $position = 'right', $separator = '&ndash;') {
		$app_name = CoreConfig::$app->name;

		if ( $position == 'left' ) {
			$title = $app_name . ' ' . $separator . ' ' . $title;

		} elseif ( $position == 'right' ) {
			$title = $title . ' ' . $separator . ' ' . $app_name;
		}

		return $title;
	}
}
