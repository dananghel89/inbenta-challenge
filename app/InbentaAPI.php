<?php
/**
 * InbentaAPI class.
 */

use GuzzleHttp\Client;

class InbentaAPI {

	// Inbenta constants.
	const AUTH_URL = "https://api.inbenta.io/v1/auth";
	const CONV_URL = "https://api-gce3.inbenta.io/prod/chatbot/v1/conversation";

	const ACCESS_TOKEN     = 'access_token';
	const TOKEN_EXPIRATION = 'token_expiration';
	const SESSION_TOKEN    = 'session_token';
	const NOT_FOUND_COUNT  = 'not_found_count';

	// Inbenta properties.
	private $client;
	private $answer;

	// Inbenta constructor.
	public function __construct() {
		$this->client = new Client();
		$expiration = $this->getTokenExpiration();

		// set access token if doesn't exist or expired

		if ( empty($this->getAccessToken()) || time() > $expiration ) {
			$this->setAccessToken();
		}

		// set session token if doesn't exist or expired

		if ( empty($this->getSessionToken()) || time() > $expiration ) {
			$this->setSessionToken();
		}
	}

	// Inbenta set session var method.
	private function setSessionVar($value) {
		return isset($_SESSION[$value]) ? $_SESSION[$value] : null;
	}

	// Inbenta get access token method.
	private function getAccessToken() {
		return $this->setSessionVar(self::ACCESS_TOKEN);
	}

	// Inbenta get token expiration method.
	private function getTokenExpiration() {
		return $this->setSessionVar(self::TOKEN_EXPIRATION);
	}

	// Inbenta get session token method.
	private function getSessionToken() {
		return $this->setSessionVar(self::SESSION_TOKEN);
	}

	// Inbenta get not found count method.
	public function getNotFoundCount() {
		return $this->setSessionVar(self::NOT_FOUND_COUNT);
	}

	// Inbenta get answer message method.
	public function getAnswerMessage() {
		if ( !empty($this->answer->message) ) {
			$response = $this->answer->message;

		} else {
			$messages = [
				"I'm sorry, I couldn't find any information relating to your question. Please search for another word or phrase.",
				"I've had a look and there's nothing I can find which fits your question. Please search again using a different word or phrase.",
				"Sorry, we couldn't find any answers to your question.",
			];

			$rand_key = array_rand($messages);

			$response = $messages[$rand_key];
		}

		return $response;
	}

	// Inbenta set access token method.
	private function setAccessToken() {
		$headers = [
			'x-inbenta-key' => CoreConfig::$inbenta->app_key,
			'Content-Type'  => 'application/json',
		];

		$body = [ 'secret' => CoreConfig::$inbenta->app_secret ];

		try {
			$response = $this->client->post(self::AUTH_URL, [
				'headers' => $headers,
				'body' => json_encode($body),
			]);

			$body = $response->getBody();

			$data = json_decode(
				(string) $body
			);

			$_SESSION[self::ACCESS_TOKEN] = $data->accessToken;
			$_SESSION[self::TOKEN_EXPIRATION] = $data->expiration;

		} catch (Exception $e) {
			unset($e);
		}
	}

	// Inbenta set session token method.
	private function setSessionToken() {
		$access_token = $this->getAccessToken();

		$headers = [
			'x-inbenta-key' => CoreConfig::$inbenta->app_key,
			'Authorization' => "Bearer $access_token",
		];

		try {
			$response = $this->client->post(self::CONV_URL, [
				'headers' => $headers
			]);

			$body = $response->getBody();

			$data = json_decode(
				(string) $body
			);

			$_SESSION[self::SESSION_TOKEN] = $data->sessionToken;

		} catch (Exception $e) {
			unset($e);
		}
	}

	// Inbenta reset not found count method.
	public function resetNotFoundCount() {
		return $_SESSION[self::NOT_FOUND_COUNT] = 0;
	}

	// Inbenta send message method.
	public function sendMessage($message) {
		$access_token  = $this->getAccessToken();
		$session_token = $this->getSessionToken();

		$headers = [
			'x-inbenta-key'     => CoreConfig::$inbenta->app_key,
			'Authorization'     => "Bearer $access_token",
			'x-inbenta-session' => "Bearer $session_token",
		];

		$params = [ 'message' => $message ];

		try {
			$response = $this->client->post(self::CONV_URL . '/message', [
				'headers' => $headers,
				'form_params' => $params,
			]);

			$body = $response->getBody();

			$data = json_decode(
				(string) $body
			);

			$result = !empty($data->answers[0]) ? $data->answers[0] : null;

			if ( !empty($result) ) {
				if ( in_array('no-results', $result->flags) ) {
					$i = (int) $this->getNotFoundCount();
					$count = ++$i;

				} else {
					$count = 0;
				}

				$_SESSION[self::NOT_FOUND_COUNT] = $count;

				$this->answer = $result;
			}

		} catch (Exception $e) {
			unset($e);
		}
	}

}
