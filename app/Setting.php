<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    public static function getPhone()
    {
    	return self::where('name', 'phone')->first()->value;
    }

    public static function getSupportMail()
    {
    	return self::where('name', 'support_mail')->first()->value;
    }

    public static function getOrderMail()
    {
    	return self::where('name', 'order_mail')->first()->value;
    }

    public static function getStreetAddress()
    {
    	return self::where('name', 'street_address')->first()->value;
    }

    public static function getCity()
    {
    	return self::where('name', 'city')->first()->value;
    }

    public static function getPostalCode()
    {
    	return self::where('name', 'postal_code')->first()->value;
    }

    public static function getBankGiro()
    {
    	return self::where('name', 'bank_giro')->first()->value;
    }

    public static function getOrgNumber()
    {
    	return self::where('name', 'org_number')->first()->value;
    }

    public static function getVATNumber()
    {
    	return self::where('name', 'vat_number')->first()->value;
    }

    public static function getFacebookLink()
    {
    	return self::where('name', 'facebook')->first()->value;
    }

    public static function getInstagramLink()
    {
    	return self::where('name', 'instagram')->first()->value;
    }

    public static function getTwitterLink()
    {
    	return self::where('name', 'twitter')->first()->value;
    }

    public static function getYoutubeLink()
    {
    	return self::where('name', 'youtube')->first()->value;
    }

    public static function getGooglePlusLink()
    {
    	return self::where('name', 'google_plus')->first()->value;
    }
}
