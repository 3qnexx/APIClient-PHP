<?php
namespace nexxomnia\result;

class metadata{

	protected ?array $data=NULL;
	protected ?string $apiVersion="";

	public function __construct($data){
		$this->data=$data;
		if(($data)&&(array_key_exists('version',$this->data))){
			$this->apiVersion="4.0";
		}else{
			$this->apiVersion=$this->getAPIVersion();
		}
	}

	public function updateProcessingTime(float $time):void{
		$this->data[($this->apiVersion=='4.0'?'processingTime':'processingtime')]=$time;
	}

	public function getCode():int{
		return($this->data['status']);
	}

	public function getAPIVersion():string{
		return(($this->apiVersion=='4.0'?'version':'apiversion'));
	}

	public function getVerb():string{
		return($this->data['verb']);
	}

	public function getProcessingTime():float{
		return($this->data[($this->apiVersion=='4.0'?'processingTime':'processingtime')]);
	}

	public function getCalledWithPath():string{
		return($this->data[($this->apiVersion=='4.0'?'calledWith':'calledwith')]);
	}

	public function getCalledForDomain():int{
		return($this->data[($this->apiVersion=='4.0'?'forDomain':($this->apiVersion=='3.0'?'forclient':'fordomain'))]);
	}

	public function getCalledForContext():?string{
		return($this->data[($this->apiVersion=='4.0'?'calledFor':'calledfor')]);
	}

	public function getIsFromCache():?int{
		return($this->data['fromCache']);
	}

	public function getErrorHint():?string{
		return($this->data[($this->apiVersion=='4.0'?'errorHint':'errorhint')]);
	}

	public function getNotice():?string{
		return($this->data['notice']);
	}

}