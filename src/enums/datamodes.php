<?php
namespace nexxOMNIA\enums;

class datamodes{

	const API='api';
	const STATIC='static';
	const OFFLINE='offline';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}