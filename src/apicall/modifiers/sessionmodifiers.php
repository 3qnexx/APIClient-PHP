<?php

namespace nexxomnia\apicall\modifiers;

use nexxomnia\internal\modifiers;

class sessionmodifiers extends modifiers{

	public function __construct(){
		parent::__construct();
	}

	public function addDomainData(){
		$this->params['addDomainData']=1;
	}

	public function addTextTemplates(){
		$this->params['addTextTemplates']=1;
	}

	public function addPriceModel(){
		$this->params['addPriceModel']=1;
	}

	public function addAdModel(){
		$this->params['addAdModel']=1;
	}

	public function addChannels(){
		$this->params['addChannels']=1;
	}

	public function addFormats(){
		$this->params['addFormats']=1;
	}
}