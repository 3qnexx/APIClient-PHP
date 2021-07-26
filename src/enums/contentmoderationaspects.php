<?php
namespace nexxomnia\enums;

class contentmoderationaspects{

	const SEX='sex';
	const DRUGS='drugs';
	const VIOLENCE='violence';
	const MEDICAL='medical';
	const SPEECH='speech';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}