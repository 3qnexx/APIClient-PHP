<?php

namespace nexxOMNIA\apicall;

use nexxOMNIA\enums\defaults;

class free extends \nexxOMNIA\internal\apicall{

	public function __construct(){
		parent::__construct();
	}

	public function setPath(string $path):void{
		if(substr($path,0,1)=="/"){
			$path=substr($path,1);
		}
		$this->path=$path;
	}

	public function setVerb(string $verb):void{
		if(in_array($verb,defaults::getAllVerbs())){
			$this->verb=$verb;
		}else{
			throw new \Exception("verb must be in ".implode(",",defaults::getAllVerbs()));
		}
	}
}