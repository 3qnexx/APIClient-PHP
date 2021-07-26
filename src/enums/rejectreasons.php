<?php
namespace nexxomnia\enums;

class rejectreasons{

	const DELETE='delete';
	const ARCHIVE='archive';
	const BLOCK='block';
	const NEWVERSION='newversion';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}