<?php
namespace nexxOMNIA\enums;

class streamtypes{

	const VIDEO='video';
	const AUDIO='audio';
	const LIVE='live';
	const SCENE='scene';
	const PLAYLIST='playlist';
	const RADIO='radio';
	const COLLECTION='collection';
	const SET='set';
	const SERIES='series';
	const BUNDLE='bundle';
	const IMAGE='image';
	const ALBUM='album';
	const AUDIOALBUM='audioalbum';
	const FILE='file';
	const FOLDER='folder';
	const EVENT='event';
	const ARTICLE='article';
	const MAGAZINE='magazine';
	const PLACE='place';
	const LINK='link';
	const POST='post';
	const PERSON='person';
	const GROUP='group';
	const SHOW='show';

	const ALLMEDIA='allmedia';

	public static function getPluralizedStreamtype(string $streamtype):string{
		$plural=$streamtype;
		switch($plural){
			case streamtypes::SERIES:
			case streamtypes::AUDIO:
			case streamtypes::LIVE:
			case streamtypes::RADIO:
			case streamtypes::ALLMEDIA:
			break;
			default:
				if(substr($plural,-1)!='s'){
					$plural.='s';
				}
			break;
		}
		return($plural);
	}

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}