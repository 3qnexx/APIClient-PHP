<?php
namespace nexxomnia\enums;

class gateways{

	const PC='desktop';
	const MOBILE='mobile';
	const TABLET='tablet';
	const AMP='amp';
	const FBIA='fbia';
	const IOS='ios';
	const ANDROID='android';
	const SMARTTV='smarttv';
	const ANDROIDTV='androidtv';
	const podcast='podcast';


	public static function getAllGateways():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

	public static function getMediaGateways():array{
		return([self::PC,self::MOBILE,self::SMARTTV]);
	}

}