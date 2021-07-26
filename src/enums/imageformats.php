<?php
namespace nexxomnia\enums;

class imageformats{

	const CLASSIC="classic";
	const WEBP="webp";
	const AVIF="avif";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}