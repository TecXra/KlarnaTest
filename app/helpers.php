<?php

function flash($title = null, $message = null) {
	// $session->flash('flash_message', $message);
	$flash = app('App\Http\Flash');

	if (func_num_args() == 0) {
		return $flash;
	}

	return $flash->info($title, $message);
}