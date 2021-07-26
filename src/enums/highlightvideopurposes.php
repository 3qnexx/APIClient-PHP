<?php
namespace nexxOMNIA\enums;

class highlightvideopurposes{

	const VIDEO='video';
	const VARIANT='variant';
	const TRAILER='trailer';
	const BONUS='bonus';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}