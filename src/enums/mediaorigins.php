<?php
namespace nexxOMNIA\enums;

class mediaorigins{

	const OWN='own';
	const REMOTE='remote';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}