<?php
namespace nexxOMNIA\enums;

class querymodes{

	const FULLTEXT='fulltext';
	const CLASSIC_AND='classicwithand';
	const CLASSIC_OR='classicwithor';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}