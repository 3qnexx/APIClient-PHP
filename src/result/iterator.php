<?php
namespace nexxOMNIA\result;

class iterator implements \Iterator{

	private array $data;
	private bool $asMediaObjects;

	public function __construct(array $data,bool $asMediaObjects=FALSE){
		$this->data=$data;
		$this->asMediaObjects=$asMediaObjects;
	}

	public function count():int{
		return(sizeof($this->data));
	}

	public function current(){
		$toreturn=current($this->data);
		if(($toreturn)&&($this->asMediaObjects)){
			$toreturn=new resultobject($toreturn);
		}
		return($toreturn);
	}

	public function next(){
		$toreturn=next($this->data);
		if(($toreturn)&&($this->asMediaObjects)){
			$toreturn=new resultobject($toreturn);
		}
		return($toreturn);
	}

	public function key():?int{
		return(key($this->data));
	}

	public function valid():bool{
		return(key($this->data)!==NULL);
	}

	public function rewind():bool{
		return(reset($this->data));
	}
}