<?php
namespace nexxOMNIA\enums;

class networkmodes{

	const MIXED="mixed";
	const MASTER="master";
	const OWN="own";
	const ALL="all";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}