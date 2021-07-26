<?php
namespace nexxOMNIA\enums;

class autoorderattributes{

	const TITLE='title';
	const SUBTITLE='subtitle';
	const CREATED='created';
	const UPLOADED='uploaded';
	const RELEASEDATE='releasedate';
	const RUNTIME='runtime';
	const FILESIZE='filesize';
	const RANDOM='random';
	const IGPOP='igpop';

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}