<?php
namespace nexxOMNIA\enums;

class distanceunits{

	const METRIC='metric';
	const MILES='miles';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}