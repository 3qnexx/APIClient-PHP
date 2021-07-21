<?php
namespace nexxOMNIA\result;

class result{

	protected int $code=0;
	protected ?array $raw=NULL;
	protected ?paging $paging=NULL;
	protected ?metadata $metadata=NULL;

	public function __construct(?\Psr\Http\Message\ResponseInterface $response){
		if($response){
			$this->code=intval($response->getStatusCode());
			$this->raw=json_decode($response->getBody(),TRUE);
			if($this->wasSuccessfull()){
				if($this->raw['paging']){
					$this->paging=new paging($this->raw['paging'],sizeof($this->raw['result']));
				}
				if($this->raw['metadata']){
					$this->metadata=new metadata($this->raw['metadata']);
				}
			}
		}
	}

	public function getRawResponse():?array{
		return($this->raw);
	}

	public function getStatusCode():int{
		return($this->code);
	}

	public function wasSuccessfull():bool{
		return((!empty($this->code))&&($this->code<400));
	}

	public function getErrorReason():?string{
		$reason=NULL;
		if($this->metadata){
			$reason=$this->metadata->getErrorHint();
		}
		return($reason);
	}

	public function addResults(array $results):void{
		$this->raw['result']=array_merge($this->raw['result'],$results);
		if($this->paging){
			$this->paging->updateSize(sizeof($this->raw['result']));
		}
	}

	public function getResult():?array{
		return($this->raw['result']);
	}

	public function getResultIterator():iterator{
		if($this->supportsPaging()){
			$toreturn=new iterator($this->raw['result']);
		}else{
			throw new \Exception("result is not pageable");
		}
		return($toreturn);
	}

	public function supportsPaging():bool{
		return($this->getPaging()!==NULL);
	}

	public function getPaging():?paging{
		return($this->paging);
	}

	public function getMetadata():?metadata{
		return($this->metadata);
	}

}