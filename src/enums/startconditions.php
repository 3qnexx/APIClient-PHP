<?php
namespace nexxOMNIA\enums;

class startconditions{

	const AUTOPLAY='autoplay';
	const AUTOPLAYMUTED='autoplaymuted';
	const MANUAL='manual';
	const MANUALMUTED='manualmuted';


	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}