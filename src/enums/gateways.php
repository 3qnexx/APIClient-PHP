<?php
namespace nexxomnia\enums;

class gateways extends \nexxomnia\internals\enum{

	const PC='desktop';
	const MOBILE='mobile';
	const TABLET='tablet';
	const CAR='car';
	const AMP='amp';
	const IOS='ios';
	const ANDROID='android';
	const SMARTTV='smarttv';
	const CHROMECAST='chromecast';
	const ANDROIDTV='androidtv';
	const ANDROIDAUTO='androidauto';
	const CARPLAY='carplay';
	const PODCAST='podcast';
	const WATCH='watch';
	const XBOX='xbox';
	const PLAYSTATION='playstation';
	const WINDOWS='windows';
	const CHROMEOS='chromeos';
	const MACOS='macos';
	const VR='vr';

	public static function getMediaGateways():array{
		return([self::PC,self::MOBILE,self::SMARTTV,self::CAR]);
	}

}