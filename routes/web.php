<?php

use NoahBuscher\Macaw\Macaw as Route;

/* Web routes. */

Route::get('/', 'BotController@indexWebchat');
Route::post('/conversation', 'BotController@indexChatbot');

/* Error route. */

Route::error(function() {
	header_redirect(
		get_url()
	);
});
