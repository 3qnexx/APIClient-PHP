<?php

namespace nexxomnia\apicalls\modifiers;

use nexxomnia\internals\modifiers;

class sessionmodifiers extends modifiers{

	public function __construct(){
		parent::__construct();
	}

	public function addDomainData():void{
		$this->params['addDomainData']=1;
	}

	public function addTextTemplates():void{
		$this->params['addTextTemplates']=1;
	}

	public function addPriceModel():void{
		$this->params['addPriceModel']=1;
	}

	public function addAdModel():void{
		$this->params['addAdModel']=1;
	}

	public function addChannels():void{
		$this->params['addChannels']=1;
	}

	public function addFormats(){
		$this->params['addFormats']=1;
	}
}