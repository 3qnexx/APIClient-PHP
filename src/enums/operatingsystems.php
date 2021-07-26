<?php
namespace nexxomnia\enums;

class operatingsystems{

	const WINDOWS='windows';
	const MACOS='macOS';
	const LINUX='linux';
	const IOS='ios';
	const ANDROID='android';
	const CHROMEOS='chromeOS';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}