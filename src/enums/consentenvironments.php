<?php
namespace nexxomnia\enums;

class consentenvironments{

	const NONE='none';
	const ONLYSTRING='onlystring';
	const V1='1';
	const V2='2';


	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}