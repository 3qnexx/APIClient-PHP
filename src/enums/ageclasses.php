<?php
namespace nexxOMNIA\enums;

class ageclasses{

	const ZERO=0;
	const SIX=6;
	const TWELVE=12;
	const SIXTEEN=16;
	const EIGHTEEN=18;
	const NOFREE=99;
	const NOTCHECKED=-1;

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}