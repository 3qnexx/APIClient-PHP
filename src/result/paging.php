<?php
namespace nexxomnia\result;

class paging{

	protected array $data;
	protected int $size;

	public function __construct(array $data,int $size){
		$this->data=$data;
		$this->size=$size;
	}

	public function updateSize(int $size):void{
		$this->size=$size;
	}

	public function getStart():int{
		return($this->data['start']);
	}

	public function getLimit():int{
		return($this->data['limit']);
	}

	public function getSize():int{
		return($this->size);
	}

	public function getTotalSize():int{
		return($this->data['resultcount']);
	}

	public function hasMoreResults():bool{
		return($this->getStart()+$this->getSize()<$this->getTotalSize());
	}

}