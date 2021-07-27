<?php
namespace nexxomnia\internals;

class enum{

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}
}