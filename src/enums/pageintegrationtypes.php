<?php
namespace nexxOMNIA\enums;

class pageintegrationtypes{

	const JS='js';
	const EMBED='embed';
	const WC='wc';
	const NATIVE='native';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}