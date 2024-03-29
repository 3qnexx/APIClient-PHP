<?php
namespace nexxomnia\enums;

class streamtypes extends  \nexxomnia\internals\enum{

	const VIDEO='video';
	const AUDIO='audio';
	const LIVE='live';
	const SCENE='scene';
	const PLAYLIST='playlist';
	const RADIO='radio';
	const COLLECTION='collection';
	const SET='set';
	const RACK='rack';
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
	const PRODUCT='product';
	const GROUP='group';
	const SHOW='show';
	const POLL='poll';
	const FORM='form';
	const VOTING='voting';
	const VARIANT='variant';

	const ALLMEDIA='allmedia';

	public static function getPluralizedStreamtype(string $streamtype):string{
		$plural=$streamtype;
		switch($plural){
			case self::SERIES:
			case self::AUDIO:
			case self::LIVE:
			case self::RADIO:
			case self::ALLMEDIA:
			break;
			default:
				if(substr($plural,-8)=="category"){
					$plural=str_replace("category","categories",$plural);
				}else if(substr($plural,-1)!='s'){
					$plural.='s';
				}
			break;
		}
		return($plural);
	}

	public static function getUploadableTypes():array{
		return([self::VIDEO,self::AUDIO,self::IMAGE,self::FILE]);
	}

	public static function getDownloadLinkTypes():array{
		return([self::VIDEO,self::AUDIO,self::IMAGE,self::FILE,self::SCENE,self::VARIANT]);
	}

	public static function getPlayerTypes():array{
		return([self::VIDEO,self::PLAYLIST,self::SET,self::COLLECTION,self::AUDIO,self::RADIO,self::AUDIOALBUM,self::LIVE,self::SCENE,self::RACK,self::VARIANT]);
	}

	public static function getContainerTypes():array{
		return([self::PLAYLIST,self::SET,self::ALBUM,self::COLLECTION,self::AUDIOALBUM,self::FOLDER,self::MAGAZINE,self::GROUP,self::BUNDLE,self::SERIES,self::RACK]);
	}

	public static function getSimpleContainerTypes():array{
		return([self::PLAYLIST,self::ALBUM,self::AUDIOALBUM,self::MAGAZINE]);
	}

	public static function getExportableTypes():array{
		return([self::VIDEO,self::LIVE,self::IMAGE,self::SCENE]);
	}

	public static function getAllTypes():array{
		$toreturn=[];
		$reflect=new \ReflectionClass(static::class);
		foreach(array_values($reflect->getConstants()) as $type){
			if($type!=self::VARIANT){
				array_push($toreturn,$type);
			}
		}
		return($toreturn);
	}
}