<?php

namespace nexxomnia\apicalls\helpers;

use nexxomnia\enums\streamtypes;

class task{

	protected array $data=[];

	public function __construct(string $name="",string $endpoint="",string $method="",int $item=0,array $parameters=[]){
		if(!empty($name)){
			$this->setName($name);
		}
		if(!empty($endpoint)){
			$this->setEndpoint($endpoint);
		}
		if(!empty($method)){
			$this->setMethod($method);
		}
		if(!empty($item)){
			$this->setItem($item);
		}
		if(!empty($parameters)){
			$this->setParameters($parameters);
		}
	}

	public function setName(string $name):void{
		$this->data['name']=$name;
	}

	public function setEndpoint(string $endpoint):void{
		if(in_array($endpoint,streamtypes::getAllTypes())){
			$endpoint=streamtypes::getPluralizedStreamtype($endpoint);
		}
		$this->data['endpoint']=$endpoint;
	}

	public function setMethod(string $method):void{
		$this->data['method']=$method;
	}

	public function setItem(int $item):void{
		$this->data['item']=$item;
	}

	public function setParameters(array $params):void{
		$this->data['parameters']=$params;
	}

	public function isValid():bool{
		return((!empty($this->data['name']))&&(!empty($this->data['endpoint']))&&(!empty($this->data['method'])));
	}

	public function get():?array{
		$toreturn=NULL;
		if($this->isValid()){
			$toreturn=$this->data;
		}
		return($toreturn);
	}
}