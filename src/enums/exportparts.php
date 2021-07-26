<?php
namespace nexxomnia\enums;

class exportparts{

	const ALL="all";
	const METADATA="metadata";
	const CAPTIONS="captions";
	const COVER="cover";
	const VIDEO="video";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}