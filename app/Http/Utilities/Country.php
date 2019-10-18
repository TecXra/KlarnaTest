<?php

namespace App\Http\Utilities;

class Country {

	protected static $countries = [
	  "Sverige"                                => "SE",
	  "Danmark"                                => "DK",
	  "Finland"                                => "FI",
	  "Norge"                                  => "NO",
	  // "Netherlands"                            => "NL",
	  // "Germany"                                => "DE"
	];

	public static function all()
	{
		return static::$countries;
	}

	public static function find($code) {
		foreach (static::$countries as $country => $cc) {
			if($code == $cc)
				return $country;
		}
	}

}