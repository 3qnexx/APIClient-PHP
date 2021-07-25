<?php
namespace nexxOMNIA\result;

class resultobject{

	protected array $data;

	public function __construct(array $data){
		$this->data=$data;
	}

	public function __call(string $name,array $arguments){
		$toreturn=NULL;
		$originalName=$name;
		$name=strtolower($name);
		print_r($this->data);
		if(substr($name,0,3)=="get"){
			$name=substr($name,3);
			$originalName=lcfirst(substr($originalName,3));
		}
		if(isset($this->data[$name])){
			if(is_array($this->data[$name])){
				return (new resultobject($this->data[$name]));
			}else{
				$toreturn=$this->data[$name];
			}
		}else if(isset($this->data[$originalName])){
			if(is_array($this->data[$originalName])){
				return(new resultobject($this->data[$originalName]));
			}else{
				$toreturn=$this->data[$originalName];
			}
		}else if(isset($this->data['itemupdate'])){
			if(isset($this->data['itemupdate'][$name])){
				$toreturn=$this->data['itemupdate'][$name];
			}else if(isset($this->data['itemupdate'][$originalName])){
				$toreturn=$this->data['itemupdate'][$originalName];
			}
		}
		return($toreturn);
	}

}