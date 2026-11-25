<?php
/**
 * StarWarsAPI class.
 */

use GuzzleHttp\Client;

class StarWarsAPI {

	// SW constants.
	const URL = "https://swapi.co/api/";

	// SW properties.
	private $client;

	// SW constructor.
	public function __construct() {
		$this->client = new Client(
			[ 'base_uri' => self::URL ]
		);
	}

	// SW get movie list method.
	public function getMovieList() {
		try {
			$response = $this->client->request('GET', 'films');
			$body = $response->getBody();

			$obj = json_decode(
				(string) $body
			);

			$result = !empty($obj->results) ? array_column($obj->results, 'title') : [];

		} catch (Exception $e) {
			$result = [
				'A New Hope',
				'Attack of the Clones',
				'The Phantom Menace',
				'Revenge of the Sith',
				'Return of the Jedi',
				'The Empire Strikes Back',
				'The Force Awakens',
			];
		}

		return $result;
	}

	// SW get character list method.
	public function getCharacterList() {
		try {
			$response = $this->client->request('GET', 'people');
			$body = $response->getBody();

			$obj = json_decode(
				(string) $body
			);

			$result = !empty($obj->results) ? array_column($obj->results, 'name') : [];

		} catch (Exception $e) {
			$result = [
				'Luke Skywalker',
				'C-3PO',
				'R2-D2',
				'Darth Vader',
				'Leia Organa',
				'Owen Lars',
				'Beru Whitesun lars',
				'R5-D4',
				'Biggs Darklighter',
				'Obi-Wan Kenobi',
			];
		}

		return $result;
	}

}
