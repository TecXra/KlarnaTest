<?php

namespace App\Http;

class Flash {

	/* Step 1 is refactored to step 2 */

	// public function message($title, $message)
	// {
	// 	session()->flash('flash_message', [
	// 		'title' 	=> $title,
	// 		'message' 	=> $message,
	// 		'level' 	=> 'info'
	// 	]);
	// }

	// public function success($title, $message)
	// {
	// 	session()->flash('flash_message', [
	// 		'title' 	=> $title,
	// 		'message' 	=> $message,
	// 		'level' 	=> 'success'
	// 	]);
	// }

	// public function error($title, $message)
	// {
	// 	session()->flash('flash_message', [
	// 		'title' 	=> $title,
	// 		'message' 	=> $message,
	// 		'level' 	=> 'error'
	// 	]);
	// }

	/*  Step 2 is refactored to step 3 */
	
	/**
	 * Create a flash message.
	 * 
	 * @param  string 		$title
	 * @param  string 		$message
	 * @param  string 		$level  
	 * @param  string|null  $key    
	 * @return void        
	 */
	public function create($title, $message, $level, $key = 'flash_message')
	{
		return session()->flash($key, [
			'title' 	=> $title,
			'message' 	=> $message,
			'level' 	=> $level
		]);	
	}

	/**
	 * Create an information flash message.
	 * 
	 * @param  string $title   
	 * @param  string $message 
	 * @return void         
	 */
	public function info($title, $message)
	{
		$this->create($title, $message, 'info');
	}

	/**
	 * Create a success flash message.
	 * 
	 * @param  string $title   
	 * @param  string $message 
	 * @return void         
	 */
	public function success($title, $message)
	{
		$this->create($title, $message, 'success');
	}

	/**
	 * Create an error flash message.
	 * 
	 * @param  string $title   
	 * @param  string $message 
	 * @return void         
	 */
	public function error($title, $message)
	{
		$this->create($title, $message, 'danger');
	}

	/**
	 * Create an overlay flash message.
	 * 
	 * @param  string 		$title  
	 * @param  string 		$message
	 * @param  string|null 	$level  
	 * @return void        
	 */
	public function overlay($title, $message, $level = 'success')
	{
		$this->create($title, $message, $level, 'flash_message_overlay');
	}
	

	/** Steg 3 gör ovanstånde steg 1 och 2 med magic methods */

	// public function __call($level, $args) 
	// {
	// 	// dd($level, $args);

	// 	session()->flash('flash_message', [
	// 		'title' 	=> $args[0],
	// 		'message' 	=> $args[1],
	// 		'level' 	=> $level
	// 	]);	
	// }
}