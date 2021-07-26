<?php
namespace nexxomnia\enums;

class externalplatforms{

	const FACEBOOK='facebook';
	const INSTAGRAM='instagram';
	const TWITTER='twitter';
	const VIMEO='vimeo';
	const YOUTUBE='youtube';
	const EXTERNALVIEW='externalview';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}