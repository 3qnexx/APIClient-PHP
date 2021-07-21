<?php
namespace nexxOMNIA\enums;

class kpis{

	const DISPLAY="display";
	const PLAYERSTART="playerstart";
	const VIEW="view";
	const VIEWTIME="viewtime";
	const VIEWEXTERNAL="viewexternal";
	const DOWNLOAD="download";
	const CLICK="click";
	const PROGRESS25="progress25";
	const PROGRESS50="progress50";
	const PROGRESS75="progress75";
	const PROGRESS95="progress95";
	const FINISHED="progress100";
	const ADREQUEST="adrequest";
	const ADIMPRESSION="adimpression";

	public static function getAllTypes():array{
		$reflect=new \ReflectionClass(static::class);
		return array_values($reflect->getConstants());
	}

}