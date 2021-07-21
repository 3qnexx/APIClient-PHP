<?php
namespace nexxOMNIA\enums;

class dimensioncodes{

	const HD="hd";
	const FULLHD="fullhd";
	const TWOK="2k";
	const FOURK="4k";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}