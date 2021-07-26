<?php
namespace nexxomnia\enums;

class devicetypes{

	const PC='video';
	const MOBILE='mobile';
	const TABLET='tablet';
	const TV='tv';
	const SMART='smart';


	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}