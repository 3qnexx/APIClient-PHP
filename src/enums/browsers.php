<?php
namespace nexxomnia\enums;

class browsers{

	const FIREFOX='firefox';
	const CHROME='chrome';
	const EDGE='edge';
	const EXPLORER='explorer';
	const SAFARI='safari';
	const OPERAT='opera';
	const SAMSUNG='samsung browser';
	const HUAWEI='huawei browser';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}