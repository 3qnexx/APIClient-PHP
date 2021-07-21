<?php
namespace nexxOMNIA\enums;

class registrationproviders{

	const FACEBOOK="facebook";
	const GOOGLE="google";
	const TWITTER="twitter";
	const AAD="aad";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}