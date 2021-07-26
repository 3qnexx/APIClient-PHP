<?php
namespace nexxomnia\enums;

class playbackmodes{

	const BUMPER="bumper";
	const PREVIEW="preview";
	const PSEUDOLIVE="pseudolive";
	const SCENESPLIT="scenesplit";
	const STORY="story";
	const PRESENTATION="presentation";
	const ENDLESS="endless";
	const PREMIERE="premiere";
	const MINI="mini";
	const MICRO="micro";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}