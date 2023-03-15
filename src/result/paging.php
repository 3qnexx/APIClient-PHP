<?php
namespace nexxomnia\result;

class paging{

	protected array $data;
	protected int $size;
	protected string $apiVersion;

	public function __construct(array $data,int $size,string $apiVersion=""){
		$this->data=$data;
		$this->size=$size;
		$this->apiVersion=$apiVersion;
	}

	public function updateSize(int $size):void{
		$this->size=$size;
	}

	public function getStart():int{
		return($this->data[($this->apiVersion=='4.0'?'offset':'start')]);
	}

	public function getOffset():int{
		return($this->getStart());
	}

	public function getLimit():int{
		return($this->data['limit']);
	}

	public function getSize():int{
		return($this->size);
	}

	public function getTotalSize():int{
		return($this->data[($this->apiVersion=='4.0'?'total':'resultcount')]);
	}

	public function hasMoreResults():bool{
		return(($this->getStart()+$this->getSize())<$this->getTotalSize());
	}

}