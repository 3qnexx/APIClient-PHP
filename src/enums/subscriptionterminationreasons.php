<?php
namespace nexxomnia\enums;

class subscriptionterminationreasons{

	const BYUSER="byuser";
	const INCMS="incms";
	const INAPPAPI="inappapi";
	const RECHARGEPROBLEM="rechargeproblem";
	const PAYMENTCANCELED="paymentcanceled";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}