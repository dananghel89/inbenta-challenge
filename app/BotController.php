<?php
/**
 * BotController class.
 */

class BotController {

	// Controller index webchat method.
	public function indexWebchat() {
		$header = 'Inbenta Challenge';
		$title  = get_title($header, 'left');

		$vars = [
			'header',
			'title',
		];

		require_view('webchat', compact($vars));
	}

	// Controller index chatbot method.
	public function indexChatbot() {
		$data = json_decode(
			file_get_contents('php://input')
		);

		if ( !empty($data->message) ) {
			echo $this->botLogic($data->message);

		} else {
		 	echo 'Please ask me a question';
		}
	}

	/*----------  Controller Base Methods  ----------*/

	// Controller bot logic method.
	private function botLogic($message = null) {
		$message = string_input_cleaner($message);

		// load api classes and init the client

		$inbenta  = new InbentaAPI();
		$starwars = new StarWarsAPI();

		// list star wars movies for message containing force word

		if ( stripos($message, 'force') !== false ) {
			$movies = $starwars->getMovieList();

			$list = '<p>The <strong>force</strong> is in these movies:</p>';

			if ( !empty($movies) ) {
				$list .= '<ul>';

				foreach ( $movies as $value ) {
					$list .= "<li>$value</li>";
				}

				$list .= '</ul>';
			}

			return $list;
		}

		// send conversation message to inbenta api

		$inbenta->sendMessage($message);

		// list star wars characters for two not found answers

		if ( $inbenta->getNotFoundCount() >= 2 ) {
			$inbenta->resetNotFoundCount();
			$characters = $starwars->getCharacterList();

			$list = "<p>I haven't found any results, but here is a list of some Star Wars characters:</p>";

			if ( !empty($characters) ) {
				$list .= '<ul>';

				foreach ( $characters as $value ) {
					$list .= "<li>$value</li>";
				}

				$list .= '</ul>';
			}

			return $list;
		}

		// answer message from inbenta api

		return $inbenta->getAnswerMessage();
	}

}
