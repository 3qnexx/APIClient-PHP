<?php
namespace nexxOMNIA\enums;

class scenepurposes{

	const CHAPTER='chapter';
	const OPENING='opening';
	const RECAP='recap';
	const CREDITS='credits';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}