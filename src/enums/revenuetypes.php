<?php
namespace nexxOMNIA\enums;

class revenuetypes{

	const SUBSCRIPTION="subscription";
	const REBILL="rebill";
	const PPV="ppv";
	const OWNAGE="ownage";
	const DEPOSIT="deposit";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}