<?php
namespace nexxOMNIA\enums;

class livestreamtypes{

	const EVENT="event";
	const PERMANENT="247";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}