<?php
namespace nexxomnia\enums;

class temperatureunits{

	const CELSIUS='celsius';
	const FAHRENHEIT='fahrenheit';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}