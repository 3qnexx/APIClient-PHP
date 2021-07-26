<?php
namespace nexxomnia\internal;

class modifiers{

	protected array $params=[];

	public function __construct(){
	}

	public function get():array{
		return($this->params);
	}

	public function addPublishingDetails():void{
		$this->params['addPublishingDetails']=1;
	}
}