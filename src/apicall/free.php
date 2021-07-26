<?php

namespace nexxomnia\apicall;

use nexxomnia\enums\defaults;

class free extends \nexxomnia\internal\apicall{

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