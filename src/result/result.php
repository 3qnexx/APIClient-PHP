<?php
namespace nexxomnia\result;

class result{

	protected int $code=0;
	protected ?array $raw=NULL;
	protected ?paging $paging=NULL;
	protected ?metadata $metadata=NULL;

	public function __construct(?\Psr\Http\Message\ResponseInterface $response,?\Psr\Log\LoggerInterface $logger=NULL){
		if($response){
			$this->code=$response->getStatusCode();
			$this->raw=json_decode($response->getBody(),TRUE);
			if(!empty($this->raw['metadata'])){
				$this->metadata=new metadata($this->raw['metadata']);
			}
			if($this->isSuccess()){
				if(!empty($this->raw['paging'])){
					$this->paging=new paging($this->raw['paging'],sizeof($this->raw['result']));
				}
			}else if($logger){
				$logger->error("API CALL ERROR: ".$this->code." (".($this->metadata?$this->metadata->getErrorHint():'NO RESPONSE').")");
			}
		}else if($logger){
			$logger->error("API RESULT GOT NO RESPONSE");
		}
	}

	public function getRawResponse():?array{
		return($this->raw);
	}

	public function getStatusCode():int{
		return($this->code);
	}

	public function isSuccess():bool{
		return((!empty($this->code))&&($this->code<400));
	}

	public function isError():bool{
		return((empty($this->code))||($this->code>=400));
	}

	public function getErrorReason():?string{
		$reason=NULL;
		if($this->metadata){
			$reason=$this->metadata->getErrorHint();
		}
		return($reason);
	}

	public function addResults(array $results,float $additionalTime=0):void{
		$this->raw['result']=array_merge($this->raw['result'],$results);
		if(($additionalTime>0)&&($this->metadata)){
			$this->metadata->updateProcessingTime($this->metadata->getProcessingTime()+$additionalTime);
		}
		if($this->paging){
			$this->paging->updateSize(sizeof($this->raw['result']));
		}
	}

	public function getResult():?array{
		return($this->raw['result']);
	}

	/**
	 * @throws \Exception if Result does not support Object
	 */
	public function getResultObject():resultobject{
		if($this->supportsResultObject()){
			$toreturn=new resultobject($this->raw['result']);
		}else{
			throw new \Exception("result cannot be converted to object.");
		}
		return($toreturn);
	}

	/**
	 * @throws \Exception if Result does not support Iterator
	 */
	public function getResultIterator(bool $asMediaObjects=FALSE):iterator{
		if($this->supportsIterator()){
			$toreturn=new iterator($this->raw['result'],$asMediaObjects);
		}else{
			throw new \Exception("result is not iterable.");
		}
		return($toreturn);
	}

	public function supportsIterator():bool{
		$toreturn=FALSE;
		if($this->isSuccess()){
			if((is_array($this->raw['result']))&&(is_array($this->raw['result'][0]))&&(is_array($this->raw['result'][0]['general']))){
				$toreturn=TRUE;
			}
		}
		return($toreturn);
	}

	public function supportsResultObject():bool{
		$toreturn=FALSE;
		if($this->isSuccess()){
			if(($this->raw['result']['general'])||($this->raw['result']['itemupdate'])){
				$toreturn=TRUE;
			}
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