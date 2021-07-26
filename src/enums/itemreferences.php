<?php
namespace nexxomnia\enums;

class itemreferences{

	const ID='ID';
	const GID='GID';
	const HASH='hash';
	const REFNR='refnr';
	const EXTERNALREFERENCE='externalReference';


	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}