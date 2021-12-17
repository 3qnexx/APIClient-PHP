<?php
namespace nexxomnia\result;

class metadata{

	protected ?array $data=NULL;

	public function __construct($data){
		$this->data=$data;
	}

	public function updateProcessingTime(float $time):void{
		$this->data['processingtime']=$time;
	}

	public function getCode():int{
		return($this->data['status']);
	}

	public function getAPIVersion():string{
		return($this->data['apiversion']);
	}

	public function getVerb():string{
		return($this->data['verb']);
	}

	public function getProcessingTime():float{
		return($this->data['processingtime']);
	}

	public function getCalledWithPath():string{
		return($this->data['calledwith']);
	}

	public function getCalledForDomain():int{
		return($this->data['fordomain']);
	}

	public function getCalledForContext():?string{
		return($this->data['calledfor']);
	}

	public function getErrorHint():?string{
		return($this->data['errorhint']);
	}

	public function getNotice():?string{
		return($this->data['notice']);
	}

}