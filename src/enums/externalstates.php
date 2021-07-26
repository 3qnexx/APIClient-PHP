<?php
namespace nexxOMNIA\enums;

class externalstates{

	const PUBLIC="public";
	const UNLISTED="unlisted";
	const PRIVATE="private";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}