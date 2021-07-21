<?php
namespace nexxOMNIA\result;

class iterator implements \Iterator{

	private array $data;

	public function __construct(array $data){
		$this->data=$data;
	}

	public function count():int{
		return(sizeof($this->data));
	}

	public function current():?array{
		return(current($this->data));
	}

	public function next(){
		return(next($this->data));
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