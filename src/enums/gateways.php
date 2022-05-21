<?php
namespace nexxomnia\enums;

class gateways extends \nexxomnia\internals\enum{

	const PC='desktop';
	const MOBILE='mobile';
	const TABLET='tablet';
	const CAR='car';
	const AMP='amp';
	const FBIA='fbia';
	const IOS='ios';
	const ANDROID='android';
	const SMARTTV='smarttv';
	const ANDROIDTV='androidtv';
	const ANDROIDCAR='androidcar';
	const podcast='podcast';

	public static function getMediaGateways():array{
		return([self::PC,self::MOBILE,self::SMARTTV,self::CAR]);
	}

}